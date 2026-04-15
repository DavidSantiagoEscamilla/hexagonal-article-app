<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Dashboard</h2>
        <p class="page-subtitle">Bienvenido, <?= htmlspecialchars($user['name']) ?> · <?= date('l, d \d\e F Y') ?></p>
    </div>
    <a href="<?= BASE_URL ?>/articles/create" class="btn btn-primary">&#43; Nuevo Artículo</a>
</div>

<?php if (!empty($flash['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
<?php endif; ?>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">&#9645;</div>
        <div class="stat-value"><?= $totalArticles ?></div>
        <div class="stat-label">Total artículos activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--success)">&#9650;</div>
        <div class="stat-value" style="color:var(--success)"><?= $enStock ?></div>
        <div class="stat-label">En stock (>5 unidades)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--warning)">&#9651;</div>
        <div class="stat-value" style="color:var(--warning)"><?= $stockBajo ?></div>
        <div class="stat-label">Stock bajo (1–5 unidades)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--danger)">&#9660;</div>
        <div class="stat-value" style="color:var(--danger)"><?= $sinStock ?></div>
        <div class="stat-label">Sin stock</div>
    </div>
    <?php if ($user['role'] === 'admin'): ?>
    <div class="stat-card">
        <div class="stat-icon">&#9786;</div>
        <div class="stat-value"><?= $totalUsers ?></div>
        <div class="stat-label">Usuarios registrados</div>
    </div>
    <?php endif; ?>
</div>

<!-- Latest Articles -->
<div class="card">
    <div class="card-title">Últimos artículos registrados</div>
    <?php if (empty($latestArticles)): ?>
        <p style="color:var(--muted);text-align:center;padding:20px 0">No hay artículos registrados aún.</p>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Marca / Modelo</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Tienda</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($latestArticles as $a): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($a['marca']) ?></strong>
                        <br><small style="color:var(--muted)"><?= htmlspecialchars($a['modelo']) ?></small>
                    </td>
                    <td><span class="badge badge-info"><?= htmlspecialchars($a['categoria']) ?></span></td>
                    <td style="font-family:'Space Mono',monospace;color:var(--accent2)">
                        $<?= number_format($a['precio_venta'], 0, ',', '.') ?>
                    </td>
                    <td>
                        <?php if ($a['cantidad'] == 0): ?>
                            <span class="badge badge-danger">Sin stock</span>
                        <?php elseif ($a['cantidad'] <= 5): ?>
                            <span class="badge badge-warning"><?= $a['cantidad'] ?> uds</span>
                        <?php else: ?>
                            <span class="badge badge-success"><?= $a['cantidad'] ?> uds</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:var(--muted)"><?= htmlspecialchars($a['tienda']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/articles/<?= $a['id'] ?>" class="btn btn-secondary btn-xs">Ver</a>
                        <a href="<?= BASE_URL ?>/articles/<?= $a['id'] ?>/edit" class="btn btn-primary btn-xs">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px;">
        <a href="<?= BASE_URL ?>/articles" class="btn btn-secondary btn-sm">Ver todos los artículos →</a>
    </div>
    <?php endif; ?>
</div>

<!-- Architecture info -->
<div class="card" style="border-color:var(--accent);background:rgba(108,99,255,.06)">
    <div class="card-title" style="color:var(--accent2)">&#9729; Arquitectura del proyecto</div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;font-size:.83rem;color:var(--muted)">
        <div>
            <strong style="color:var(--text);display:block;margin-bottom:4px">Domain Layer</strong>
            Entidades, Value Objects, Repositorios (interfaces), Excepciones de dominio
        </div>
        <div>
            <strong style="color:var(--text);display:block;margin-bottom:4px">Application Layer</strong>
            Casos de uso (Handlers), Commands, Queries — orquestan el dominio sin acoplarse a infraestructura
        </div>
        <div>
            <strong style="color:var(--text);display:block;margin-bottom:4px">Infrastructure Layer</strong>
            Controladores HTTP, Repositorios MySQL (adaptadores), Router, Contenedor de dependencias, Vistas
        </div>
    </div>
</div>
