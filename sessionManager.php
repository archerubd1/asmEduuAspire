<?php
/**
 * Phoenix LXP - Session Manager Utility
 * Ensures valid session and handles idle timeouts
 * PHP 5.4+ Compatible (UwAmp + GoDaddy)
 */

require_once(__DIR__ . '/config.php');

// ======================================================
// 1️⃣ Idle Timeout Configuration
// ======================================================
$session_timeout = 3600; // 60 minutes

// ======================================================
// 2️⃣ Check if User is Logged In
// ======================================================
if (empty($_SESSION['phx_logged_in']) || empty($_SESSION['phx_user_id'])) {
    session_unset();
    session_destroy();

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "warning",
            title: "Session Expired",
            text: "Your session has expired or you are not logged in. Please log in again.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Login Now"
        }).then(() => {
            window.location.href = "/phxlogin.php";
        });
    </script>';
    exit;
}

// ======================================================
// 3️⃣ Check Idle Timeout
// ======================================================
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > $session_timeout)) {
    session_unset();
    session_destroy();

    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "info",
            title: "Session Timeout",
            text: "You were inactive for over 60 minutes. Please log in again to continue.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Re-login"
        }).then(() => {
            window.location.href = "/phxlogin.php";
        });
    </script>';
    exit;
}

// ======================================================
// 4️⃣ Refresh Session Timer & Optionally Regenerate ID
// ======================================================
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
}

if (time() - $_SESSION['last_regeneration'] > 600) { // regenerate every 10 mins
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

$_SESSION['login_time'] = time(); // refresh idle timer
?>
