<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Editar Artículo</h2>
        <p class="page-subtitle"><?= htmlspecialchars($article->marca() . ' — ' . $article->modelo()) ?></p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="<?= BASE_URL ?>/articles/<?= $article->id() ?>" class="btn btn-secondary">Ver detalle</a>
        <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary">← Volver</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Modificar datos del artículo</div>
    <form method="POST" action="<?= BASE_URL ?>/articles/<?= $article->id() ?>/update">

        <div class="form-grid" style="margin-bottom:16px">
            <div class="form-group">
                <label>Marca *</label>
                <input type="text" name="marca" value="<?= htmlspecialchars($old['marca'] ?? $article->marca()) ?>" required>
            </div>
            <div class="form-group">
                <label>Modelo *</label>
                <input type="text" name="modelo" value="<?= htmlspecialchars($old['modelo'] ?? $article->modelo()) ?>" required>
            </div>
        </div>

        <div class="form-group full" style="margin-bottom:16px">
            <label>Descripción *</label>
            <textarea name="descripcion" required><?= htmlspecialchars($old['descripcion'] ?? $article->descripcion()) ?></textarea>
        </div>

        <div class="form-grid cols-3" style="margin-bottom:16px">
            <div class="form-group">
                <label>Categoría *</label>
                <input type="text" name="categoria" value="<?= htmlspecialchars($old['categoria'] ?? $article->categoria()) ?>"
                       required list="categorias-list">
                <datalist id="categorias-list">
                    <option value="Electrónica"><option value="Calzado"><option value="Ropa">
                    <option value="Computadores"><option value="Hogar"><option value="Deportes">
                    <option value="Alimentos"><option value="Juguetes"><option value="Libros">
                </datalist>
            </div>
            <div class="form-group">
                <label>Proveedor *</label>
                <input type="text" name="proveedor" value="<?= htmlspecialchars($old['proveedor'] ?? $article->proveedor()) ?>" required>
            </div>
            <div class="form-group">
                <label>Tienda *</label>
                <input type="text" name="tienda" value="<?= htmlspecialchars($old['tienda'] ?? $article->tienda()) ?>" required>
            </div>
        </div>

        <div class="form-grid cols-3" style="margin-bottom:24px">
            <div class="form-group">
                <label>Precio de Compra (COP) *</label>
                <input type="number" name="precio_compra" step="0.01" min="0"
                       value="<?= htmlspecialchars($old['precio_compra'] ?? $article->precioCompra()) ?>" required>
            </div>
            <div class="form-group">
                <label>Precio de Venta (COP) *</label>
                <input type="number" name="precio_venta" step="0.01" min="0"
                       value="<?= htmlspecialchars($old['precio_venta'] ?? $article->precioVenta()) ?>" required>
            </div>
            <div class="form-group">
                <label>IVA (%) *</label>
                <select name="iva">
                    <?php $ivaActual = $old['iva'] ?? $article->iva(); ?>
                    <option value="0"  <?= $ivaActual == 0  ? 'selected' : '' ?>>0% — Exento</option>
                    <option value="5"  <?= $ivaActual == 5  ? 'selected' : '' ?>>5% — Reducido</option>
                    <option value="19" <?= $ivaActual == 19 ? 'selected' : '' ?>>19% — General</option>
                </select>
            </div>
        </div>

        <div class="form-grid" style="margin-bottom:24px">
            <div class="form-group">
                <label>Cantidad en stock *</label>
                <input type="number" name="cantidad" min="0"
                       value="<?= htmlspecialchars($old['cantidad'] ?? $article->cantidad()) ?>" required>
            </div>
            <div>
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:14px;font-size:.85rem;color:var(--muted)">
                    <strong style="display:block;color:var(--text);margin-bottom:6px">Margen de ganancia actual</strong>
                    <?php
                        $margen = $article->margenGanancia();
                        $color  = $margen >= 20 ? 'var(--success)' : ($margen >= 5 ? 'var(--warning)' : 'var(--danger)');
                    ?>
                    <span style="font-size:1.4rem;font-family:'Space Mono',monospace;color:<?= $color ?>">
                        <?= number_format($margen, 1) ?>%
                    </span>
                    <span style="display:block;margin-top:4px">
                        Precio con IVA: $<?= number_format($article->precioConIva(), 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">&#10003; Actualizar Artículo</button>
            <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<!-- Metadata -->
<div class="card" style="padding:16px 24px;font-size:.8rem;color:var(--muted)">
    <strong style="color:var(--text)">Registro</strong> ·
    Creado: <?= $article->createdAt()->format('d/m/Y H:i') ?> ·
    Última actualización: <?= $article->updatedAt()->format('d/m/Y H:i') ?> ·
    ID: <code style="font-family:'Space Mono',monospace;font-size:.75rem"><?= $article->id() ?></code>
</div>
