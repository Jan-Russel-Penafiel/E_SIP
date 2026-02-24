<?php
/**
 * E-SIP — Test Suite
 * CLI-based tests for core classes, models, and business logic.
 *
 * Run: php tests/test.php
 */

// ─── Bootstrap ───
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class) {
    $map = [
        'Core\\'        => BASE_PATH . '/core/',
        'Models\\'      => BASE_PATH . '/models/',
        'Controllers\\' => BASE_PATH . '/controllers/',
    ];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $file = $dir . substr($class, strlen($prefix)) . '.php';
            if (file_exists($file)) { require_once $file; return; }
        }
    }
});

// ─── Test Helpers ───
$passed  = 0;
$failed  = 0;
$errors  = [];

function assert_true(bool $condition, string $label): void
{
    global $passed, $failed, $errors;
    if ($condition) {
        $passed++;
        echo "  ✓ {$label}\n";
    } else {
        $failed++;
        $errors[] = $label;
        echo "  ✗ {$label}\n";
    }
}

function section(string $title): void
{
    echo "\n━━━ {$title} ━━━\n";
}

// ─── Backup existing data ───
$dataDir   = BASE_PATH . '/data';
$backupDir = BASE_PATH . '/data_backup_' . time();
$jsonFiles = ['users.json', 'progress.json', 'leaderboard.json'];

echo "E-SIP Test Suite\n";
echo "================\n";

// Create backup
if (is_dir($dataDir)) {
    mkdir($backupDir, 0755, true);
    foreach ($jsonFiles as $file) {
        $src = $dataDir . '/' . $file;
        if (file_exists($src)) {
            copy($src, $backupDir . '/' . $file);
        }
    }
}

// Reset mutable JSON files for clean tests
foreach ($jsonFiles as $file) {
    file_put_contents($dataDir . '/' . $file, json_encode([], JSON_PRETTY_PRINT));
}

// Reset singletons so they reload fresh data
\Core\Database::resetInstances();

// ═══════════════════════════════════════════
//  1. Database Singleton Tests
// ═══════════════════════════════════════════
section('Database Singleton');

$db1 = \Core\Database::getInstance('users');
$db2 = \Core\Database::getInstance('users');
assert_true($db1 === $db2, 'Singleton returns same instance for same collection');

$db3 = \Core\Database::getInstance('progress');
assert_true($db1 !== $db3, 'Different collection returns different instance');

// Insert
$record = $db1->insert(['username' => 'testuser', 'email' => 'test@test.com']);
assert_true(isset($record['id']), 'Insert returns record with generated ID');
assert_true($record['username'] === 'testuser', 'Insert preserves data');
assert_true(isset($record['created_at']), 'Insert sets created_at timestamp');
$testId = $record['id'];

// FindById
$found = $db1->findById($testId);
assert_true($found !== null, 'FindById finds existing record');
assert_true($found['username'] === 'testuser', 'FindById returns correct data');

// FindBy
$results = $db1->findBy('email', 'test@test.com');
assert_true(count($results) === 1, 'FindBy returns matching records');
assert_true($results[0]['email'] === 'test@test.com', 'FindBy matches correct field');

// Update
$updated = $db1->update($testId, ['username' => 'updated_user']);
assert_true($updated !== null, 'Update returns updated record');
assert_true($updated['username'] === 'updated_user', 'Update modifies data correctly');
assert_true(isset($updated['updated_at']), 'Update sets updated_at timestamp');

// Count
assert_true($db1->count() === 1, 'Count returns correct number');

// Delete
$deleted = $db1->delete($testId);
assert_true($deleted === true, 'Delete returns true on success');
assert_true($db1->findById($testId) === null, 'Deleted record is not found');
assert_true($db1->count() === 0, 'Count is 0 after delete');

// Delete non-existent
$deletedAgain = $db1->delete('nonexistent');
assert_true($deletedAgain === false, 'Delete returns false for non-existent ID');

// ═══════════════════════════════════════════
//  2. Auth Singleton Tests
// ═══════════════════════════════════════════
section('Auth Singleton');

// Reset for clean state
\Core\Database::resetInstances();
file_put_contents($dataDir . '/users.json', json_encode([], JSON_PRETTY_PRINT));
\Core\Auth::resetInstance();

// We can't fully test Auth when it calls session_start in Session,
// so we test at the model level instead.

// ═══════════════════════════════════════════
//  3. User Model Tests
// ═══════════════════════════════════════════
section('User Model');

\Core\Database::resetInstances();
file_put_contents($dataDir . '/users.json', json_encode([], JSON_PRETTY_PRINT));

$userModel = new \Models\User();

// Manually insert a user via Database
$userDb = \Core\Database::getInstance('users');
$testUser = $userDb->insert([
    'username'             => 'gamer1',
    'email'                => 'gamer1@test.com',
    'password'             => password_hash('secret', PASSWORD_BCRYPT),
    'level'                => 1,
    'xp'                   => 0,
    'streak'               => 0,
    'badges'               => [],
    'difficulty'            => 'beginner',
    'completed_challenges' => [],
    'completed_modules'    => [],
]);
$userId = $testUser['id'];

assert_true($userModel->find($userId) !== null, 'User.find returns user');
assert_true($userModel->findByEmail('gamer1@test.com')['username'] === 'gamer1', 'User.findByEmail works');
assert_true($userModel->findByUsername('gamer1')['email'] === 'gamer1@test.com', 'User.findByUsername works');

// Award XP — should stay level 1 with 50 XP (need 100 to level up)
$afterXP = $userModel->awardXP($userId, 50);
assert_true($afterXP['xp'] === 50, 'AwardXP adds XP correctly');
assert_true($afterXP['level'] === 1, 'Level stays at 1 with 50 XP');

// Award enough to level up: currently 50 XP, level 1 needs 100 to level up → add 50 more
$afterLevelUp = $userModel->awardXP($userId, 50);
assert_true($afterLevelUp['level'] === 2, 'Level up triggers at threshold (100 XP)');
assert_true($afterLevelUp['xp'] === 0, 'XP resets after level up');

// Badge
$badged = $userModel->addBadge($userId, 'first_challenge');
assert_true(in_array('first_challenge', $badged['badges']), 'AddBadge adds badge');
$badged2 = $userModel->addBadge($userId, 'first_challenge');
assert_true(count($badged2['badges']) === 1, 'AddBadge does not duplicate badge');

// Complete challenge
$completed = $userModel->completeChallenge($userId, 'ch001');
assert_true(in_array('ch001', $completed['completed_challenges']), 'CompleteChallenge records challenge ID');

// Complete module
$completedMod = $userModel->completeModule($userId, 'mod001');
assert_true(in_array('mod001', $completedMod['completed_modules']), 'CompleteModule records module ID');

// Set difficulty
$diffUser = $userModel->setDifficulty($userId, 'advanced');
assert_true($diffUser['difficulty'] === 'advanced', 'SetDifficulty changes difficulty');
$invalidDiff = $userModel->setDifficulty($userId, 'impossible');
assert_true($invalidDiff === null, 'SetDifficulty rejects invalid value');

// Streak
$streaked = $userModel->incrementStreak($userId);
assert_true($streaked['streak'] === 1, 'IncrementStreak adds 1');
$userModel->incrementStreak($userId);
$streaked3 = $userModel->incrementStreak($userId);
assert_true($streaked3['streak'] === 3, 'Streak increments correctly');
$reset = $userModel->resetStreak($userId);
assert_true($reset['streak'] === 0, 'ResetStreak sets to 0');

// XP for next level
$currentUser = $userModel->find($userId);
$needed = $userModel->xpForNextLevel($currentUser);
assert_true($needed === $currentUser['level'] * 100, 'XpForNextLevel calculates correctly');

// ═══════════════════════════════════════════
//  4. Challenge Model Tests
// ═══════════════════════════════════════════
section('Challenge Model');

$challengeModel = new \Models\Challenge();

$allChallenges = $challengeModel->all();
assert_true(count($allChallenges) >= 12, 'Seeded challenges loaded (≥12)');

$firstChallenge = $allChallenges[0];
assert_true($challengeModel->find($firstChallenge['id']) !== null, 'Challenge.find works');

// Filter by language
$pythonChallenges = $challengeModel->findByLanguage('python');
assert_true(count($pythonChallenges) > 0, 'FindByLanguage returns Python challenges');

// Filter by difficulty
$beginnerChallenges = $challengeModel->findByDifficulty('beginner');
assert_true(count($beginnerChallenges) > 0, 'FindByDifficulty returns beginner challenges');

// Filtered
$filtered = $challengeModel->getFiltered(null, 'beginner', 'python');
assert_true(count($filtered) > 0, 'GetFiltered applies multiple filters');

// Validate multiple choice
$mcChallenge = null;
foreach ($allChallenges as $c) {
    if ($c['type'] === 'multiple_choice') { $mcChallenge = $c; break; }
}
if ($mcChallenge) {
    $correct = $challengeModel->validateSubmission($mcChallenge['id'], '', $mcChallenge['correct_answer']);
    assert_true($correct['success'] === true, 'Multiple choice: correct answer passes');
    assert_true($correct['xp'] > 0, 'Multiple choice: awards XP on correct');

    $wrong = $challengeModel->validateSubmission($mcChallenge['id'], '', 'wrong_answer');
    assert_true($wrong['success'] === false, 'Multiple choice: wrong answer fails');
    assert_true($wrong['xp'] === 0, 'Multiple choice: no XP on wrong answer');
}

// Validate code challenge
$codeChallenge = null;
foreach ($allChallenges as $c) {
    if ($c['type'] === 'code' && isset($c['expected_output'])) { $codeChallenge = $c; break; }
}
if ($codeChallenge) {
    $passResult = $challengeModel->validateSubmission($codeChallenge['id'], 'code', $codeChallenge['expected_output']);
    assert_true($passResult['success'] === true, 'Code challenge: matching output passes');

    $failResult = $challengeModel->validateSubmission($codeChallenge['id'], 'code', 'wrong output');
    assert_true($failResult['success'] === false, 'Code challenge: wrong output fails');
}

// Random challenge
$random = $challengeModel->getRandomChallenge();
assert_true($random !== null, 'GetRandomChallenge returns a challenge');

// Count
assert_true($challengeModel->count() >= 12, 'Challenge count matches seeded data');

// ═══════════════════════════════════════════
//  5. Progress Model Tests
// ═══════════════════════════════════════════
section('Progress Model');

\Core\Database::resetInstances();
file_put_contents($dataDir . '/progress.json', json_encode([], JSON_PRETTY_PRINT));

$progressModel = new \Models\Progress();

// Record attempts
$attempt1 = $progressModel->recordAttempt($userId, 'ch001', true, 50, 30, 'print("Hello")');
assert_true(isset($attempt1['id']), 'RecordAttempt returns record with ID');
assert_true($attempt1['success'] === true, 'RecordAttempt stores success');
assert_true($attempt1['xp_earned'] === 50, 'RecordAttempt stores XP earned');

$attempt2 = $progressModel->recordAttempt($userId, 'ch002', false, 0, 45, 'bad code');
$attempt3 = $progressModel->recordAttempt($userId, 'ch003', true, 75, 20, 'good code');

// Find by user
$userAttempts = $progressModel->findByUser($userId);
assert_true(count($userAttempts) === 3, 'FindByUser returns all attempts');

// Find by user and challenge
$ch001 = $progressModel->findByUserAndChallenge($userId, 'ch001');
assert_true(count($ch001) === 1, 'FindByUserAndChallenge filters correctly');

// Analytics
$analytics = $progressModel->getUserAnalytics($userId);
assert_true($analytics['total_attempts'] === 3, 'Analytics: correct total attempts');
assert_true($analytics['successful'] === 2, 'Analytics: correct successful count');
assert_true($analytics['success_rate'] === round((2/3)*100, 1), 'Analytics: correct success rate');
assert_true($analytics['total_xp'] === 125, 'Analytics: correct total XP (50+0+75)');
assert_true(count($analytics['recent']) === 3, 'Analytics: recent includes all attempts');

// Empty analytics
$emptyAnalytics = $progressModel->getUserAnalytics('nonexistent');
assert_true($emptyAnalytics['total_attempts'] === 0, 'Analytics: empty for unknown user');
assert_true($emptyAnalytics['success_rate'] === 0, 'Analytics: 0% rate for unknown user');

// Daily activity
$activity = $progressModel->getDailyActivity($userId);
assert_true(count($activity) === 7, 'DailyActivity returns 7 days');
assert_true(isset($activity[0]['date']), 'DailyActivity entry has date');
assert_true(isset($activity[0]['attempts']), 'DailyActivity entry has attempts');

// ═══════════════════════════════════════════
//  6. Leaderboard Model Tests
// ═══════════════════════════════════════════
section('Leaderboard Model');

\Core\Database::resetInstances();
file_put_contents($dataDir . '/leaderboard.json', json_encode([], JSON_PRETTY_PRINT));

$lb = new \Models\Leaderboard();

// Add entries
$lb->updateEntry('user1', 'Alice', 5, 300, 20, 3);
$lb->updateEntry('user2', 'Bob',   3, 150, 10, 1);
$lb->updateEntry('user3', 'Charlie', 8, 500, 35, 7);

// Top players
$top = $lb->getTopPlayers(10);
assert_true(count($top) === 3, 'GetTopPlayers returns all 3 entries');
assert_true($top[0]['username'] === 'Charlie', 'Highest scorer is ranked #1');
assert_true($top[0]['rank'] === 1, 'Rank starts at 1');
assert_true($top[2]['username'] === 'Bob', 'Lowest scorer is last');

// Score formula: (level * 1000) + xp + (challengesCompleted * 50) + (streak * 10)
$expectedCharlie = (8 * 1000) + 500 + (35 * 50) + (7 * 10);
assert_true($top[0]['score'] === $expectedCharlie, 'Score formula is correct');

// User rank
$rank = $lb->getUserRank('user2');
assert_true($rank === 3, 'GetUserRank returns correct position');

$noRank = $lb->getUserRank('nonexistent');
assert_true($noRank === 0, 'GetUserRank returns 0 for unknown user');

// Update existing entry (should update not duplicate)
$lb->updateEntry('user1', 'Alice', 6, 350, 25, 5);
$topAfterUpdate = $lb->getTopPlayers(10);
assert_true(count($topAfterUpdate) === 3, 'UpdateEntry does not create duplicate');
$alice = null;
foreach ($topAfterUpdate as $p) {
    if ($p['user_id'] === 'user1') { $alice = $p; break; }
}
assert_true($alice !== null && $alice['level'] === 6, 'UpdateEntry modifies existing record');

// ═══════════════════════════════════════════
//  7. Module Model Tests
// ═══════════════════════════════════════════
section('Module Model');

$moduleModel = new \Models\Module();
$allModules = $moduleModel->all();
assert_true(count($allModules) >= 8, 'Seeded modules loaded (≥8)');

$firstModule = $allModules[0];
assert_true(isset($firstModule['id']), 'Module has ID');
assert_true(isset($firstModule['title']), 'Module has title');
assert_true(isset($firstModule['language']), 'Module has language');

// Find by ID
$found = $moduleModel->find($firstModule['id']);
assert_true($found !== null && $found['id'] === $firstModule['id'], 'Module.find by ID works');

// Filtered
$filtered = $moduleModel->getFiltered('python', null);
assert_true(count($filtered) > 0, 'Module getFiltered by language works');

// ═══════════════════════════════════════════
//  8. Configuration Tests
// ═══════════════════════════════════════════
section('Configuration');

$config = require BASE_PATH . '/config/app.php';
assert_true($config['name'] === 'E-SIP', 'App name is set');
assert_true(isset($config['gamification']), 'Gamification config exists');
assert_true(isset($config['languages']), 'Languages config exists');
assert_true(count($config['languages']) >= 6, 'At least 6 languages configured');
assert_true(isset($config['difficulty']), 'Difficulty config exists');
assert_true($config['difficulty']['beginner']['xp_multiplier'] === 1.0, 'Beginner XP multiplier is 1.0');
assert_true($config['difficulty']['advanced']['xp_multiplier'] === 2.0, 'Advanced XP multiplier is 2.0');

// ═══════════════════════════════════════════
//  9. Data Integrity Tests
// ═══════════════════════════════════════════
section('Data Integrity');

// Check JSON files are valid
$dataFiles = ['modules.json', 'challenges.json'];
foreach ($dataFiles as $file) {
    $path = $dataDir . '/' . $file;
    assert_true(file_exists($path), "Data file {$file} exists");
    $content = json_decode(file_get_contents($path), true);
    assert_true($content !== null, "Data file {$file} contains valid JSON");
    assert_true(is_array($content), "Data file {$file} is an array");
}

// All challenges reference valid languages
$validLangs = array_keys($config['languages']);
$challengeAll = (new \Models\Challenge())->all();
foreach ($challengeAll as $ch) {
    $inValid = in_array($ch['language'], $validLangs);
    assert_true($inValid, "Challenge '{$ch['id']}' has valid language '{$ch['language']}'");
}

// ═══════════════════════════════════════════
//  Cleanup & Report
// ═══════════════════════════════════════════
echo "\n━━━ Restoring Data ━━━\n";

// Restore from backup
if (is_dir($backupDir)) {
    foreach ($jsonFiles as $file) {
        $backup = $backupDir . '/' . $file;
        if (file_exists($backup)) {
            copy($backup, $dataDir . '/' . $file);
        }
    }
    // Clean up backup
    array_map('unlink', glob($backupDir . '/*'));
    rmdir($backupDir);
    echo "  Original data restored from backup.\n";
} else {
    // No backup existed — clear test data
    foreach ($jsonFiles as $file) {
        file_put_contents($dataDir . '/' . $file, json_encode([], JSON_PRETTY_PRINT));
    }
    echo "  Mutable data files reset.\n";
}

\Core\Database::resetInstances();

// ─── Results ───
echo "\n════════════════════════════════════════\n";
echo "  RESULTS: {$passed} passed, {$failed} failed\n";
echo "════════════════════════════════════════\n";

if ($failed > 0) {
    echo "\nFailed tests:\n";
    foreach ($errors as $e) {
        echo "  • {$e}\n";
    }
    exit(1);
}

echo "\nAll tests passed! ✓\n";
exit(0);
