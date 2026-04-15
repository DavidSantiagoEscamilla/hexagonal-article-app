<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Article;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Repository\ArticleRepositoryInterface;
use App\Domain\Article\ValueObject\ArticleId;

// ============================================================
// Infrastructure/Persistence/Article/MysqlArticleRepository.php
// Adaptador de salida — implementa el puerto del dominio
// ============================================================

final class MysqlArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(private readonly \PDO $pdo) {}

    public function save(Article $article): void
    {
        $data = $article->toPrimitives();

        $exists = $this->pdo
            ->prepare('SELECT COUNT(*) FROM articles WHERE id = :id')
            ->execute([':id' => $data['id']]);

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM articles WHERE id = :id');
        $stmt->execute([':id' => $data['id']]);
        $exists = (int)$stmt->fetchColumn() > 0;

        if ($exists) {
            $sql = '
                UPDATE articles SET
                    marca          = :marca,
                    modelo         = :modelo,
                    descripcion    = :descripcion,
                    categoria      = :categoria,
                    precio_venta   = :precio_venta,
                    precio_compra  = :precio_compra,
                    iva            = :iva,
                    proveedor      = :proveedor,
                    tienda         = :tienda,
                    cantidad       = :cantidad,
                    updated_at     = :updated_at,
                    deleted_at     = :deleted_at
                WHERE id = :id
            ';
        } else {
            $sql = '
                INSERT INTO articles
                    (id, marca, modelo, descripcion, categoria, precio_venta, precio_compra, iva, proveedor, tienda, cantidad, created_at, updated_at, deleted_at)
                VALUES
                    (:id, :marca, :modelo, :descripcion, :categoria, :precio_venta, :precio_compra, :iva, :proveedor, :tienda, :cantidad, :created_at, :updated_at, :deleted_at)
            ';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'            => $data['id'],
            ':marca'         => $data['marca'],
            ':modelo'        => $data['modelo'],
            ':descripcion'   => $data['descripcion'],
            ':categoria'     => $data['categoria'],
            ':precio_venta'  => $data['precio_venta'],
            ':precio_compra' => $data['precio_compra'],
            ':iva'           => $data['iva'],
            ':proveedor'     => $data['proveedor'],
            ':tienda'        => $data['tienda'],
            ':cantidad'      => $data['cantidad'],
            ':created_at'    => $data['created_at'],
            ':updated_at'    => $data['updated_at'],
            ':deleted_at'    => $data['deleted_at'],
        ]);
    }

    public function findById(ArticleId $id): ?Article
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM articles WHERE id = :id AND deleted_at IS NULL'
        );
        $stmt->execute([':id' => $id->value()]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM articles WHERE deleted_at IS NULL ORDER BY created_at DESC');
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findByCategory(string $categoria): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM articles WHERE categoria = :categoria AND deleted_at IS NULL ORDER BY marca'
        );
        $stmt->execute([':categoria' => $categoria]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findByMarca(string $marca): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM articles WHERE marca LIKE :marca AND deleted_at IS NULL ORDER BY modelo'
        );
        $stmt->execute([':marca' => "%$marca%"]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function search(string $term): array
    {
        $like = "%$term%";
        $stmt = $this->pdo->prepare('
            SELECT * FROM articles
            WHERE deleted_at IS NULL AND (
                marca LIKE :t1 OR modelo LIKE :t2 OR descripcion LIKE :t3
                OR categoria LIKE :t4 OR proveedor LIKE :t5 OR tienda LIKE :t6
            )
            ORDER BY marca
        ');
        $stmt->execute([':t1'=>$like,':t2'=>$like,':t3'=>$like,':t4'=>$like,':t5'=>$like,':t6'=>$like]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function delete(ArticleId $id): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE articles SET deleted_at = NOW(), updated_at = NOW() WHERE id = :id'
        );
        $stmt->execute([':id' => $id->value()]);
    }

    public function count(): int
    {
        return (int)$this->pdo->query('SELECT COUNT(*) FROM articles WHERE deleted_at IS NULL')->fetchColumn();
    }

    public function findPaginated(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt   = $this->pdo->prepare(
            'SELECT * FROM articles WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit',  $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  \PDO::PARAM_INT);
        $stmt->execute();
        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    private function hydrate(array $row): Article
    {
        return Article::fromPrimitives(
            id:           $row['id'],
            marca:        $row['marca'],
            modelo:       $row['modelo'],
            descripcion:  $row['descripcion'],
            categoria:    $row['categoria'],
            precioVenta:  (float)$row['precio_venta'],
            precioCompra: (float)$row['precio_compra'],
            iva:          (float)$row['iva'],
            proveedor:    $row['proveedor'],
            tienda:       $row['tienda'],
            cantidad:     (int)$row['cantidad'],
            createdAt:    $row['created_at'],
            updatedAt:    $row['updated_at'],
            deletedAt:    $row['deleted_at']
        );
    }
}
