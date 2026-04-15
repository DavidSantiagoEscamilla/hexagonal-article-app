<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

// ============================================================
// Infrastructure/Http/Router.php
// ============================================================

final class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = strtok($uri, '?');

        // 1. Exact match
        if (isset($this->routes[$method][$uri])) {
            $this->call($this->routes[$method][$uri], []);
            return;
        }

        // 2. Pattern match (e.g. /articles/{id})
        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';
            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->call($handler, $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        echo $this->render404();
    }

    private function call(callable|array $handler, array $params): void
    {
        if (is_callable($handler)) {
            $handler($params);
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            $controller->$method($params);
        }
    }

    private function render404(): string
    {
        return '<!DOCTYPE html><html><body style="font-family:sans-serif;text-align:center;padding:60px">
            <h1 style="color:#e74c3c">404 — Página no encontrada</h1>
            <a href="/hexagonal-app/public">Volver al inicio</a></body></html>';
    }
}
