<?php
/**
 * Challenge Controller
 * Handles challenge listing, playing, and submission.
 */

namespace Controllers;

use Models\Challenge;
use Models\User;
use Models\Progress;
use Models\Leaderboard;

class ChallengeController extends BaseController
{
    private Challenge $challengeModel;
    private User $userModel;
    private Progress $progressModel;

    public function __construct()
    {
        parent::__construct();
        $this->challengeModel = new Challenge();
        $this->userModel      = new User();
        $this->progressModel  = new Progress();
    }

    /** List all challenges with filters */
    public function index(): void
    {
        $this->auth->requireAuth();

        $language   = $this->query('language');
        $difficulty = $this->query('difficulty');
        $moduleId   = $this->query('module');

        $challenges = $this->challengeModel->getFiltered(
            $moduleId ?: null,
            $difficulty ?: null,
            $language ?: null
        );

        $user = $this->auth->user();

        $this->render('challenges/index', [
            'challenges'  => $challenges,
            'user'        => $user,
            'filterLang'  => $language,
            'filterDiff'  => $difficulty,
            'filterMod'   => $moduleId,
            'langConfig'  => $this->config['languages'],
        ], 'Challenges - E-SIP');
    }

    /** Show the challenge play/solve interface */
    public function play(string $id): void
    {
        $this->auth->requireAuth();

        $challenge = $this->challengeModel->find($id);
        if (!$challenge) {
            $this->redirectWithMessage('/challenges', 'Challenge not found.', 'error');
            return;
        }

        $user = $this->auth->user();
        $attempts = $this->progressModel->findByUserAndChallenge($user['id'], $id);
        $isCompleted = in_array($id, $user['completed_challenges'] ?? []);

        $this->render('challenges/play', [
            'challenge'   => $challenge,
            'user'        => $user,
            'attempts'    => $attempts,
            'isCompleted' => $isCompleted,
            'langConfig'  => $this->config['languages'],
        ], $challenge['title'] . ' - E-SIP');
    }

    /** Handle challenge submission (AJAX) */
    public function submit(string $id): void
    {
        $this->auth->requireAuth();

        // Read JSON body for AJAX requests
        $rawInput = file_get_contents('php://input');
        $input    = json_decode($rawInput, true) ?? $_POST;

        $userCode   = $input['code'] ?? '';
        $userOutput = $input['output'] ?? '';
        $timeTaken  = (int)($input['time_taken'] ?? 0);

        $user   = $this->auth->user();
        $userId = $user['id'];

        // Validate submission
        $result = $this->challengeModel->validateSubmission($id, $userCode, $userOutput);

        // Record attempt
        $this->progressModel->recordAttempt(
            $userId,
            $id,
            $result['success'],
            $result['xp'],
            $timeTaken,
            $userCode
        );

        // Award XP and mark completed on success
        if ($result['success']) {
            $alreadyCompleted = in_array($id, $user['completed_challenges'] ?? []);
            if (!$alreadyCompleted) {
                $this->userModel->awardXP($userId, $result['xp']);
                $this->userModel->completeChallenge($userId, $id);
                $this->userModel->incrementStreak($userId);

                // Check for badges
                $updatedUser = $this->userModel->find($userId);
                $completedCount = count($updatedUser['completed_challenges'] ?? []);

                if ($completedCount >= 1) $this->userModel->addBadge($userId, 'first_challenge');
                if ($completedCount >= 5) $this->userModel->addBadge($userId, 'five_challenges');
                if ($completedCount >= 10) $this->userModel->addBadge($userId, 'ten_challenges');
                if ($completedCount >= 25) $this->userModel->addBadge($userId, 'quarter_century');
                if (($updatedUser['streak'] ?? 0) >= 3) $this->userModel->addBadge($userId, 'streak_3');
                if (($updatedUser['streak'] ?? 0) >= 7) $this->userModel->addBadge($userId, 'streak_7');
                if ($updatedUser['level'] >= 5) $this->userModel->addBadge($userId, 'level_5');
                if ($updatedUser['level'] >= 10) $this->userModel->addBadge($userId, 'level_10');

                // Update leaderboard
                $leaderboard = new Leaderboard();
                $leaderboard->updateEntry(
                    $userId,
                    $updatedUser['username'],
                    $updatedUser['level'],
                    $updatedUser['xp'],
                    $completedCount,
                    $updatedUser['streak'] ?? 0
                );

                $result['level_up']  = $updatedUser['level'] > $user['level'];
                $result['new_level'] = $updatedUser['level'];
            } else {
                $result['xp'] = 0; // No XP for re-completing
                $result['message'] = 'Correct! (Already completed — no additional XP)';
            }
        }

        $this->json($result);
    }

    /**
     * Run code locally on the server.
     * POST /challenges/{id}/run  →  { output, status, error }
     */
    public function run(string $id = ''): void
    {
        $this->auth->requireAuth();

        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $code     = trim($input['code']     ?? '');
        $language = strtolower($input['language'] ?? 'javascript');
        $stdin    = $input['stdin'] ?? '';

        if (empty($code)) {
            $this->json(['output' => '', 'status' => 'Empty', 'error' => 'No code provided.']);
            return;
        }

        [$output, $status, $error] = $this->executeLocally($code, $language, $stdin);

        $this->json([
            'output' => $output,
            'status' => $status,
            'time'   => null,
            'memory' => null,
            'error'  => $error,
        ]);
    }

    // ── Local Executor ────────────────────────────────────────────────────────

    private function executeLocally(string $code, string $language, string $stdin = ''): array
    {
        switch ($language) {
            // HTML & CSS are markup — return the code itself as output
            case 'html':
            case 'css':
                return [trim($code), 'Accepted', ''];

            // SQL — run against an in-memory SQLite database
            case 'sql':
                return $this->executeSql($code);

            // PHP — run with the current PHP binary
            case 'php':
                return $this->executeTempFile($code, 'php', PHP_BINARY, $stdin);

            // JavaScript — run with Node.js
            case 'javascript':
            case 'js':
                $node = $this->findExecutable(['node', 'node.exe']);
                if (!$node) {
                    return ['', 'Error', 'Node.js is not installed or not found in PATH. Install Node.js from https://nodejs.org'];
                }
                return $this->executeTempFile($code, 'js', $node, $stdin);

            default:
                return ['', 'Error', "Language '{$language}' is not supported for local execution."];
        }
    }

    /**
     * Write code to a temp file, execute it, return [output, status, error].
     */
    private function executeTempFile(string $code, string $ext, string $binary, string $stdin): array
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'esip_') . '.' . $ext;
        file_put_contents($tmpFile, $code);

        $cmd = escapeshellarg($binary) . ' ' . escapeshellarg($tmpFile);

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $proc = proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($proc)) {
            @unlink($tmpFile);
            return ['', 'Error', 'Failed to start process.'];
        }

        if ($stdin !== '') {
            fwrite($pipes[0], $stdin);
        }
        fclose($pipes[0]);

        // Non-blocking reads with 10-second timeout
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $start  = microtime(true);

        while (microtime(true) - $start < 10) {
            $status  = proc_get_status($proc);
            $stdout .= (string) fread($pipes[1], 8192);
            $stderr .= (string) fread($pipes[2], 8192);
            if (!$status['running']) break;
            usleep(50000);
        }

        // Drain remaining output
        stream_set_blocking($pipes[1], true);
        stream_set_blocking($pipes[2], true);
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($proc);
        @unlink($tmpFile);

        $stdout = trim($stdout);
        $stderr = trim($stderr);

        if ($exitCode !== 0 && $stderr !== '') {
            return [$stderr, 'Runtime Error', $stderr];
        }

        return [$stdout !== '' ? $stdout : $stderr, 'Accepted', ''];
    }

    /**
     * Execute SQL against an in-memory SQLite database seeded with sample tables.
     */
    private function executeSql(string $code): array
    {
        try {
            $db = new \PDO('sqlite::memory:');
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Seed sample tables so SELECT/JOIN challenges work
            $db->exec("
                CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, email TEXT, age INTEGER, is_active INTEGER);
                INSERT INTO users VALUES (1,'Alice','alice@example.com',25,1),
                                        (2,'Bob','bob@example.com',30,1),
                                        (3,'Carol','carol@example.com',28,0);

                CREATE TABLE products (id INTEGER PRIMARY KEY, name TEXT, price REAL, category TEXT);
                INSERT INTO products VALUES (1,'Laptop',999.99,'Electronics'),
                                           (2,'Book',19.99,'Education'),
                                           (3,'Phone',599.99,'Electronics');

                CREATE TABLE orders (id INTEGER PRIMARY KEY, customer_id INTEGER, product TEXT, total REAL, status TEXT);
                INSERT INTO orders VALUES (1,1,'Laptop',999.99,'shipped'),
                                         (2,2,'Book',19.99,'delivered'),
                                         (3,1,'Phone',599.99,'pending');

                CREATE TABLE customers (id INTEGER PRIMARY KEY, name TEXT, email TEXT, city TEXT);
                INSERT INTO customers VALUES (1,'Alice','alice@example.com','Manila'),
                                            (2,'Bob','bob@example.com','Cebu'),
                                            (3,'Carol','carol@example.com','Davao');
            ");

            $statements = array_filter(array_map('trim', explode(';', $code)), fn($s) => $s !== '');

            $lines = [];
            foreach ($statements as $sql) {
                $stmt = $db->query($sql);
                if ($stmt && $stmt->columnCount() > 0) {
                    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    if (!empty($rows)) {
                        $lines[] = implode(' | ', array_keys($rows[0]));
                        $lines[] = str_repeat('-', 40);
                        foreach ($rows as $row) {
                            $lines[] = implode(' | ', array_map('strval', array_values($row)));
                        }
                    }
                } elseif ($stmt) {
                    $count = $stmt->rowCount();
                    if ($count > 0) $lines[] = "Query OK, {$count} row(s) affected.";
                }
            }

            return [implode("\n", $lines), 'Accepted', ''];
        } catch (\Exception $e) {
            return ['', 'Runtime Error', $e->getMessage()];
        }
    }

    /**
     * Find the first available executable from a list of candidate names.
     */
    private function findExecutable(array $names): ?string
    {
        $isWindows = PHP_OS_FAMILY === 'Windows';
        foreach ($names as $name) {
            $cmd  = $isWindows ? "where {$name} 2>NUL" : "which {$name} 2>/dev/null";
            $path = shell_exec($cmd);
            if ($path) {
                $found = trim(explode("\n", $path)[0]);
                if ($found !== '' && file_exists($found)) {
                    return $found;
                }
            }
        }
        return null;
    }
}
