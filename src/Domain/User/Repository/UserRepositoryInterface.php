<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findById(string $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findAll(): array;
    public function findPaginated(int $page, int $perPage): array;
    public function delete(string $id): void;
    public function count(): int;
    public function search(string $term): array;
}
