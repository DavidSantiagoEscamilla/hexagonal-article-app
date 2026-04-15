<?php declare(strict_types=1);

// ============================================================
// Application/Article/Read/FindArticleQuery.php
// ============================================================
namespace App\Application\Article\Read;

final class FindArticleQuery
{
    public function __construct(public readonly string $id) {}
}
