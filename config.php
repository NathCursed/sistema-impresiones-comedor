<?php
/**
 * Configuración general del Sistema de Impresiones y Comedor.
 * Cambie aquí el nombre del negocio y los datos de conexión.
 */

// ---- Datos del negocio ----
define('NEGOCIO', 'Impresiones y Comedor');
define('LUGAR', 'Apopa, San Salvador');

// ---- Conexión a MySQL (valores por defecto de XAMPP) ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'impresiones_comedor');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---- Listas del sistema ----
const AREAS      = ['Impresiones', 'Comida'];
const CATEGORIAS = [
    'Impresiones' => ['Impresión', 'Copias', 'Servicios'],
    'Comida'      => ['Almuerzos', 'Desayunos', 'Snacks', 'Bebidas'],
];
const TIPOS   = ['Para llevar', 'Comer aquí', 'Recoger después'];
const ESTADOS = ['Pendiente', 'En proceso', 'Listo', 'Entregado', 'Cancelado'];

date_default_timezone_set('America/El_Salvador');

// ---- Sesión ----
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- Conexión PDO ----
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    // Misma zona horaria en MySQL que en PHP (para "hoy")
    $pdo->exec("SET time_zone = '" . date('P') . "'");
} catch (PDOException $e) {
    die('<h2>No se pudo conectar a la base de datos</h2><p>' . htmlspecialchars($e->getMessage()) .
        '</p><p>Revise que MySQL esté encendido en XAMPP y que haya importado <code>database/database.sql</code>.</p>');
}

// ---- Funciones de ayuda ----

/** Escapa texto para mostrarlo en HTML (evita XSS). */
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

/** Formatea un número como dinero: 1.5 -> $1.50 */
function dinero($n): string { return '$' . number_format((float)$n, 2); }

/** Redirige con un mensaje opcional y termina. */
function ir(string $url, string $msg = ''): void {
    if ($msg !== '') $url .= (str_contains($url, '?') ? '&' : '?') . 'msg=' . urlencode($msg);
    header('Location: ' . $url);
    exit;
}

/** Obliga a iniciar sesión para ver la página. */
function requiere_login(): void {
    if (empty($_SESSION['usuario_id'])) ir('login.php');
}

/** Token CSRF para formularios POST. */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_campo(): string {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}
function verificar_csrf(): void {
    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        http_response_code(400);
        die('Solicitud no válida. Recargue la página e intente de nuevo.');
    }
}

/** Clase CSS para cada estado (colores). */
function clase_estado(string $estado): string {
    return 'est-' . strtolower(str_replace(' ', '-', $estado));
}
