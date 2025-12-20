<?php
/**
 * -------------------------------------------------------------------
 * Global Config — Astraal LXP
 * PHP 5.4+ Compatible | Works on UwAmp + GoDaddy Shared Hosting
 * -------------------------------------------------------------------
 */

ob_start(); // Prevent “headers already sent” before sessions

// =============================================================
// 1️⃣ Secure Session Initialization
// =============================================================
if (session_status() === PHP_SESSION_NONE) {
    // Custom session storage directory (safe fallback)
    $sess_path = dirname(__FILE__) . '/../tmp';
    if (!is_dir($sess_path) || !is_writable($sess_path)) {
        $sess_path = sys_get_temp_dir();
    }
    session_save_path($sess_path);

    // Detect localhost vs production
    $is_localhost = isset($_SERVER['REMOTE_ADDR']) &&
        in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));

    // Secure cookie parameters
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $domain   = $is_localhost ? '' : preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);
    $path     = $is_localhost ? '/asmEduuAspire' : '/';

    $cookieParams = array(
        'lifetime' => 0,
        'path'     => $path,
        'domain'   => $domain,
        'secure'   => $is_https,
        'httponly' => true,
        'samesite' => 'Lax'
    );

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params($cookieParams);
    } else {
        // Compatibility for PHP < 7.3
        session_set_cookie_params(0, $path, $domain, $is_https, true);
    }

    session_start();
}

// Disable caching (for secure session data)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// =============================================================
// 2️⃣ Environment Detection
// =============================================================
$is_localhost = isset($_SERVER['REMOTE_ADDR']) &&
    in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));

// =============================================================
// 3️⃣ Environment-Specific Config
// =============================================================
if ($is_localhost) {
    $base_url  = 'http://localhost/asmEduuAspire';
    $base_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/asmEduuAspire';

    $db_host = 'localhost';
    $db_name = 'ulxp';
    $db_user = 'root';
    $db_pass = 'root';
} else {
    $base_url  = 'https://eduuaspire.online';
    $base_path = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

    $db_host = 'localhost';
    $db_name = 'edu5';
    $db_user = 'fpAdmin';
    $db_pass = 'gza@123Admin';
}

// =============================================================
// 4️⃣ Canonical URL (optional SEO utility)
// =============================================================
if (!isset($canonical_url)) {
    $protocol = $is_https ? 'https://' : 'http://';
    $canonical_url = $protocol . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
}

// =============================================================
// 5️⃣ Database Connection
// =============================================================
$coni = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$coni) {
    die('Database connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($coni, 'utf8mb4'); // ✅ upgraded to utf8mb4

// =============================================================
// 6️⃣ Timezone
// =============================================================
date_default_timezone_set('Asia/Kolkata');

// =============================================================
// 7️⃣ Error Reporting
// =============================================================
if ($is_localhost) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>
