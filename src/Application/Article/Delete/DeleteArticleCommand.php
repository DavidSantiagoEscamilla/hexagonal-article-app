<?php declare(strict_types=1);
namespace App\Application\Article\Delete;

final class DeleteArticleCommand
{
    public function __construct(public readonly string $id) {}
}
