<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArticleManager — Sistema de Gestión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #0f1117;
            --surface:   #1a1d27;
            --surface2:  #222535;
            --border:    #2e3147;
            --accent:    #6c63ff;
            --accent2:   #a78bfa;
            --success:   #10b981;
            --danger:    #ef4444;
            --warning:   #f59e0b;
            --text:      #e2e8f0;
            --muted:     #64748b;
            --radius:    10px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 15px; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ───────────────────── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            padding: 0;
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand h1 {
            font-family: 'Space Mono', monospace;
            font-size: 1.1rem;
            color: var(--accent2);
            letter-spacing: -0.5px;
        }
        .sidebar-brand span {
            font-size: 0.72rem;
            color: var(--muted);
            display: block;
            margin-top: 2px;
        }
        .nav-section {
            padding: 16px 12px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--muted);
            text-transform: uppercase;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--text);
            background: var(--surface2);
            border-left-color: var(--accent);
        }
        .nav-link .icon { font-size: 1rem; width: 18px; text-align: center; }
        .sidebar-footer {
            margin-top: auto;
            padding: 16px 20px;
            border-top: 1px solid var(--border);
        }
        .user-info { font-size: 0.8rem; color: var(--muted); margin-bottom: 10px; }
        .user-info strong { display: block; color: var(--text); font-size: 0.875rem; }
        .btn-logout {
            display: block;
            text-align: center;
            background: var(--surface2);
            color: var(--danger);
            border: 1px solid var(--border);
            padding: 7px 14px;
            border-radius: var(--radius);
            font-size: 0.8rem;
            text-decoration: none;
            transition: .15s;
        }
        .btn-logout:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        /* ── Main ─────────────────────── */
        .main {
            margin-left: 240px;
            flex: 1;
            padding: 32px;
            min-height: 100vh;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
        }
        .page-subtitle {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 2px;
        }

        /* ── Cards ───────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        /* ── Buttons ─────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: var(--radius);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: .15s;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #5a52d5; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { opacity: .85; }
        .btn-danger  { background: var(--danger);  color: #fff; }
        .btn-danger:hover  { opacity: .85; }
        .btn-secondary { background: var(--surface2); color: var(--text); border-color: var(--border); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent2); }
        .btn-sm { padding: 5px 12px; font-size: 0.78rem; }
        .btn-xs { padding: 3px 9px; font-size: 0.73rem; }

        /* ── Tables ──────────────────── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.86rem; }
        thead th {
            background: var(--surface2);
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }
        tbody tr:hover { background: var(--surface2); }
        tbody tr:last-child td { border-bottom: none; }

        /* ── Forms ───────────────────── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 0.8rem; font-weight: 600; color: var(--muted); }
        input, select, textarea {
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-family: inherit;
            transition: border-color .15s;
            width: 100%;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
        }
        textarea { resize: vertical; min-height: 80px; }

        /* ── Alerts ──────────────────── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: 0.86rem;
            margin-bottom: 18px;
            border-left: 4px solid;
        }
        .alert-success { background: rgba(16,185,129,.1); border-color: var(--success); color: #6ee7b7; }
        .alert-danger  { background: rgba(239,68,68,.1);  border-color: var(--danger);  color: #fca5a5; }
        .alert-warning { background: rgba(245,158,11,.1); border-color: var(--warning); color: #fcd34d; }
        .alert ul { margin: 6px 0 0 18px; }

        /* ── Badges ──────────────────── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-success { background: rgba(16,185,129,.15); color: var(--success); }
        .badge-danger  { background: rgba(239,68,68,.15);  color: var(--danger); }
        .badge-warning { background: rgba(245,158,11,.15); color: var(--warning); }
        .badge-info    { background: rgba(108,99,255,.15); color: var(--accent2); }

        /* ── Pagination ──────────────── */
        .pagination { display: flex; gap: 6px; margin-top: 18px; align-items: center; }
        .page-btn {
            padding: 6px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            transition: .15s;
        }
        .page-btn:hover, .page-btn.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* ── Search bar ──────────────── */
        .search-bar { display: flex; gap: 8px; margin-bottom: 18px; }
        .search-bar input { flex: 1; }

        /* ── Stat cards ──────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
        }
        .stat-value { font-size: 2rem; font-weight: 700; font-family: 'Space Mono', monospace; color: var(--text); }
        .stat-label { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }
        .stat-icon  { font-size: 1.6rem; margin-bottom: 8px; }

        /* ── Detail view ─────────────── */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .detail-item label { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; color: var(--muted); text-transform: uppercase; }
        .detail-item p     { font-size: 0.95rem; color: var(--text); margin-top: 3px; }

        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main    { margin-left: 200px; padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<?php
use App\Infrastructure\Http\Middleware\AuthMiddleware;
$currentUser = AuthMiddleware::currentUser();
$currentUri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

function isActive(string $path): string {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    return str_contains($uri, $path) ? 'active' : '';
}
?>
<?php if (AuthMiddleware::isLoggedIn()): ?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>&#9697; ArticleManager</h1>
        <span>DDD + Arquitectura Hexagonal</span>
    </div>

    <span class="nav-section">Principal</span>
    <a href="<?= BASE_URL ?>/dashboard" class="nav-link <?= isActive('/dashboard') || $currentUri === '/' ? 'active' : '' ?>">
        <span class="icon">&#9783;</span> Dashboard
    </a>

    <span class="nav-section">Inventario</span>
    <a href="<?= BASE_URL ?>/articles" class="nav-link <?= isActive('/articles') ?>">
        <span class="icon">&#9645;</span> Artículos
    </a>
    <a href="<?= BASE_URL ?>/articles/create" class="nav-link">
        <span class="icon">&#43;</span> Nuevo Artículo
    </a>

    <?php if ($currentUser['role'] === 'admin'): ?>
    <span class="nav-section">Administración</span>
    <a href="<?= BASE_URL ?>/users" class="nav-link <?= isActive('/users') ?>">
        <span class="icon">&#9786;</span> Usuarios
    </a>
    <a href="<?= BASE_URL ?>/users/create" class="nav-link">
        <span class="icon">&#43;</span> Nuevo Usuario
    </a>
    <?php endif; ?>

    <div class="sidebar-footer">
        <div class="user-info">
            <strong><?= htmlspecialchars($currentUser['name'] ?? '') ?></strong>
            <?= htmlspecialchars($currentUser['email'] ?? '') ?>
            <span style="display:inline-block;margin-top:3px" class="badge badge-info"><?= $currentUser['role'] ?></span>
        </div>
        <a href="<?= BASE_URL ?>/auth/logout" class="btn-logout">&#x2715; Cerrar sesión</a>
    </div>
</aside>
<?php endif; ?>
<main class="main" <?= !AuthMiddleware::isLoggedIn() ? 'style="margin-left:0;display:flex;align-items:center;justify-content:center;background:var(--bg)"' : '' ?>>
