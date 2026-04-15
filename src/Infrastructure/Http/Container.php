<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\Persistence\MySQL\MysqlConnection;
use App\Infrastructure\Persistence\Article\MysqlArticleRepository;
use App\Infrastructure\Persistence\User\MysqlUserRepository;

use App\Application\Article\Create\CreateArticleHandler;
use App\Application\Article\Read\FindArticleHandler;
use App\Application\Article\Update\UpdateArticleHandler;
use App\Application\Article\Delete\DeleteArticleHandler;
use App\Application\Article\List\ListArticlesHandler;

// ============================================================
// Infrastructure/Http/Container.php
// Contenedor de dependencias (Service Locator simple)
// ============================================================

final class Container
{
    private array $bindings = [];
    private array $instances = [];

    public static function build(array $config): self
    {
        $container = new self();
        $pdo       = MysqlConnection::getInstance($config['db']);

        // Repositories
        $articleRepo = new MysqlArticleRepository($pdo);
        $userRepo    = new MysqlUserRepository($pdo);

        // Article handlers
        $container->bind('article.create',  fn() => new CreateArticleHandler($articleRepo));
        $container->bind('article.find',    fn() => new FindArticleHandler($articleRepo));
        $container->bind('article.update',  fn() => new UpdateArticleHandler($articleRepo));
        $container->bind('article.delete',  fn() => new DeleteArticleHandler($articleRepo));
        $container->bind('article.list',    fn() => new ListArticlesHandler($articleRepo));
        $container->bind('article.repo',    fn() => $articleRepo);

        // User handlers
        $container->bind('user.repo',       fn() => $userRepo);

        // Config
        $container->bind('config', fn() => $config);
        $container->bind('pdo',    fn() => $pdo);

        return $container;
    }

    public function bind(string $id, callable $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function get(string $id): mixed
    {
        if (!isset($this->instances[$id])) {
            if (!isset($this->bindings[$id])) {
                throw new \RuntimeException("No binding found for: $id");
            }
            $this->instances[$id] = ($this->bindings[$id])();
        }
        return $this->instances[$id];
    }
}
