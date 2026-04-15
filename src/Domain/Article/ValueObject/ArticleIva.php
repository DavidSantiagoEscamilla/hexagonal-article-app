<?php declare(strict_types=1);
namespace App\Domain\Article\ValueObject;

final class ArticleIva
{
    private readonly float $value;

    // Valores permitidos de IVA en Colombia
    private const VALID_RATES = [0.0, 5.0, 19.0];

    public function __construct(float $value)
    {
        if (!in_array($value, self::VALID_RATES, true) && !($value >= 0 && $value <= 100)) {
            throw new \InvalidArgumentException("IVA inválido: $value. Debe estar entre 0 y 100.");
        }
        $this->value = round($value, 2);
    }

    public function value(): float { return $this->value; }
    public function asDecimal(): float { return $this->value / 100; }
    public function label(): string { return $this->value . '%'; }
}
