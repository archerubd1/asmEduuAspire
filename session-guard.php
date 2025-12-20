<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * session-guard.php
 * Unified session manager for PHX LXP (PHP 5.4+ compatible)
 * Prevents session expiry issues and keeps backward compatibility
 */

// Always include after config.php or before any output
if (session_id() == '') session_start();

// -----------------------------------------------------------------------------
// 1️⃣ Backward compatibility: Map old session keys if found
// -----------------------------------------------------------------------------
if (isset($_SESSION['learner_id']) && !isset($_SESSION['phx_user_id'])) {
    $_SESSION['phx_user_id']    = $_SESSION['learner_id'];
}
if (isset($_SESSION['user_name']) && !isset($_SESSION['phx_user_login'])) {
    $_SESSION['phx_user_login'] = $_SESSION['user_name'];
    $_SESSION['phx_user_name']  = $_SESSION['user_name'];
}

// -----------------------------------------------------------------------------
// 2️⃣ Validate active session
// -----------------------------------------------------------------------------
if (
    !isset($_SESSION['phx_logged_in']) || $_SESSION['phx_logged_in'] !== true ||
    !isset($_SESSION['phx_user_id'])   ||
    !isset($_SESSION['phx_user_login'])
) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

// -----------------------------------------------------------------------------
// 3️⃣ Idle timeout (default: 60 minutes)
// -----------------------------------------------------------------------------
$timeout_duration = 60 * 60; // 60 minutes

if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session timed out due to inactivity. Please log in again.")));
    exit;
} else {
    $_SESSION['login_time'] = time(); // refresh session activity timer
}

// -----------------------------------------------------------------------------
// 4️⃣ Security hardening (recommended)
// -----------------------------------------------------------------------------
if (!isset($_SESSION['session_token'])) {
    $_SESSION['session_token'] = md5(uniqid(mt_rand(), true));
}

// Optional: Restrict session reuse across IPs or browsers
if (!isset($_SESSION['ip_check'])) {
    $_SESSION['ip_check'] = $_SERVER['REMOTE_ADDR'];
} elseif ($_SESSION['ip_check'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Security check failed. Please log in again.")));
    exit;
}
?>
