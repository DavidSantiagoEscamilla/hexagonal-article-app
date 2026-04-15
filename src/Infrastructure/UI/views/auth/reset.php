<?php ?>
<div style="width:100%;max-width:420px;">
    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-family:'Space Mono',monospace;font-size:1.6rem;color:var(--accent2)">&#9697; ArticleManager</h1>
        <p style="color:var(--muted);font-size:.86rem;margin-top:6px">Nueva contraseña</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <div class="card" style="padding:32px;">
        <?php if (!$valid): ?>
            <div class="alert alert-danger">El enlace es inválido o ha expirado.</div>
            <a href="<?= BASE_URL ?>/auth/forgot-password" class="btn btn-secondary" style="width:100%;justify-content:center">
                Solicitar nuevo enlace
            </a>
        <?php else: ?>
            <form method="POST" action="<?= BASE_URL ?>/auth/reset-password">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? $_POST['token'] ?? '') ?>">
                <div class="form-group" style="margin-bottom:16px">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password" placeholder="Mínimo 8 caracteres" required minlength="8">
                </div>
                <div class="form-group" style="margin-bottom:24px">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="confirm" placeholder="Repite la contraseña" required>
                </div>
                <button type="submit" class="btn btn-success" style="width:100%;justify-content:center">
                    Guardar nueva contraseña
                </button>
            </form>
        <?php endif; ?>
        <div style="text-align:center;margin-top:18px;">
            <a href="<?= BASE_URL ?>/auth/login" style="color:var(--muted);font-size:.82rem;text-decoration:none">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>
