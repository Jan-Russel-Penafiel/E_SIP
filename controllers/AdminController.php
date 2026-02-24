<?php
/**
 * Admin Controller
 * Full management panel for admin users — CRUD for users, modules, and challenges.
 */

namespace Controllers;

use Core\Auth;
use Core\Database;
use Models\User;
use Models\Module;
use Models\Challenge;
use Models\Progress;

class AdminController extends BaseController
{
    private User $userModel;
    private Module $moduleModel;
    private Challenge $challengeModel;
    private Progress $progressModel;

    public function __construct()
    {
        parent::__construct();
        $this->auth->requireAdmin();
        $this->userModel = new User();
        $this->moduleModel = new Module();
        $this->challengeModel = new Challenge();
        $this->progressModel = new Progress();
    }

    /** Admin Dashboard — overview stats */
    public function dashboard(): void
    {
        $users = $this->userModel->all();
        $modules = $this->moduleModel->all();
        $challenges = $this->challengeModel->all();
        $progressDb = Database::getInstance('progress');
        $allProgress = $progressDb->findAll();

        $stats = [
            'total_users'      => count($users),
            'total_modules'    => count($modules),
            'total_challenges' => count($challenges),
            'total_attempts'   => count($allProgress),
            'success_rate'     => count($allProgress) > 0
                ? round(count(array_filter($allProgress, fn($p) => $p['success'] ?? false)) / count($allProgress) * 100, 1)
                : 0,
            'active_today'     => count(array_filter($users, fn($u) => str_starts_with($u['last_active'] ?? '', date('Y-m-d')))),
        ];

        // Recent activity (last 10)
        usort($allProgress, fn($a, $b) => strtotime($b['created_at'] ?? '0') - strtotime($a['created_at'] ?? '0'));
        $recentActivity = array_slice($allProgress, 0, 10);

        // Enrich recent activity with usernames
        $userMap = [];
        foreach ($users as $u) { $userMap[$u['id']] = $u['username']; }
        foreach ($recentActivity as &$act) {
            $act['username'] = $userMap[$act['user_id'] ?? ''] ?? 'Unknown';
        }

        $this->render('admin/dashboard', [
            'stats'          => $stats,
            'users'          => $users,
            'modules'        => $modules,
            'challenges'     => $challenges,
            'recentActivity' => $recentActivity,
        ], 'Admin Dashboard');
    }

    // ──────────── USERS ────────────

    /** List all users */
    public function users(): void
    {
        $users = $this->userModel->all();
        $this->render('admin/users', ['users' => $users], 'Manage Users');
    }

    /** Edit user form */
    public function editUser(string $id): void
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->redirectWithMessage('/admin/users', 'User not found.', 'error');
            return;
        }
        $this->render('admin/edit-user', ['editUser' => $user], 'Edit User');
    }

    /** Update user (POST) */
    public function updateUser(string $id): void
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->redirectWithMessage('/admin/users', 'User not found.', 'error');
            return;
        }

        $data = [
            'username'   => $this->input('username', $user['username']),
            'email'      => $this->input('email', $user['email']),
            'role'       => $this->input('role', $user['role']),
            'level'      => (int)$this->input('level', $user['level']),
            'xp'         => (int)$this->input('xp', $user['xp']),
            'streak'     => (int)$this->input('streak', $user['streak']),
            'difficulty'  => $this->input('difficulty', $user['difficulty']),
        ];

        // Update password if provided
        $newPassword = $this->input('password', '');
        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $db = Database::getInstance('users');
        $db->update($id, $data);

        $this->redirectWithMessage('/admin/users', 'User updated successfully.');
    }

    /** Delete user */
    public function deleteUser(string $id): void
    {
        // Prevent deleting yourself
        if ($id === $this->auth->userId()) {
            $this->redirectWithMessage('/admin/users', 'Cannot delete your own account.', 'error');
            return;
        }

        $db = Database::getInstance('users');
        $db->delete($id);
        $this->redirectWithMessage('/admin/users', 'User deleted.');
    }

    // ──────────── MODULES ────────────

    /** List all modules */
    public function modules(): void
    {
        $modules = $this->moduleModel->all();
        $this->render('admin/modules', ['modules' => $modules], 'Manage Modules');
    }

    /** Edit module form */
    public function editModule(string $id): void
    {
        $module = $this->moduleModel->find($id);
        if (!$module) {
            $this->redirectWithMessage('/admin/modules', 'Module not found.', 'error');
            return;
        }
        $this->render('admin/edit-module', ['editModule' => $module], 'Edit Module');
    }

    /** Update module (POST) */
    public function updateModule(string $id): void
    {
        $module = $this->moduleModel->find($id);
        if (!$module) {
            $this->redirectWithMessage('/admin/modules', 'Module not found.', 'error');
            return;
        }

        $data = [
            'title'       => $this->input('title', $module['title']),
            'description' => $this->input('description', $module['description']),
            'language'    => $this->input('language', $module['language']),
            'difficulty'  => $this->input('difficulty', $module['difficulty']),
            'icon'        => $this->input('icon', $module['icon']),
            'order'       => (int)$this->input('order', $module['order']),
        ];

        // Handle lessons JSON
        $lessonsJson = $this->input('lessons_json', '');
        if (!empty($lessonsJson)) {
            $lessons = json_decode($lessonsJson, true);
            if ($lessons !== null) {
                $data['lessons'] = $lessons;
                $data['total_xp'] = array_sum(array_column($lessons, 'xp'));
            }
        }

        $db = Database::getInstance('modules');
        $db->update($id, $data);

        $this->redirectWithMessage('/admin/modules', 'Module updated successfully.');
    }

    /** Create new module (POST) */
    public function createModule(): void
    {
        $db = Database::getInstance('modules');
        $data = [
            'title'       => $this->input('title', 'New Module'),
            'description' => $this->input('description', ''),
            'language'    => $this->input('language', 'python'),
            'difficulty'  => $this->input('difficulty', 'beginner'),
            'icon'        => $this->input('icon', '📝'),
            'order'       => (int)$this->input('order', 99),
            'lessons'     => [],
            'total_xp'    => 0,
        ];
        $db->insert($data);
        $this->redirectWithMessage('/admin/modules', 'Module created.');
    }

    /** Delete module */
    public function deleteModule(string $id): void
    {
        $db = Database::getInstance('modules');
        $db->delete($id);
        $this->redirectWithMessage('/admin/modules', 'Module deleted.');
    }

    // ──────────── CHALLENGES ────────────

    /** List all challenges */
    public function challenges(): void
    {
        $challenges = $this->challengeModel->all();
        $this->render('admin/challenges', ['challenges' => $challenges], 'Manage Challenges');
    }

    /** Edit challenge form */
    public function editChallenge(string $id): void
    {
        $challenge = $this->challengeModel->find($id);
        if (!$challenge) {
            $this->redirectWithMessage('/admin/challenges', 'Challenge not found.', 'error');
            return;
        }
        $this->render('admin/edit-challenge', ['editChallenge' => $challenge], 'Edit Challenge');
    }

    /** Update challenge (POST) */
    public function updateChallenge(string $id): void
    {
        $challenge = $this->challengeModel->find($id);
        if (!$challenge) {
            $this->redirectWithMessage('/admin/challenges', 'Challenge not found.', 'error');
            return;
        }

        $data = [
            'title'           => $this->input('title', $challenge['title']),
            'description'     => $this->input('description', $challenge['description']),
            'language'        => $this->input('language', $challenge['language']),
            'difficulty'      => $this->input('difficulty', $challenge['difficulty']),
            'type'            => $this->input('type', $challenge['type']),
            'instructions'    => $this->input('instructions', $challenge['instructions']),
            'expected_output' => $this->input('expected_output', $challenge['expected_output'] ?? ''),
            'xp_reward'       => (int)$this->input('xp_reward', $challenge['xp_reward']),
            'time_limit'      => (int)$this->input('time_limit', $challenge['time_limit'] ?? 0),
        ];

        // Handle starter code & solution
        $starterCode = $this->input('starter_code', '');
        if ($starterCode !== '') $data['starter_code'] = $starterCode;

        $solution = $this->input('solution', '');
        if ($solution !== '') $data['solution'] = $solution;

        // Handle test cases JSON
        $testCasesJson = $this->input('test_cases_json', '');
        if (!empty($testCasesJson)) {
            $testCases = json_decode($testCasesJson, true);
            if ($testCases !== null) $data['test_cases'] = $testCases;
        }

        // Handle options (for multiple choice)
        $optionsJson = $this->input('options_json', '');
        if (!empty($optionsJson)) {
            $options = json_decode($optionsJson, true);
            if ($options !== null) $data['options'] = $options;
        }

        // Handle hints JSON
        $hintsJson = $this->input('hints_json', '');
        if (!empty($hintsJson)) {
            $hints = json_decode($hintsJson, true);
            if ($hints !== null) $data['hints'] = $hints;
        }

        $db = Database::getInstance('challenges');
        $db->update($id, $data);

        $this->redirectWithMessage('/admin/challenges', 'Challenge updated successfully.');
    }

    /** Create new challenge (POST) */
    public function createChallenge(): void
    {
        $db = Database::getInstance('challenges');
        $data = [
            'title'           => $this->input('title', 'New Challenge'),
            'description'     => $this->input('description', ''),
            'language'        => $this->input('language', 'python'),
            'difficulty'      => $this->input('difficulty', 'beginner'),
            'type'            => $this->input('type', 'code'),
            'instructions'    => $this->input('instructions', ''),
            'expected_output' => $this->input('expected_output', ''),
            'starter_code'    => $this->input('starter_code', ''),
            'solution'        => $this->input('solution', ''),
            'test_cases'      => [],
            'hints'           => [],
            'xp_reward'       => (int)$this->input('xp_reward', 50),
            'time_limit'      => (int)$this->input('time_limit', 300),
            'module_id'       => $this->input('module_id', ''),
            'options'         => [],
        ];
        $db->insert($data);
        $this->redirectWithMessage('/admin/challenges', 'Challenge created.');
    }

    /** Delete challenge */
    public function deleteChallenge(string $id): void
    {
        $db = Database::getInstance('challenges');
        $db->delete($id);
        $this->redirectWithMessage('/admin/challenges', 'Challenge deleted.');
    }
}
