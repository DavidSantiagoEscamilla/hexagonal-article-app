<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Usuarios</h2>
        <p class="page-subtitle">Gestión de usuarios del sistema</p>
    </div>
    <a href="<?= BASE_URL ?>/users/create" class="btn btn-primary">&#43; Nuevo Usuario</a>
</div>

<?php if (!empty($flash['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash['success']) ?></div>
<?php endif; ?>
<?php if (!empty($flash['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>

<div class="card" style="padding:16px 24px;margin-bottom:20px">
    <form method="GET" action="<?= BASE_URL ?>/users" style="display:flex;gap:10px">
        <div class="form-group" style="flex:1">
            <label>Buscar usuario</label>
            <input type="text" name="search" placeholder="Nombre o correo..."
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div style="display:flex;gap:8px;align-items:flex-end">
            <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            <a href="<?= BASE_URL ?>/users" class="btn btn-secondary btn-sm">Limpiar</a>
        </div>
    </form>
</div>

<div class="card" style="padding:0;overflow:hidden">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                    <th style="text-align:center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No se encontraron usuarios</td></tr>
            <?php else: ?>
                <?php foreach ($users as $u):
                    $up = is_array($u) ? $u : $u->toPrimitives(); ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0">
                                <?= strtoupper(mb_substr($up['name'], 0, 1)) ?>
                            </div>
                            <strong><?= htmlspecialchars($up['name']) ?></strong>
                        </div>
                    </td>
                    <td style="color:var(--muted)"><?= htmlspecialchars($up['email']) ?></td>
                    <td>
                        <span class="badge <?= $up['role'] === 'admin' ? 'badge-warning' : 'badge-info' ?>">
                            <?= $up['role'] ?>
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:.82rem">
                        <?= date('d/m/Y', strtotime($up['created_at'])) ?>
                    </td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="<?= BASE_URL ?>/users/<?= $up['id'] ?>/edit" class="btn btn-primary btn-xs">Editar</a>
                        <?php if ($up['id'] !== $user['id']): ?>
                        <form method="POST" action="<?= BASE_URL ?>/users/<?= $up['id'] ?>/delete" style="display:inline"
                              onsubmit="return confirm('¿Eliminar el usuario <?= addslashes(htmlspecialchars($up['name'])) ?>?')">
                            <button type="submit" class="btn btn-danger btn-xs">Eliminar</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pages > 1 && empty($search)): ?>
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
        <span style="color:var(--muted);font-size:.82rem">Página <?= $page ?> de <?= $pages ?> · <?= $total ?> usuarios</span>
        <div class="pagination">
            <?php if ($page > 1): ?><a href="?page=<?= $page-1 ?>" class="page-btn">← Anterior</a><?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?><a href="?page=<?= $page+1 ?>" class="page-btn">Siguiente →</a><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
