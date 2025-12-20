<?php
/**
 *  Astraal LXP - save_learner_profile.php (Dispatcher)
 * Modular handler router
 * PHP 5.4 compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    echo json_encode(array('status' => 'error', 'message' => 'Session expired'));
    exit;
}

$phx_user_id = (int) $_SESSION['phx_user_id'];
$section     = isset($_POST['section']) ? $_POST['section'] : '';

switch ($section) {
    case 'account':
        require('save_account.php');
        break;

    case 'notifications':
        require('save_notifications.php');
        break;

    case 'connections':
        require('save_connections.php');
        break;

    case 'deactivate':
        require('save_deactivate.php');
        break;

    default:
        echo json_encode(array('status' => 'error', 'message' => 'Invalid section'));
        break;
}

exit;
?>
