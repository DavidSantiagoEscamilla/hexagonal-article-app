<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Editar Usuario</h2>
        <p class="page-subtitle"><?= htmlspecialchars($target->name()) ?></p>
    </div>
    <a href="<?= BASE_URL ?>/users" class="btn btn-secondary">← Volver</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<div class="card" style="max-width:540px">
    <div class="card-title">Modificar datos</div>
    <form method="POST" action="<?= BASE_URL ?>/users/<?= $target->id() ?>/update">
        <div class="form-group" style="margin-bottom:14px">
            <label>Nombre completo *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? $target->name()) ?>"
                   placeholder="Nombre completo" required autofocus>
        </div>
        <div class="form-group" style="margin-bottom:14px">
            <label>Correo electrónico *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? $target->email()) ?>"
                   placeholder="correo@ejemplo.com" required>
        </div>
        <div class="form-group" style="margin-bottom:24px">
            <label>Rol *</label>
            <select name="role">
                <?php $roleActual = $old['role'] ?? $target->role(); ?>
                <option value="user"  <?= $roleActual === 'user'  ? 'selected' : '' ?>>Usuario</option>
                <option value="admin" <?= $roleActual === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">&#10003; Actualizar Usuario</button>
            <a href="<?= BASE_URL ?>/users" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<div class="card" style="max-width:540px;margin-top:16px;font-size:.8rem;color:var(--muted)">
    <strong style="color:var(--text)">Metadata</strong> ·
    Registrado: <?= $target->createdAt()->format('d/m/Y H:i') ?> ·
    ID: <code style="font-family:'Space Mono',monospace;font-size:.72rem"><?= $target->id() ?></code>
</div>
