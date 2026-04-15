<?php

declare(strict_types=1);

namespace App\Application\Article\List;

use App\Domain\Article\Repository\ArticleRepositoryInterface;

final class ListArticlesHandler
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository
    ) {}

    public function handle(ListArticlesQuery $query): array
    {
        if ($query->search !== '') {
            $articles = $this->repository->search($query->search);
        } elseif ($query->categoria !== '') {
            $articles = $this->repository->findByCategory($query->categoria);
        } else {
            $articles = $this->repository->findPaginated($query->page, $query->perPage);
        }

        return array_map(fn($a) => $a->toPrimitives(), $articles);
    }
}
