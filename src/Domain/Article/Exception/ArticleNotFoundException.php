<?php declare(strict_types=1);
namespace App\Domain\Article\Exception;

// ── ArticleNotFoundException ──────────────────────────────────
final class ArticleNotFoundException extends \RuntimeException
{
    public function __construct(string $id)
    {
        parent::__construct("Artículo no encontrado con ID: $id", 404);
    }
}
