<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title"><?= htmlspecialchars($article->marca() . ' ' . $article->modelo()) ?></h2>
        <p class="page-subtitle">Detalle completo del artículo</p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="<?= BASE_URL ?>/articles/<?= $article->id() ?>/edit" class="btn btn-primary">Editar</a>
        <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary">← Volver</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    <!-- Main info -->
    <div>
        <div class="card">
            <div class="card-title">Información general</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Marca</label>
                    <p><?= htmlspecialchars($article->marca()) ?></p>
                </div>
                <div class="detail-item">
                    <label>Modelo</label>
                    <p><?= htmlspecialchars($article->modelo()) ?></p>
                </div>
                <div class="detail-item">
                    <label>Categoría</label>
                    <p><span class="badge badge-info"><?= htmlspecialchars($article->categoria()) ?></span></p>
                </div>
                <div class="detail-item">
                    <label>Stock disponible</label>
                    <p>
                        <?php if ($article->cantidad() == 0): ?>
                            <span class="badge badge-danger">Sin stock</span>
                        <?php elseif ($article->cantidad() <= 5): ?>
                            <span class="badge badge-warning"><?= $article->cantidad() ?> unidades (stock bajo)</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $article->cantidad() ?> unidades</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="detail-item" style="grid-column:1/-1">
                    <label>Descripción</label>
                    <p style="line-height:1.6"><?= nl2br(htmlspecialchars($article->descripcion())) ?></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Logística</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Proveedor</label>
                    <p><?= htmlspecialchars($article->proveedor()) ?></p>
                </div>
                <div class="detail-item">
                    <label>Tienda</label>
                    <p><?= htmlspecialchars($article->tienda()) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing sidebar -->
    <div>
        <div class="card">
            <div class="card-title">Precios</div>
            <div style="display:flex;flex-direction:column;gap:16px">
                <div>
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase">Precio de Compra</div>
                    <div style="font-family:'Space Mono',monospace;font-size:1.3rem;color:var(--text);margin-top:4px">
                        $<?= number_format($article->precioCompra(), 0, ',', '.') ?>
                    </div>
                </div>
                <div>
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase">Precio de Venta</div>
                    <div style="font-family:'Space Mono',monospace;font-size:1.3rem;color:var(--accent2);margin-top:4px">
                        $<?= number_format($article->precioVenta(), 0, ',', '.') ?>
                    </div>
                </div>
                <div>
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase">IVA</div>
                    <div style="font-size:1rem;color:var(--text);margin-top:4px"><?= $article->iva() ?>%</div>
                </div>
                <div style="border-top:1px solid var(--border);padding-top:14px">
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase">Precio con IVA incluido</div>
                    <div style="font-family:'Space Mono',monospace;font-size:1.5rem;color:var(--success);margin-top:4px">
                        $<?= number_format($article->precioConIva(), 0, ',', '.') ?>
                    </div>
                </div>
                <div style="border-top:1px solid var(--border);padding-top:14px">
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.5px;color:var(--muted);text-transform:uppercase">Margen de ganancia</div>
                    <?php
                        $margen = $article->margenGanancia();
                        $color  = $margen >= 20 ? 'var(--success)' : ($margen >= 5 ? 'var(--warning)' : 'var(--danger)');
                    ?>
                    <div style="font-family:'Space Mono',monospace;font-size:1.5rem;color:<?= $color ?>;margin-top:4px">
                        <?= number_format($margen, 1) ?>%
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Metadata</div>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:.8rem;color:var(--muted)">
                <div>
                    <strong style="display:block;color:var(--text)">ID</strong>
                    <code style="font-family:'Space Mono',monospace;font-size:.72rem;word-break:break-all"><?= $article->id() ?></code>
                </div>
                <div>
                    <strong style="display:block;color:var(--text)">Creado</strong>
                    <?= $article->createdAt()->format('d/m/Y H:i:s') ?>
                </div>
                <div>
                    <strong style="display:block;color:var(--text)">Última actualización</strong>
                    <?= $article->updatedAt()->format('d/m/Y H:i:s') ?>
                </div>
            </div>
        </div>

        <div class="card" style="border-color:var(--danger)">
            <div class="card-title" style="color:var(--danger)">Zona de peligro</div>
            <form method="POST" action="<?= BASE_URL ?>/articles/<?= $article->id() ?>/delete"
                  onsubmit="return confirm('¿Estás seguro de eliminar este artículo? Esta acción no se puede deshacer.')">
                <p style="font-size:.82rem;color:var(--muted);margin-bottom:14px">
                    Eliminar este artículo de forma permanente (soft delete).
                </p>
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                    &#x2715; Eliminar artículo
                </button>
            </form>
        </div>
    </div>
</div>
