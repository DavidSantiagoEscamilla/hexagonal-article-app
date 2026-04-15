<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

// ============================================================
// Domain/Shared/ValueObject/Uuid.php
// Value Object reutilizable para identificadores únicos (UUID v4)
// ============================================================

final class Uuid
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    public static function random(): self
    {
        return new self(self::generate());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function ensureIsValidUuid(string $value): void
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        if (!preg_match($pattern, $value)) {
            throw new \InvalidArgumentException(
                sprintf('<%s> does not allow the value <%s>.', static::class, $value)
            );
        }
    }

    private static function generate(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
