<?php
/**
 * Astraal LXP - save_connections.php
 * PHP 5.4 | MySQL 5.x | UwAmp compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($coni) || !$coni) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Database connection not available'
    ));
    exit;
}

mysqli_set_charset($coni, 'utf8mb4');

if (!isset($_SESSION['phx_user_id'])) {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Session expired. Please login again.'
    ));
    exit;
}

$learner_id = (int)$_SESSION['phx_user_id'];

function safe($val, $conn) {
    return mysqli_real_escape_string($conn, trim($val));
}

$platform = isset($_POST['platform']) ? safe($_POST['platform'], $coni) : '';
$url      = isset($_POST['url']) ? safe($_POST['url'], $coni) : '';

if ($platform === '') {
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Missing platform'
    ));
    exit;
}

/**
 * UNLINK
 */
if ($url === '') {

    mysqli_query(
        $coni,
        "DELETE FROM learner_connections
         WHERE learner_id = $learner_id
           AND platform = '$platform'"
    );

    echo json_encode(array(
        'status'    => 'success',
        'message'   => 'Connection removed successfully.',
        'next_step' => 'profiling'
    ));
    exit;
}

/**
 * CHECK EXISTING
 */
$check = mysqli_query(
    $coni,
    "SELECT connection_id
     FROM learner_connections
     WHERE learner_id = $learner_id
       AND platform = '$platform'
     LIMIT 1"
);

if ($check && mysqli_num_rows($check) > 0) {

    mysqli_query(
        $coni,
        "UPDATE learner_connections
         SET profile_url = '$url',
             status = 'pending',
             updated_on = NOW()
         WHERE learner_id = $learner_id
           AND platform = '$platform'"
    );

} else {

    mysqli_query(
        $coni,
        "INSERT INTO learner_connections
         (learner_id, platform, profile_url, status, created_on, updated_on)
         VALUES
         ($learner_id, '$platform', '$url', 'pending', NOW(), NOW())"
    );
}

echo json_encode(array(
    'status'    => 'success',
    'message'   => 'Connection submitted for review.',
    'next_step' => 'profiling'
));
exit;
