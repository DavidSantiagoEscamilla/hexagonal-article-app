<?php

declare(strict_types=1);

namespace App\Application\Article\Delete;

use App\Domain\Article\Repository\ArticleRepositoryInterface;
use App\Domain\Article\ValueObject\ArticleId;
use App\Domain\Article\Exception\ArticleNotFoundException;

final class DeleteArticleHandler
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository
    ) {}

    public function handle(DeleteArticleCommand $command): void
    {
        $id      = ArticleId::fromString($command->id);
        $article = $this->repository->findById($id);

        if ($article === null) {
            throw new ArticleNotFoundException($command->id);
        }

        $article->delete();
        $this->repository->save($article);
    }
}
