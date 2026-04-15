<?php

declare(strict_types=1);

namespace App\Application\Article\Update;

use App\Domain\Article\Repository\ArticleRepositoryInterface;
use App\Domain\Article\ValueObject\ArticleId;
use App\Domain\Article\Exception\ArticleNotFoundException;

final class UpdateArticleHandler
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository
    ) {}

    public function handle(UpdateArticleCommand $command): void
    {
        $id      = ArticleId::fromString($command->id);
        $article = $this->repository->findById($id);

        if ($article === null) {
            throw new ArticleNotFoundException($command->id);
        }

        $article->update(
            marca:        $command->marca,
            modelo:       $command->modelo,
            descripcion:  $command->descripcion,
            categoria:    $command->categoria,
            precioVenta:  $command->precioVenta,
            precioCompra: $command->precioCompra,
            iva:          $command->iva,
            proveedor:    $command->proveedor,
            tienda:       $command->tienda,
            cantidad:     $command->cantidad
        );

        $this->repository->save($article);
    }
}
