<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Artículos</h2>
        <p class="page-subtitle">Gestión de inventario de artículos</p>
    </div>
    <a href="<?= BASE_URL ?>/articles/create" class="btn btn-primary">&#43; Nuevo Artículo</a>
</div>

<?php if (!empty($flash['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>

<!-- Filtros -->
<div class="card" style="padding:16px 24px;">
    <form method="GET" action="<?= BASE_URL ?>/articles" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
        <div class="form-group" style="flex:1;min-width:200px">
            <label>Buscar</label>
            <input type="text" name="search" placeholder="Marca, modelo, descripción..."
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="form-group" style="min-width:160px">
            <label>Categoría</label>
            <select name="categoria">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria === $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary btn-sm">Limpiar</a>
        </div>
    </form>
</div>

<!-- Tabla -->
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Marca / Modelo</th>
                    <th>Categoría</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                    <th>IVA</th>
                    <th>Stock</th>
                    <th>Proveedor</th>
                    <th>Tienda</th>
                    <th style="text-align:center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($articles)): ?>
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:var(--muted)">
                        No se encontraron artículos
                        <?= !empty($search) ? 'para "' . htmlspecialchars($search) . '"' : '' ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($articles as $a): ?>
                <tr>
                    <td>
                        <strong style="color:var(--text)"><?= htmlspecialchars($a['marca']) ?></strong>
                        <br><small style="color:var(--muted)"><?= htmlspecialchars($a['modelo']) ?></small>
                    </td>
                    <td><span class="badge badge-info"><?= htmlspecialchars($a['categoria']) ?></span></td>
                    <td style="font-family:'Space Mono',monospace;font-size:.82rem;color:var(--muted)">
                        $<?= number_format($a['precio_compra'], 0, ',', '.') ?>
                    </td>
                    <td style="font-family:'Space Mono',monospace;font-size:.82rem;color:var(--accent2)">
                        $<?= number_format($a['precio_venta'], 0, ',', '.') ?>
                    </td>
                    <td style="color:var(--muted)"><?= $a['iva'] ?>%</td>
                    <td>
                        <?php if ($a['cantidad'] == 0): ?>
                            <span class="badge badge-danger">Sin stock</span>
                        <?php elseif ($a['cantidad'] <= 5): ?>
                            <span class="badge badge-warning"><?= $a['cantidad'] ?> uds</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $a['cantidad'] ?> uds</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:var(--muted);font-size:.82rem"><?= htmlspecialchars($a['proveedor']) ?></td>
                    <td style="color:var(--muted);font-size:.82rem"><?= htmlspecialchars($a['tienda']) ?></td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="<?= BASE_URL ?>/articles/<?= $a['id'] ?>" class="btn btn-secondary btn-xs">Ver</a>
                        <a href="<?= BASE_URL ?>/articles/<?= $a['id'] ?>/edit" class="btn btn-primary btn-xs">Editar</a>
                        <form method="POST" action="<?= BASE_URL ?>/articles/<?= $a['id'] ?>/delete"
                              style="display:inline"
                              onsubmit="return confirm('¿Eliminar el artículo <?= addslashes(htmlspecialchars($a['marca'] . ' ' . $a['modelo'])) ?>?')">
                            <button type="submit" class="btn btn-danger btn-xs">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pages > 1 && empty($search) && empty($categoria)): ?>
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
        <span style="color:var(--muted);font-size:.82rem">
            Página <?= $page ?> de <?= $pages ?> · <?= $total ?> artículos en total
        </span>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="page-btn">← Anterior</a>
            <?php endif; ?>
            <?php for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++): ?>
                <a href="?page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="page-btn">Siguiente →</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
