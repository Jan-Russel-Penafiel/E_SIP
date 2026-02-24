<?php
/**
 * Auth Controller
 * Handles login, register, and logout actions.
 */

namespace Controllers;

use Core\Auth;

class AuthController extends BaseController
{
    /** Show login form */
    public function showLogin(): void
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }
        $this->renderClean('auth/login', [], 'Login - E-SIP');
    }

    /** Process login form submission */
    public function login(): void
    {
        $email    = trim($this->input('email'));
        $password = $this->input('password');

        if (empty($email) || empty($password)) {
            $this->redirectWithMessage('/login', 'All fields are required.', 'error');
            return;
        }

        $result = $this->auth->login($email, $password);

        if (is_string($result)) {
            $this->redirectWithMessage('/login', $result, 'error');
            return;
        }

        // Honor post-login redirect (e.g. from Ctrl+Alt+E shortcut)
        $redirect = trim((string)($this->input('redirect') ?: $this->query('redirect')));
        if ($redirect && str_starts_with($redirect, '/') && !str_starts_with($redirect, '//')) {
            if (str_starts_with($redirect, '/admin') && $this->auth->isAdmin()) {
                $this->redirect($redirect);
                return;
            } elseif (!str_starts_with($redirect, '/admin')) {
                $this->redirect($redirect);
                return;
            }
        }

        // If no redirect provided, send admins to the admin dashboard by default
        if ($this->auth->isAdmin()) {
            $this->redirect('/admin/dashboard');
            return;
        }

        $this->redirect('/dashboard');
    }

    /** Show registration form */
    public function showRegister(): void
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect('/dashboard');
            return;
        }
        $this->renderClean('auth/register', [], 'Register - E-SIP');
    }

    /** Process registration form submission */
    public function register(): void
    {
        $username = trim($this->input('username'));
        $email    = trim($this->input('email'));
        $password = $this->input('password');
        $confirm  = $this->input('confirm_password');

        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            $this->redirectWithMessage('/register', 'All fields are required.', 'error');
            return;
        }

        if (strlen($username) < 3) {
            $this->redirectWithMessage('/register', 'Username must be at least 3 characters.', 'error');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithMessage('/register', 'Invalid email address.', 'error');
            return;
        }

        if (strlen($password) < 6) {
            $this->redirectWithMessage('/register', 'Password must be at least 6 characters.', 'error');
            return;
        }

        if ($password !== $confirm) {
            $this->redirectWithMessage('/register', 'Passwords do not match.', 'error');
            return;
        }

        $result = $this->auth->register($username, $email, $password);

        if (is_string($result)) {
            $this->redirectWithMessage('/register', $result, 'error');
            return;
        }

        // Auto-login after registration
        $this->auth->login($email, $password);
        $this->redirectWithMessage('/dashboard', 'Welcome to E-SIP! Your coding journey begins now.', 'success');
    }

    /** Logout and redirect */
    public function logout(): void
    {
        $this->auth->logout();
        // Restart session for flash
        $this->session = \Core\Session::getInstance();
        $this->redirectWithMessage('/login', 'You have been logged out.', 'success');
    }
}
