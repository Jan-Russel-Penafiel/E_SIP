<?php
/**
 * Base Controller
 * Provides shared rendering and utility methods for all controllers.
 */

namespace Controllers;

use Core\Auth;
use Core\Session;

class BaseController
{
    protected Auth $auth;
    protected Session $session;
    protected array $config;

    public function __construct()
    {
        $this->auth    = Auth::getInstance();
        $this->session = Session::getInstance();
        $this->config  = require __DIR__ . '/../config/app.php';
    }

    /**
     * Determine the application's base URL.
     * Uses `config['base_url']` unless it is empty or set to 'auto',
     * in which case it derives the path from the current script location.
     * Returns a path (e.g. '/e_sip' or '' for document root) without trailing slash.
     */
    protected function getBaseUrl(): string
    {
        $cfg = $this->config['base_url'] ?? '';
        if ($cfg === '' || $cfg === null || $cfg === 'auto') {
            $script = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? '');
            $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
            if ($dir === '' || $dir === '.') {
                return '';
            }
            return $dir;
        }

        // Normalize provided base_url: remove trailing slash if present
        $normalized = rtrim($cfg, '/');

        // If a full URL was provided (http...), return as-is
        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://') || str_starts_with($normalized, '//')) {
            return $normalized;
        }

        // Determine project root on disk. Prefer defined BASE_PATH, otherwise infer.
        $projectRoot = defined('BASE_PATH') ? BASE_PATH : realpath(__DIR__ . '/..');

        // Path where assets should exist if base_url is correct
        $cfgPath = '/' . trim($normalized, '/');
        $assetCandidate1 = $projectRoot . $cfgPath . '/assets/css/app.css';
        $assetCandidate2 = $projectRoot . '/assets/css/app.css';

        // If assets exist under configured base, keep it. Otherwise fallback to root if assets exist there.
        if (file_exists($assetCandidate1)) {
            return $normalized;
        }
        if (file_exists($assetCandidate2)) {
            return '';
        }

        // If neither exists, return normalized config anyway (best-effort)
        return $normalized;
    }

    /**
     * Render a view within the main layout.
     * @param string $view   View path relative to views/ (e.g., 'dashboard/index')
     * @param array  $data   Variables to pass to the view
     * @param string $title  Page title
     */
    protected function render(string $view, array $data = [], string $title = ''): void
    {
        // Extract data to make variables available in view
        extract($data);

        $pageTitle   = $title ?: 'E-SIP';
        $currentUser = $this->auth->user();
        $isLoggedIn  = $this->auth->isLoggedIn();
        $baseUrl     = $this->getBaseUrl();
        $flash       = $this->session->getFlash('message');
        $flashType   = $this->session->getFlash('type', 'info');

        // Capture view content
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();

        // Render within layout
        require __DIR__ . '/../views/layouts/main.php';
    }

    /**
     * Render a view without layout (for auth pages).
     */
    protected function renderClean(string $view, array $data = [], string $title = ''): void
    {
        extract($data);
        $pageTitle = $title ?: 'E-SIP';
        $baseUrl   = $this->getBaseUrl();
        $flash     = $this->session->getFlash('message');
        $flashType = $this->session->getFlash('type', 'info');

        require __DIR__ . '/../views/' . $view . '.php';
    }

    /**
     * Send a JSON response (for AJAX/API endpoints).
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a path.
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . $this->config['base_url'] . $path);
        exit;
    }

    /**
     * Set a flash message and redirect.
     */
    protected function redirectWithMessage(string $path, string $message, string $type = 'success'): void
    {
        $this->session->flash('message', $message);
        $this->session->flash('type', $type);
        $this->redirect($path);
    }

    /**
     * Get POST data safely.
     */
    protected function input(string $key, mixed $default = ''): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET parameter safely.
     */
    protected function query(string $key, mixed $default = ''): mixed
    {
        return $_GET[$key] ?? $default;
    }
}
