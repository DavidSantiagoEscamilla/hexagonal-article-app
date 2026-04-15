<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

// ============================================================
// Infrastructure/Http/Middleware/AuthMiddleware.php
// ============================================================

final class AuthMiddleware
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('hexagonal_session');
            session_start();
        }
    }

    public static function requireAuth(): void
    {
        self::start();
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }
    }

    public static function requireGuest(): void
    {
        self::start();
        if (!empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo '<h1>403 — Acceso denegado</h1>';
            exit;
        }
    }

    public static function login(string $userId, string $userName, string $userEmail, string $userRole): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['user_id']    = $userId;
        $_SESSION['user_name']  = $userName;
        $_SESSION['user_email'] = $userEmail;
        $_SESSION['user_role']  = $userRole;
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    public static function currentUser(): array
    {
        self::start();
        return [
            'id'    => $_SESSION['user_id']    ?? null,
            'name'  => $_SESSION['user_name']  ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'role'  => $_SESSION['user_role']  ?? null,
        ];
    }

    public static function isLoggedIn(): bool
    {
        self::start();
        return !empty($_SESSION['user_id']);
    }

    public static function flash(string $key, string $message): void
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        self::start();
        $msg = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
}
