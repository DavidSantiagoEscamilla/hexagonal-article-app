<?php

declare(strict_types=1);

// ============================================================
// config/config.php — Configuración central de la aplicación
// ============================================================

return [
    'db' => [
        'host'     => $_ENV['DB_HOST']     ?? 'localhost',
        'port'     => $_ENV['DB_PORT']     ?? '3306',
        'dbname'   => $_ENV['DB_NAME']     ?? 'hexagonal_app',
        'user'     => $_ENV['DB_USER']     ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset'  => 'utf8mb4',
    ],
    'app' => [
        'name'     => 'ArticleManager',
        'url'      => $_ENV['APP_URL']     ?? 'http://localhost/hexagonal-app/public',
        'env'      => $_ENV['APP_ENV']     ?? 'development',
        'debug'    => ($_ENV['APP_DEBUG']  ?? 'true') === 'true',
        'timezone' => 'America/Bogota',
    ],
    'session' => [
        'name'     => 'hexagonal_session',
        'lifetime' => 7200, // 2 horas
    ],
    'mail' => [
        'host'     => $_ENV['MAIL_HOST']     ?? 'smtp.gmail.com',
        'port'     => (int)($_ENV['MAIL_PORT']     ?? 587),
        'user'     => $_ENV['MAIL_USER']     ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'from'     => $_ENV['MAIL_FROM']     ?? 'noreply@example.com',
        'name'     => $_ENV['MAIL_NAME']     ?? 'ArticleManager',
    ],
];
