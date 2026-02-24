<?php
/**
 * Router
 * Simple request router mapping URI paths to controller actions.
 */

namespace Core;

class Router
{
    /** @var array Registered routes */
    private array $routes = [];

    /** @var string Base path prefix to strip */
    private string $basePath;

    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    /**
     * Register a GET route.
     * @param string   $path     URI pattern
     * @param callable $handler  Callback or [Controller, method]
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Register a POST route.
     * @param string   $path
     * @param callable $handler
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Dispatch the current request to the matched route handler.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Strip base path
        if ($this->basePath && str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
        }

        $uri = '/' . trim($uri, '/');
        if ($uri === '/') {
            $uri = '/';
        }

        // Try exact match first
        if (isset($this->routes[$method][$uri])) {
            $this->callHandler($this->routes[$method][$uri]);
            return;
        }

        // Try parameterized routes (e.g., /modules/{id})
        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callHandler($handler, $matches);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo $this->render404();
    }

    /**
     * Call the route handler with optional parameters.
     */
    private function callHandler(callable|array $handler, array $params = []): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            call_user_func_array([$controller, $method], $params);
        } else {
            call_user_func_array($handler, $params);
        }
    }

    /** Render a styled 404 page */
    private function render404(): string
    {
        return '<!DOCTYPE html><html><head><title>404 - E-SIP</title>
        <script src="https://cdn.tailwindcss.com"></script></head>
        <body class="bg-surface text-white flex items-center justify-center min-h-screen">
        <div class="text-center"><h1 class="text-6xl font-bold mb-4">404</h1>
        <p class="text-xl text-gray-400 mb-8">Page not found</p>
        <a href="/here/" class="px-6 py-3 bg-white text-black font-semibold rounded-lg hover:bg-gray-200 transition">
        Go Home</a></div></body></html>';
    }
}
