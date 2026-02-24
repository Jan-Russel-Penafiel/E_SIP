<?php
/**
 * Session Singleton
 * Manages PHP session lifecycle for E-SIP.
 */

namespace Core;

class Session
{
    /** @var Session|null Singleton instance */
    private static ?Session $instance = null;

    /** Private constructor starts session */
    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../config/app.php';
            session_name($config['session']['name']);
            session_start();
        }
    }

    private function __clone() {}

    /** Get Singleton instance */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Set a session value */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /** Get a session value */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /** Check if session key exists */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /** Remove a session key */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Set a flash message (shown once) */
    public function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /** Get and clear a flash message */
    public function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /** Destroy the session completely */
    public function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
        self::$instance = null;
    }
}
