<?php
use App\Infrastructure\Http\Middleware\AuthMiddleware;
$flash = AuthMiddleware::getFlash('success');
?>
<div style="width:100%;max-width:400px;">
    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-family:'Space Mono',monospace;font-size:1.6rem;color:var(--accent2)">&#9697; ArticleManager</h1>
        <p style="color:var(--muted);font-size:.86rem;margin-top:6px">Inicia sesión para continuar</p>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <div class="card" style="padding:32px;">
        <form method="POST" action="<?= BASE_URL ?>/auth/login">
            <div class="form-group" style="margin-bottom:16px">
                <label>Correo electrónico</label>
                <input type="email" name="email" placeholder="admin@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-group" style="margin-bottom:24px">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                Iniciar sesión
            </button>
        </form>
        <div style="text-align:center;margin-top:18px;">
            <a href="<?= BASE_URL ?>/auth/forgot-password" style="color:var(--muted);font-size:.82rem;text-decoration:none">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
    </div>
    <p style="text-align:center;color:var(--muted);font-size:.75rem;margin-top:16px">
        DDD + Arquitectura Hexagonal · PHP 8+
    </p>
</div>
