<?php declare(strict_types=1);
namespace App\Domain\Article\ValueObject;
use App\Domain\Shared\ValueObject\Uuid;

// ── ArticleId ────────────────────────────────────────────────
final class ArticleId
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        if (!preg_match($pattern, $value)) {
            throw new \InvalidArgumentException("ArticleId inválido: $value");
        }
        $this->value = $value;
    }

    public static function random(): self
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return new self(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
    }

    public static function fromString(string $value): self { return new self($value); }
    public function value(): string { return $this->value; }
    public function __toString(): string { return $this->value; }
}
