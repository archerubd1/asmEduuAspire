<?php
/**
 * Astraal LXP - Instructor Adaptive learning Paths
 * Refactored for new session-guard workflow (PHP 5.4 compatible)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ ensures unified phx_user_* sessions

// Ensure session is active and valid
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

$page = "ganification";
require_once('instructorHead_Nav2.php');



if (!$coni) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . mysqli_connect_error()]));
}

if (!isset($_SESSION['user_name'])) {
    die(json_encode(['status' => 'error', 'message' => 'Session expired or user not logged in.']));
}

$instructor_username = $_SESSION['user_name'];

$sql = "SELECT course_name, course_code, learning_category, resource_type, file_name, file_path, access_level, institute_corporate, created_at, updated_at
        FROM course_library
        WHERE instructor_username = ?";

$stmt = $coni->prepare($sql);
if (!$stmt) {
    die(json_encode(['status' => 'error', 'message' => 'SQL Prepare Failed: ' . $coni->error]));
}

$stmt->bind_param("s", $instructor_username);

if (!$stmt->execute()) {
    die(json_encode(['status' => 'error', 'message' => 'Query execution failed: ' . $stmt->error]));
}

$result = $stmt->get_result();
if (!$result) {
    die(json_encode(['status' => 'error', 'message' => 'Fetching result failed: ' . $coni->error]));
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$coni->close();

if (!empty($data)) {
    echo json_encode(['status' => 'success', 'data' => $data]);
} else {
    echo json_encode(['status' => 'no_data', 'message' => 'No records found for this instructor.']);
}
?>
