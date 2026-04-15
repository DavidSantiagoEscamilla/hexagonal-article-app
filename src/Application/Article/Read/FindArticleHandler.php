<?php

declare(strict_types=1);

namespace App\Application\Article\Read;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Repository\ArticleRepositoryInterface;
use App\Domain\Article\ValueObject\ArticleId;
use App\Domain\Article\Exception\ArticleNotFoundException;

final class FindArticleHandler
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository
    ) {}

    public function handle(FindArticleQuery $query): Article
    {
        $id      = ArticleId::fromString($query->id);
        $article = $this->repository->findById($id);

        if ($article === null) {
            throw new ArticleNotFoundException($query->id);
        }

        return $article;
    }
}
