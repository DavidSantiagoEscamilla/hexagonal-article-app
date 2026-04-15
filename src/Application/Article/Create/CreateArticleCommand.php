<?php declare(strict_types=1);
namespace App\Application\Article\Create;

// ============================================================
// Application/Article/Create/CreateArticleCommand.php
// ============================================================

final class CreateArticleCommand
{
    public function __construct(
        public readonly string $marca,
        public readonly string $modelo,
        public readonly string $descripcion,
        public readonly string $categoria,
        public readonly float  $precioVenta,
        public readonly float  $precioCompra,
        public readonly float  $iva,
        public readonly string $proveedor,
        public readonly string $tienda,
        public readonly int    $cantidad
    ) {}
}
