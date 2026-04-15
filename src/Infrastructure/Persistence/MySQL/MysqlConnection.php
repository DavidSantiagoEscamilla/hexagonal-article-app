<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\MySQL;

// ============================================================
// Infrastructure/Persistence/MySQL/MysqlConnection.php
// Adaptador de conexión PDO (Singleton)
// ============================================================

final class MysqlConnection
{
    private static ?\PDO $instance = null;

    private function __construct() {}

    public static function getInstance(array $config): \PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            self::$instance = new \PDO(
                $dsn,
                $config['user'],
                $config['password'],
                [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }

        return self::$instance;
    }
}
