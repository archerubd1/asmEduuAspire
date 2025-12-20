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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['processType']) && $_POST['processType'] === 'courseLibrary') {
    
    // Retrieve form inputs
    $userName = trim($_POST['userName']); // Ensure it's not empty
    $learning_category = trim($_POST['learning_category']);
    $course_id = trim($_POST['course_name']); // 
    $resource_type = trim($_POST['resource_type']);
    $access_level = trim($_POST['access_level']);

    // File upload directory
    $uploadDir = "../../uploads/course_resources/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if not exists
    }

    // Validate if files are uploaded
    if (!isset($_FILES['files'])) {
        echo "Error: No files uploaded.";
        exit;
    }

    $fileCount = count($_FILES['files']['name']);
    $uploadedFiles = [];

    // Prepare SQL statement
    $stmt = $coni->prepare("INSERT INTO course_library (course_name, learning_category, resource_type, file_name, file_path, access_level, instructor_username, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    if (!$stmt) {
        die("SQL Error: " . $coni->error);
    }

    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = basename($_FILES['files']['name'][$i]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allowed file types
        $allowedTypes = ['pdf', 'ppt', 'pptx', 'mp4', 'avi', 'mkv', 'doc', 'docx', 'zip'];

        if (in_array($fileType, $allowedTypes)) {
            if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) {
                echo "File Upload Error: " . $_FILES['files']['error'][$i];
                exit;
            }

            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFilePath)) {
                // Bind parameters correctly
                $stmt->bind_param("sssssss", $course_id, $learning_category, $resource_type, $fileName, $targetFilePath, $access_level, $userName);

                if (!$stmt->execute()) {
                    echo "Database Error: " . $stmt->error;
                    echo $course_id;
                 
exit; // Stop execution to check values

                    exit;
                }
                $uploadedFiles[] = ['name' => $fileName, 'path' => $targetFilePath];
            } else {
                echo "Error uploading file: " . $fileName;
                exit;
            }
        } else {
            echo "Invalid file type: " . $fileName;
            exit;
        }
    }

    // Close the statement
    $stmt->close();

    // Redirect with success message
    header("Location: course-library.php?msg=" . urlencode(base64_encode("Course Resource Uploaded Successfully.")));
    exit;
}
?>
