<?php

declare(strict_types=1);

namespace App\Domain\Article\Repository;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\ValueObject\ArticleId;

// ============================================================
// Domain/Article/Repository/ArticleRepositoryInterface.php
// Puerto de salida — contrato que la infraestructura debe cumplir
// ============================================================

interface ArticleRepositoryInterface
{
    /** Persiste un artículo nuevo o actualizado */
    public function save(Article $article): void;

    /** Busca un artículo por su ID (retorna null si no existe o está eliminado) */
    public function findById(ArticleId $id): ?Article;

    /** Retorna todos los artículos activos (sin soft-delete) */
    public function findAll(): array;

    /** Busca artículos por categoría */
    public function findByCategory(string $categoria): array;

    /** Busca artículos por marca */
    public function findByMarca(string $marca): array;

    /** Búsqueda general por término */
    public function search(string $term): array;

    /** Elimina lógicamente un artículo (soft delete) */
    public function delete(ArticleId $id): void;

    /** Retorna el total de artículos activos */
    public function count(): int;

    /** Paginación */
    public function findPaginated(int $page, int $perPage): array;
}
