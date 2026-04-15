<?php ?>
<div class="page-header">
    <div>
        <h2 class="page-title">Nuevo Usuario</h2>
        <p class="page-subtitle">Registrar un nuevo usuario en el sistema</p>
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
    <div class="card-title">Datos del usuario</div>
    <form method="POST" action="<?= BASE_URL ?>/users/store">
        <div class="form-group" style="margin-bottom:14px">
            <label>Nombre completo *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                   placeholder="Nombre completo" required autofocus>
        </div>
        <div class="form-group" style="margin-bottom:14px">
            <label>Correo electrónico *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   placeholder="correo@ejemplo.com" required>
        </div>
        <div class="form-group" style="margin-bottom:14px">
            <label>Contraseña * (mínimo 8 caracteres)</label>
            <input type="password" name="password" placeholder="••••••••" required minlength="8">
        </div>
        <div class="form-group" style="margin-bottom:24px">
            <label>Rol *</label>
            <select name="role">
                <option value="user"  <?= ($old['role'] ?? 'user') === 'user'  ? 'selected' : '' ?>>Usuario</option>
                <option value="admin" <?= ($old['role'] ?? 'user') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-success">&#10003; Crear Usuario</button>
            <a href="<?= BASE_URL ?>/users" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
