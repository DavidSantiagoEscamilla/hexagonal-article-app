<?php declare(strict_types=1);
namespace App\Domain\Article\ValueObject;

// ── ArticlePrice ─────────────────────────────────────────────
final class ArticlePrice
{
    private readonly float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('El precio no puede ser negativo.');
        }
        $this->value = round($value, 2);
    }

    public function value(): float { return $this->value; }

    public function isGreaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function formatted(): string
    {
        return '$' . number_format($this->value, 2, ',', '.');
    }
}
