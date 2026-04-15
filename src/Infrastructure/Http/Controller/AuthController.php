<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Infrastructure\Http\Container;
use App\Infrastructure\Http\Middleware\AuthMiddleware;

// ============================================================
// Infrastructure/Http/Controller/AuthController.php
// ============================================================

final class AuthController
{
    public function __construct(private readonly Container $container) {}

    // GET /auth/login
    public function loginForm(array $params): void
    {
        AuthMiddleware::requireGuest();
        $errors = [];
        require VIEW_PATH . '/auth/login.php';
    }

    // POST /auth/login
    public function login(array $params): void
    {
        AuthMiddleware::requireGuest();

        $email    = strtolower(trim($_POST['email']   ?? ''));
        $password = trim($_POST['password'] ?? '');
        $errors   = [];

        if (empty($email) || empty($password)) {
            $errors[] = 'Correo y contraseña son obligatorios.';
            require VIEW_PATH . '/auth/login.php';
            return;
        }

        $userRepo = $this->container->get('user.repo');
        $user     = $userRepo->findByEmail($email);

        if ($user === null || !$user->verifyPassword($password)) {
            $errors[] = 'Credenciales incorrectas.';
            require VIEW_PATH . '/auth/login.php';
            return;
        }

        AuthMiddleware::login($user->id(), $user->name(), $user->email(), $user->role());
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    // GET /auth/logout
    public function logout(array $params): void
    {
        AuthMiddleware::logout();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    // GET /auth/forgot-password
    public function forgotForm(array $params): void
    {
        AuthMiddleware::requireGuest();
        $errors  = [];
        $success = null;
        require VIEW_PATH . '/auth/forgot.php';
    }

    // POST /auth/forgot-password
    public function forgot(array $params): void
    {
        AuthMiddleware::requireGuest();

        $email    = strtolower(trim($_POST['email'] ?? ''));
        $errors   = [];
        $success  = null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ingrese un correo válido.';
            require VIEW_PATH . '/auth/forgot.php';
            return;
        }

        $userRepo = $this->container->get('user.repo');
        $user     = $userRepo->findByEmail($email);

        // Siempre mostrar éxito por seguridad
        $success = 'Si el correo existe, recibirás un enlace para restablecer tu contraseña.';

        if ($user !== null) {
            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);
            $pdo       = $this->container->get('pdo');
            $pdo->prepare('INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (:e, :t, :ex)')
                ->execute([':e' => $email, ':t' => $token, ':ex' => $expiresAt]);

            $resetLink = BASE_URL . '/auth/reset-password?token=' . $token;
            // En producción: enviar email real. Aquí mostramos el link.
            $success .= ' <br><small style="color:#666">[DEV] Link: <a href="' . htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a></small>';
        }

        require VIEW_PATH . '/auth/forgot.php';
    }

    // GET /auth/reset-password
    public function resetForm(array $params): void
    {
        $token  = $_GET['token'] ?? '';
        $errors = [];
        $valid  = $this->validateToken($token);
        require VIEW_PATH . '/auth/reset.php';
    }

    // POST /auth/reset-password
    public function reset(array $params): void
    {
        $token    = $_POST['token']    ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm']  ?? '';
        $errors   = [];
        $valid    = $this->validateToken($token);

        if (!$valid) {
            $errors[] = 'Token inválido o expirado.';
            require VIEW_PATH . '/auth/reset.php';
            return;
        }

        if (strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
            require VIEW_PATH . '/auth/reset.php';
            return;
        }

        if ($password !== $confirm) {
            $errors[] = 'Las contraseñas no coinciden.';
            require VIEW_PATH . '/auth/reset.php';
            return;
        }

        $pdo      = $this->container->get('pdo');
        $stmt     = $pdo->prepare('SELECT email FROM password_reset_tokens WHERE token=:t AND used=0 AND expires_at > NOW()');
        $stmt->execute([':t' => $token]);
        $row      = $stmt->fetch();

        $userRepo = $this->container->get('user.repo');
        $user     = $userRepo->findByEmail($row['email']);
        $user->changePassword($password);
        $userRepo->save($user);

        $pdo->prepare('UPDATE password_reset_tokens SET used=1 WHERE token=:t')->execute([':t' => $token]);

        AuthMiddleware::flash('success', 'Contraseña actualizada. Inicia sesión.');
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    private function validateToken(string $token): bool
    {
        if (empty($token)) return false;
        $pdo  = $this->container->get('pdo');
        $stmt = $pdo->prepare('SELECT id FROM password_reset_tokens WHERE token=:t AND used=0 AND expires_at > NOW()');
        $stmt->execute([':t' => $token]);
        return $stmt->fetch() !== false;
    }
}
