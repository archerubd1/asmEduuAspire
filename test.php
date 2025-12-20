<?php
require_once('config.php');

if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 1;
} else {
    $_SESSION['count']++;
}

echo "<h3>Session Test</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Count: " . $_SESSION['count'] . "<br>";
echo "<pre>";
print_r(session_get_cookie_params());
echo "</pre>";

echo '<p><a href="test-session.php">Reload</a></p>';
