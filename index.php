<?php
/**
 * E-SIP — Entry Point
 * Educational System for Interactive Programming
 *
 * Bootstraps the application: registers the autoloader,
 * starts the session, defines all routes, and dispatches the request.
 */

// ─── Error Reporting (development) ───
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ─── Constants ───
define('BASE_PATH', __DIR__);

// ─── Autoloader ───
spl_autoload_register(function (string $class) {
    // Namespace → directory mapping
    $map = [
        'Core\\'        => BASE_PATH . '/core/',
        'Models\\'      => BASE_PATH . '/models/',
        'Controllers\\' => BASE_PATH . '/controllers/',
    ];

    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $dir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// ─── Configuration ───
$config = require BASE_PATH . '/config/app.php';

// ─── Session ───
$session = \Core\Session::getInstance();

// ─── Router ───
$router = new \Core\Router($config['base_url']);

// ───────────────────────────────────────────
//  Public Routes (no auth required)
// ───────────────────────────────────────────

// Home → redirect to dashboard (or login)
$router->get('/', function () use ($config) {
    $auth = \Core\Auth::getInstance();
    $dest = $auth->isLoggedIn() ? '/dashboard' : '/login';
    header('Location: ' . $config['base_url'] . $dest);
    exit;
});

// Auth
$router->get('/login',    [\Controllers\AuthController::class, 'showLogin']);
$router->post('/login',   [\Controllers\AuthController::class, 'login']);
$router->get('/register', [\Controllers\AuthController::class, 'showRegister']);
$router->post('/register',[\Controllers\AuthController::class, 'register']);
$router->get('/logout',   [\Controllers\AuthController::class, 'logout']);

// ───────────────────────────────────────────
//  Protected Routes (auth checked in controllers)
// ───────────────────────────────────────────

// Dashboard
$router->get('/dashboard', [\Controllers\DashboardController::class, 'index']);

// Modules
$router->get('/modules',      [\Controllers\ModuleController::class, 'index']);
$router->get('/modules/{id}', [\Controllers\ModuleController::class, 'show']);

// Challenges
$router->get('/challenges',             [\Controllers\ChallengeController::class, 'index']);
$router->get('/challenges/{id}',        [\Controllers\ChallengeController::class, 'play']);
$router->post('/challenges/{id}/submit',[\Controllers\ChallengeController::class, 'submit']);
$router->post('/challenges/{id}/run',   [\Controllers\ChallengeController::class, 'run']);
$router->post('/code/run',              [\Controllers\ChallengeController::class, 'run']); // generic (no challenge id needed)

// Leaderboard
$router->get('/leaderboard', [\Controllers\LeaderboardController::class, 'index']);

// Profile
$router->get('/profile',           [\Controllers\ProfileController::class, 'index']);
$router->post('/profile/settings', [\Controllers\ProfileController::class, 'updateSettings']);

// ───────────────────────────────────────────
//  Admin Routes (admin role checked in controller)
// ───────────────────────────────────────────
$router->get('/admin',                        [\Controllers\AdminController::class, 'dashboard']);

$router->get('/admin/users',                  [\Controllers\AdminController::class, 'users']);
$router->get('/admin/users/{id}',             [\Controllers\AdminController::class, 'editUser']);
$router->post('/admin/users/{id}',            [\Controllers\AdminController::class, 'updateUser']);
$router->post('/admin/users/{id}/delete',     [\Controllers\AdminController::class, 'deleteUser']);

$router->get('/admin/modules',                [\Controllers\AdminController::class, 'modules']);
$router->get('/admin/modules/{id}',           [\Controllers\AdminController::class, 'editModule']);
$router->post('/admin/modules/{id}',          [\Controllers\AdminController::class, 'updateModule']);
$router->post('/admin/modules/create',        [\Controllers\AdminController::class, 'createModule']);
$router->post('/admin/modules/{id}/delete',   [\Controllers\AdminController::class, 'deleteModule']);

$router->get('/admin/challenges',             [\Controllers\AdminController::class, 'challenges']);
$router->get('/admin/challenges/{id}',        [\Controllers\AdminController::class, 'editChallenge']);
$router->post('/admin/challenges/{id}',       [\Controllers\AdminController::class, 'updateChallenge']);
$router->post('/admin/challenges/create',     [\Controllers\AdminController::class, 'createChallenge']);
$router->post('/admin/challenges/{id}/delete',[\Controllers\AdminController::class, 'deleteChallenge']);

// ─── Dispatch ───
$router->dispatch();
