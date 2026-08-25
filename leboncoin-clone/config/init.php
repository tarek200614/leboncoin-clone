<?php
declare(strict_types=1);

// Detect HTTPS
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
    || ($_SERVER['SERVER_PORT'] ?? 80) == 443 
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

// Secure Session Configuration (Must be before session_start)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => '',
        'secure' => $is_https,    // Automatically true on HTTPS, false on local HTTP
        'httponly' => true,      // Prevents JS access to session cookie
        'samesite' => 'Lax'      // Allows smooth navigation and form posts
    ]);
    session_start();
}

// Calculate BASE_URL dynamically
function get_base_url(): string {
    if ($envBaseUrl = getenv('BASE_URL')) {
        return rtrim($envBaseUrl, '/');
    }
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    $appDir = str_replace('\\', '/', dirname(__DIR__));
    
    if (!empty($docRoot) && strpos($appDir, $docRoot) === 0) {
        $basePath = substr($appDir, strlen($docRoot));
        return rtrim(str_replace('\\', '/', $basePath), '/');
    }
    
    if (preg_match('#^(.*?)/(pages|includes|config|assets|index\.php)#', $scriptName, $matches)) {
        return rtrim($matches[1], '/');
    }
    return '';
}

if (!defined('BASE_URL')) {
    define('BASE_URL', get_base_url());
}

function url(string $path = ''): string {
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    $path = '/' . ltrim($path, '/');
    return BASE_URL . $path;
}

// Security Headers
if (!headers_sent()) {
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Erreur de validation de sécurité (CSRF). Veuillez réessayer.');
    }
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function redirect(string $target): void {
    $targetUrl = (str_starts_with($target, 'http://') || str_starts_with($target, 'https://')) 
        ? $target 
        : url($target);
    header("Location: $targetUrl");
    exit;
}

function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function require_login(): void {
    if (!isset($_SESSION['utilisateur_id'])) {
        set_flash('error', 'Vous devez être connecté pour accéder à cette page.');
        redirect('/pages/auth/connection.php');
    }
}

function require_admin(): void {
    require_login();
    if (!isset($_SESSION['utilisateur_role']) || $_SESSION['utilisateur_role'] !== 'admin') {
        set_flash('error', 'Accès réservé aux administrateurs.');
        redirect('/index.php');
    }
}

