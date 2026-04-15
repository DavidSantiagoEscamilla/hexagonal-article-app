<?php ?>
<div style="width:100%;max-width:400px;">
    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-family:'Space Mono',monospace;font-size:1.6rem;color:var(--accent2)">&#9697; ArticleManager</h1>
        <p style="color:var(--muted);font-size:.86rem;margin-top:6px">Recuperar contraseña</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <div class="card" style="padding:32px;">
        <p style="color:var(--muted);font-size:.85rem;margin-bottom:20px;">
            Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
        </p>
        <form method="POST" action="<?= BASE_URL ?>/auth/forgot-password">
            <div class="form-group" style="margin-bottom:20px">
                <label>Correo electrónico</label>
                <input type="email" name="email" placeholder="tu@correo.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                Enviar enlace de recuperación
            </button>
        </form>
        <div style="text-align:center;margin-top:18px;">
            <a href="<?= BASE_URL ?>/auth/login" style="color:var(--muted);font-size:.82rem;text-decoration:none">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>
