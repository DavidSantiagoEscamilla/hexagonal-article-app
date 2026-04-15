<?php declare(strict_types=1);
namespace App\Domain\Article\ValueObject;

final class ArticleStock
{
    private readonly int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('La cantidad en stock no puede ser negativa.');
        }
        $this->value = $value;
    }

    public function value(): int { return $this->value; }

    public function isOutOfStock(): bool { return $this->value === 0; }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->value > 0 && $this->value <= $threshold;
    }
}
