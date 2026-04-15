<?php

declare(strict_types=1);

namespace App\Domain\Article\Entity;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Article\ValueObject\ArticleId;
use App\Domain\Article\ValueObject\ArticlePrice;
use App\Domain\Article\ValueObject\ArticleIva;
use App\Domain\Article\ValueObject\ArticleStock;

// ============================================================
// Domain/Article/Entity/Article.php
// Entidad raíz del agregado Artículo (Rich Domain Model)
// ============================================================

final class Article
{
    private ArticleId    $id;
    private string       $marca;
    private string       $modelo;
    private string       $descripcion;
    private string       $categoria;
    private ArticlePrice $precioVenta;
    private ArticlePrice $precioCompra;
    private ArticleIva   $iva;
    private string       $proveedor;
    private string       $tienda;
    private ArticleStock $cantidad;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt;

    private function __construct(
        ArticleId    $id,
        string       $marca,
        string       $modelo,
        string       $descripcion,
        string       $categoria,
        ArticlePrice $precioVenta,
        ArticlePrice $precioCompra,
        ArticleIva   $iva,
        string       $proveedor,
        string       $tienda,
        ArticleStock $cantidad,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        ?\DateTimeImmutable $deletedAt = null
    ) {
        $this->id           = $id;
        $this->marca        = $marca;
        $this->modelo       = $modelo;
        $this->descripcion  = $descripcion;
        $this->categoria    = $categoria;
        $this->precioVenta  = $precioVenta;
        $this->precioCompra = $precioCompra;
        $this->iva          = $iva;
        $this->proveedor    = $proveedor;
        $this->tienda       = $tienda;
        $this->cantidad     = $cantidad;
        $this->createdAt    = $createdAt;
        $this->updatedAt    = $updatedAt;
        $this->deletedAt    = $deletedAt;
    }

    // ── Factory Method ────────────────────────────────────────
    public static function create(
        string $marca,
        string $modelo,
        string $descripcion,
        string $categoria,
        float  $precioVenta,
        float  $precioCompra,
        float  $iva,
        string $proveedor,
        string $tienda,
        int    $cantidad
    ): self {
        $now = new \DateTimeImmutable();
        return new self(
            ArticleId::random(),
            trim($marca),
            trim($modelo),
            trim($descripcion),
            trim($categoria),
            new ArticlePrice($precioVenta),
            new ArticlePrice($precioCompra),
            new ArticleIva($iva),
            trim($proveedor),
            trim($tienda),
            new ArticleStock($cantidad),
            $now,
            $now
        );
    }

    // ── Reconstitución desde persistencia ────────────────────
    public static function fromPrimitives(
        string  $id,
        string  $marca,
        string  $modelo,
        string  $descripcion,
        string  $categoria,
        float   $precioVenta,
        float   $precioCompra,
        float   $iva,
        string  $proveedor,
        string  $tienda,
        int     $cantidad,
        string  $createdAt,
        string  $updatedAt,
        ?string $deletedAt = null
    ): self {
        return new self(
            ArticleId::fromString($id),
            $marca,
            $modelo,
            $descripcion,
            $categoria,
            new ArticlePrice($precioVenta),
            new ArticlePrice($precioCompra),
            new ArticleIva($iva),
            $proveedor,
            $tienda,
            new ArticleStock($cantidad),
            new \DateTimeImmutable($createdAt),
            new \DateTimeImmutable($updatedAt),
            $deletedAt ? new \DateTimeImmutable($deletedAt) : null
        );
    }

    // ── Comportamiento de dominio ─────────────────────────────
    public function update(
        string $marca,
        string $modelo,
        string $descripcion,
        string $categoria,
        float  $precioVenta,
        float  $precioCompra,
        float  $iva,
        string $proveedor,
        string $tienda,
        int    $cantidad
    ): void {
        $this->marca        = trim($marca);
        $this->modelo       = trim($modelo);
        $this->descripcion  = trim($descripcion);
        $this->categoria    = trim($categoria);
        $this->precioVenta  = new ArticlePrice($precioVenta);
        $this->precioCompra = new ArticlePrice($precioCompra);
        $this->iva          = new ArticleIva($iva);
        $this->proveedor    = trim($proveedor);
        $this->tienda       = trim($tienda);
        $this->cantidad     = new ArticleStock($cantidad);
        $this->updatedAt    = new \DateTimeImmutable();
    }

    public function delete(): void
    {
        if ($this->deletedAt !== null) {
            throw new \LogicException('El artículo ya ha sido eliminado.');
        }
        $this->deletedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /** Calcula el precio de venta con IVA incluido */
    public function precioConIva(): float
    {
        return $this->precioVenta->value() * (1 + $this->iva->value() / 100);
    }

    /** Calcula el margen de ganancia en porcentaje */
    public function margenGanancia(): float
    {
        if ($this->precioCompra->value() == 0) {
            return 0.0;
        }
        return (($this->precioVenta->value() - $this->precioCompra->value()) / $this->precioCompra->value()) * 100;
    }

    // ── Getters ───────────────────────────────────────────────
    public function id(): ArticleId           { return $this->id; }
    public function marca(): string           { return $this->marca; }
    public function modelo(): string          { return $this->modelo; }
    public function descripcion(): string     { return $this->descripcion; }
    public function categoria(): string       { return $this->categoria; }
    public function precioVenta(): float      { return $this->precioVenta->value(); }
    public function precioCompra(): float     { return $this->precioCompra->value(); }
    public function iva(): float              { return $this->iva->value(); }
    public function proveedor(): string       { return $this->proveedor; }
    public function tienda(): string          { return $this->tienda; }
    public function cantidad(): int           { return $this->cantidad->value(); }
    public function createdAt(): \DateTimeImmutable  { return $this->createdAt; }
    public function updatedAt(): \DateTimeImmutable  { return $this->updatedAt; }
    public function deletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }

    public function toPrimitives(): array
    {
        return [
            'id'            => $this->id->value(),
            'marca'         => $this->marca,
            'modelo'        => $this->modelo,
            'descripcion'   => $this->descripcion,
            'categoria'     => $this->categoria,
            'precio_venta'  => $this->precioVenta->value(),
            'precio_compra' => $this->precioCompra->value(),
            'iva'           => $this->iva->value(),
            'proveedor'     => $this->proveedor,
            'tienda'        => $this->tienda,
            'cantidad'      => $this->cantidad->value(),
            'created_at'    => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at'    => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
