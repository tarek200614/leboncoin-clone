<?php
declare(strict_types=1);

// Secure Session Configuration (Must be before session_start)
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => '',
    'secure' => true,      // Requires HTTPS in production
    'httponly' => true,    // Prevents JS access to session cookie
    'samesite' => 'Strict' // Prevents CSRF via cross-site cookies
]);
session_start();

// Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf(): void {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
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

function redirect(string $url): void {
    header("Location: $url");
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
