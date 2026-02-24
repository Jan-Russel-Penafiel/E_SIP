<?php
/**
 * Auth Singleton
 * Handles user authentication: login, logout, registration, and session guard.
 */

namespace Core;

class Auth
{
    /** @var Auth|null Singleton instance */
    private static ?Auth $instance = null;

    private Session $session;
    private Database $db;

    private function __construct()
    {
        $this->session = Session::getInstance();
        $this->db = Database::getInstance('users');
    }

    private function __clone() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register a new user.
     * @param string $username
     * @param string $email
     * @param string $password
     * @return array|string User record on success, error string on failure
     */
    public function register(string $username, string $email, string $password): array|string
    {
        // Validate unique email
        $existing = $this->db->findBy('email', $email);
        if (!empty($existing)) {
            return 'Email already registered.';
        }

        // Validate unique username
        $existingUser = $this->db->findBy('username', $username);
        if (!empty($existingUser)) {
            return 'Username already taken.';
        }

        $user = $this->db->insert([
            'username'       => $username,
            'email'          => $email,
            'password'       => password_hash($password, PASSWORD_BCRYPT),
            'role'           => 'learner',
            'level'          => 1,
            'xp'             => 0,
            'streak'         => 0,
            'badges'         => [],
            'avatar'         => 'default',
            'difficulty'     => 'beginner',
            'completed_challenges' => [],
            'completed_modules'    => [],
            'last_active'    => date('Y-m-d H:i:s'),
        ]);

        return $user;
    }

    /**
     * Authenticate a user by email and password.
     * @param string $email
     * @param string $password
     * @return array|string User record or error string
     */
    public function login(string $email, string $password): array|string
    {
        $users = $this->db->findBy('email', $email);
        if (empty($users)) {
            return 'Invalid credentials.';
        }

        $user = $users[0];
        if (!password_verify($password, $user['password'])) {
            return 'Invalid credentials.';
        }

        // Update last active
        $this->db->update($user['id'], ['last_active' => date('Y-m-d H:i:s')]);

        // Store user ID in session
        $this->session->set('user_id', $user['id']);
        $this->session->set('username', $user['username']);
        // Store role to allow quick admin checks without an extra DB lookup
        $this->session->set('role', $user['role'] ?? 'learner');

        return $user;
    }

    /** Log out the current user */
    public function logout(): void
    {
        $this->session->destroy();
        self::$instance = null;
    }

    /** Check if a user is logged in */
    public function isLoggedIn(): bool
    {
        return $this->session->has('user_id');
    }

    /** Get the currently logged-in user record */
    public function user(): ?array
    {
        $userId = $this->session->get('user_id');
        if (!$userId) {
            return null;
        }
        return $this->db->findById($userId);
    }

    /** Get current user ID */
    public function userId(): ?string
    {
        return $this->session->get('user_id');
    }

    /** Guard: redirect to login if not authenticated */
    public function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $config = require __DIR__ . '/../config/app.php';
            header('Location: ' . $config['base_url'] . '/login');
            exit;
        }
    }

    /** Check if the current user is an admin */
    public function isAdmin(): bool
    {
        // Prefer the session-stored role (set at login) to avoid extra DB calls
        $role = $this->session->get('role');
        if ($role !== null) {
            return $role === 'admin';
        }

        $user = $this->user();
        return $user && ($user['role'] ?? '') === 'admin';
    }

    /** Guard: redirect to dashboard if not admin */
    public function requireAdmin(): void
    {
        $this->requireAuth();
        if (!$this->isAdmin()) {
            $config = require __DIR__ . '/../config/app.php';
            header('Location: ' . $config['base_url'] . '/dashboard');
            exit;
        }
    }

    /** Reset singleton (for testing) */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}
