<?php

declare(strict_types=1);

// ============================================================
// public/index.php — Front Controller
// Punto de entrada único de la aplicación
// ============================================================

// ── Autoloader PSR-4 manual (sin Composer) ────────────────────
spl_autoload_register(function (string $class): void {
    $prefix   = 'App\\';
    $base_dir = __DIR__ . '/../src/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative_class = substr($class, strlen($prefix));
    $file           = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// ── Constantes globales ───────────────────────────────────────
define('ROOT_PATH',  dirname(__DIR__));
define('VIEW_PATH',  ROOT_PATH . '/src/Infrastructure/UI/views');
define('BASE_URL',   rtrim(
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . dirname($_SERVER['SCRIPT_NAME']),
    '/'
));

// ── Configuración ─────────────────────────────────────────────
$config = require ROOT_PATH . '/config/config.php';
date_default_timezone_set($config['app']['timezone']);

// ── Manejo de errores ─────────────────────────────────────────
if ($config['app']['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

// ── Contenedor de dependencias ────────────────────────────────
use App\Infrastructure\Http\Container;
use App\Infrastructure\Http\Router;
use App\Infrastructure\Http\Middleware\AuthMiddleware;
use App\Infrastructure\Http\Controller\AuthController;
use App\Infrastructure\Http\Controller\ArticleController;
use App\Infrastructure\Http\Controller\UserController;
use App\Infrastructure\Http\Controller\DashboardController;

$container = Container::build($config);

// ── Controladores ─────────────────────────────────────────────
$auth      = new AuthController($container);
$articles  = new ArticleController($container);
$users     = new UserController($container);
$dashboard = new DashboardController($container);

// ── Rutas ─────────────────────────────────────────────────────
$router = new Router();

// Auth
$router->get('/auth/login',          [$auth, 'loginForm']);
$router->post('/auth/login',         [$auth, 'login']);
$router->get('/auth/logout',         [$auth, 'logout']);
$router->get('/auth/forgot-password', [$auth, 'forgotForm']);
$router->post('/auth/forgot-password',[$auth, 'forgot']);
$router->get('/auth/reset-password', [$auth, 'resetForm']);
$router->post('/auth/reset-password',[$auth, 'reset']);

// Dashboard
$router->get('/',          [$dashboard, 'index']);
$router->get('/dashboard', [$dashboard, 'index']);

// Articles CRUDL
$router->get('/articles',                 [$articles, 'index']);
$router->get('/articles/create',          [$articles, 'create']);
$router->post('/articles/store',          [$articles, 'store']);
$router->get('/articles/{id}',            [$articles, 'show']);
$router->get('/articles/{id}/edit',       [$articles, 'edit']);
$router->post('/articles/{id}/update',    [$articles, 'update']);
$router->post('/articles/{id}/delete',    [$articles, 'delete']);

// Users CRUDL (admin only)
$router->get('/users',                    [$users, 'index']);
$router->get('/users/create',             [$users, 'create']);
$router->post('/users/store',             [$users, 'store']);
$router->get('/users/{id}/edit',          [$users, 'edit']);
$router->post('/users/{id}/update',       [$users, 'update']);
$router->post('/users/{id}/delete',       [$users, 'delete']);

// ── Despachar ─────────────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Normalizar URI (quitar el prefijo del subdirectorio si existe)
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
    $uri = substr($uri, strlen($scriptDir));
}
$uri = '/' . ltrim($uri, '/');

// Soporte _method override para DELETE/PUT via formularios
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

$router->dispatch($method, $uri);
