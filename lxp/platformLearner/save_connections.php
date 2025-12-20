<?php
/**
 *  Astraal LXP - save_connections.php
 * Handles link / unlink actions for Connections tab
 * PHP 5.4 / UwAmp / GoDaddy compatible
 */

mysqli_set_charset($coni, 'utf8mb4');

function safe($val, $conn) {
    return mysqli_real_escape_string($conn, trim($val));
}

$platform = safe(isset($_POST['platform']) ? $_POST['platform'] : '', $coni);
$url      = safe(isset($_POST['url']) ? $_POST['url'] : '', $coni);

if ($platform == '') {
    echo json_encode(array('status' => 'error', 'message' => 'Missing platform'));
    exit;
}

// --- UNLINK (delete) if URL empty ---
if ($url == '') {
    $del = "DELETE FROM learner_connections WHERE learner_id = $phx_user_id AND platform = '$platform'";
    if (mysqli_query($coni, $del)) {
        echo json_encode(array('status' => 'success', 'message' => 'Connection removed successfully.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to remove connection.'));
    }
    exit;
}

// --- CHECK IF PLATFORM EXISTS ---
$check = mysqli_query($coni, "SELECT connection_id FROM learner_connections WHERE learner_id = $phx_user_id AND platform = '$platform' LIMIT 1");

if ($check && mysqli_num_rows($check) > 0) {
    // --- Update existing connection ---
    $update = "
        UPDATE learner_connections 
        SET profile_url = '$url', updated_on = NOW() 
        WHERE learner_id = $phx_user_id AND platform = '$platform'
    ";
    if (mysqli_query($coni, $update)) {
        echo json_encode(array('status' => 'success', 'message' => 'Connection updated successfully.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to update connection.'));
    }
} else {
    // --- Insert new connection ---
    $insert = "
        INSERT INTO learner_connections (learner_id, platform, profile_url, created_on, updated_on)
        VALUES ($phx_user_id, '$platform', '$url', NOW(), NOW())
    ";
    if (mysqli_query($coni, $insert)) {
        echo json_encode(array('status' => 'success', 'message' => 'Connection saved successfully.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to save connection.'));
    }
}

exit;
?>
