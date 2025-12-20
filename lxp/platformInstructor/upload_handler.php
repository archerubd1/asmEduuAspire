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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $type = isset($_POST['type']) ? $_POST['type'] : 'unknown';
    $file = $_FILES['csvFile'];

    // Debugging: Check file array
    //echo "<pre>";
   // print_r($file);
   // echo "</pre>";

    // Check if file was uploaded successfully
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = basename($file['name']);
        $tempPath = $file['tmp_name'];

        // Verify that temp file exists
        if (!file_exists($tempPath)) {
            die("Temporary file not found. Upload may have failed.");
        }

        // Define the upload directory
        $uploadDir = "uploads/instructor/";
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                die("Failed to create upload directory.");
            }
        }

        // Debug: Check if directory is writable
        if (!is_writable($uploadDir)) {
            die("Upload directory is not writable.");
        }

        // Set the destination path with timestamp
		$destinationPath = $uploadDir . date("Ymd_His") . '_'  . $type . '_' . $filename;


        // Move the uploaded file
        if (move_uploaded_file($tempPath, $destinationPath)) {
            echo "CSV file '$filename' for '$type' received and saved.";
        } else {
            die("Error moving uploaded file. Check permissions.");
        }
    } else {
        echo "File upload error. Error Code: " . $file['error'];
    }
} else {
    echo "No file uploaded.";
}
?>
