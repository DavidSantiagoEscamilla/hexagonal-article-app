<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

// ============================================================
// Domain/User/Entity/User.php
// ============================================================

final class User
{
    private string  $id;
    private string  $name;
    private string  $email;
    private string  $password;
    private string  $role;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt;

    private function __construct(
        string $id,
        string $name,
        string $email,
        string $password,
        string $role,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        ?\DateTimeImmutable $deletedAt = null
    ) {
        $this->id        = $id;
        $this->name      = $name;
        $this->email     = $email;
        $this->password  = $password;
        $this->role      = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public static function create(string $name, string $email, string $plainPassword, string $role = 'user'): self
    {
        self::ensureValidEmail($email);
        self::ensureValidRole($role);
        $now = new \DateTimeImmutable();
        $id  = self::generateUuid();
        return new self($id, trim($name), strtolower(trim($email)), password_hash($plainPassword, PASSWORD_BCRYPT), $role, $now, $now);
    }

    public static function fromPrimitives(
        string $id, string $name, string $email, string $password,
        string $role, string $createdAt, string $updatedAt, ?string $deletedAt = null
    ): self {
        return new self(
            $id, $name, $email, $password, $role,
            new \DateTimeImmutable($createdAt),
            new \DateTimeImmutable($updatedAt),
            $deletedAt ? new \DateTimeImmutable($deletedAt) : null
        );
    }

    public function update(string $name, string $email, string $role): void
    {
        self::ensureValidEmail($email);
        self::ensureValidRole($role);
        $this->name      = trim($name);
        $this->email     = strtolower(trim($email));
        $this->role      = $role;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function changePassword(string $plainPassword): void
    {
        $this->password  = password_hash($plainPassword, PASSWORD_BCRYPT);
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }

    public function delete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDeleted(): bool { return $this->deletedAt !== null; }

    public function id(): string       { return $this->id; }
    public function name(): string     { return $this->name; }
    public function email(): string    { return $this->email; }
    public function password(): string { return $this->password; }
    public function role(): string     { return $this->role; }
    public function createdAt(): \DateTimeImmutable  { return $this->createdAt; }
    public function updatedAt(): \DateTimeImmutable  { return $this->updatedAt; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }

    public function toPrimitives(): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => $this->password,
            'role'       => $this->role,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    private static function ensureValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Email inválido: $email");
        }
    }

    private static function ensureValidRole(string $role): void
    {
        if (!in_array($role, ['admin', 'user'], true)) {
            throw new \InvalidArgumentException("Rol inválido: $role");
        }
    }

    private static function generateUuid(): string
    {
        $data    = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
