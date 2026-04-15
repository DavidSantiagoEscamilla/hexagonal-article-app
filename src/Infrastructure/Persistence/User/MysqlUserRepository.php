<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;

final class MysqlUserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly \PDO $pdo) {}

    public function save(User $user): void
    {
        $data = $user->toPrimitives();

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE id = :id');
        $stmt->execute([':id' => $data['id']]);
        $exists = (int)$stmt->fetchColumn() > 0;

        if ($exists) {
            $sql = 'UPDATE users SET name=:name, email=:email, password=:password, role=:role, updated_at=:updated_at, deleted_at=:deleted_at WHERE id=:id';
        } else {
            $sql = 'INSERT INTO users (id,name,email,password,role,created_at,updated_at,deleted_at) VALUES (:id,:name,:email,:password,:role,:created_at,:updated_at,:deleted_at)';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'         => $data['id'],
            ':name'       => $data['name'],
            ':email'      => $data['email'],
            ':password'   => $data['password'],
            ':role'       => $data['role'],
            ':created_at' => $data['created_at'],
            ':updated_at' => $data['updated_at'],
            ':deleted_at' => $data['deleted_at'],
        ]);
    }

    public function findById(string $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id=:id AND deleted_at IS NULL');
        $stmt->execute([':id' => $id]);
        $row  = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email=:email AND deleted_at IS NULL');
        $stmt->execute([':email' => strtolower($email)]);
        $row  = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users WHERE deleted_at IS NULL ORDER BY name');
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findPaginated(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt   = $this->pdo->prepare('SELECT * FROM users WHERE deleted_at IS NULL ORDER BY name LIMIT :lim OFFSET :off');
        $stmt->bindValue(':lim', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,  \PDO::PARAM_INT);
        $stmt->execute();
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function delete(string $id): void
    {
        $this->pdo->prepare('UPDATE users SET deleted_at=NOW(), updated_at=NOW() WHERE id=:id')
                  ->execute([':id' => $id]);
    }

    public function count(): int
    {
        return (int)$this->pdo->query('SELECT COUNT(*) FROM users WHERE deleted_at IS NULL')->fetchColumn();
    }

    public function search(string $term): array
    {
        $like = "%$term%";
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE deleted_at IS NULL AND (name LIKE :t1 OR email LIKE :t2) ORDER BY name');
        $stmt->execute([':t1' => $like, ':t2' => $like]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    private function hydrate(array $row): User
    {
        return User::fromPrimitives(
            $row['id'], $row['name'], $row['email'], $row['password'],
            $row['role'], $row['created_at'], $row['updated_at'], $row['deleted_at']
        );
    }
}
