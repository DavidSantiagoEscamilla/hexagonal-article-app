<?php

declare(strict_types=1);

namespace App\Application\Article\Create;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Repository\ArticleRepositoryInterface;

// ============================================================
// Application/Article/Create/CreateArticleHandler.php
// Caso de uso: Crear artículo
// ============================================================

final class CreateArticleHandler
{
    public function __construct(
        private readonly ArticleRepositoryInterface $repository
    ) {}

    public function handle(CreateArticleCommand $command): string
    {
        $article = Article::create(
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

        return $article->id()->value();
    }
}
