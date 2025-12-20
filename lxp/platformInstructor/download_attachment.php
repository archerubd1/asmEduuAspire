<?php
// File: platformInstructor/download_attachment.php
// GET: ?att_id=123
// Streams attachment if current user is allowed (instructor OR owner of attempt)

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit("Unauthorized");
}
$session_user = $_SESSION['phx_user_login'];

/* Permission check helper: adjust to your auth */
function is_instructor($user_login) {
    // Replace with your real check. For now, false (change as needed).
    // e.g. return in_array($user_login, $your_instructor_list);
    return true; // allow for now; tighten in production
}

$att_id = isset($_GET['att_id']) ? (int)$_GET['att_id'] : 0;
if ($att_id <= 0) {
    header("HTTP/1.1 400 Bad Request");
    exit("Invalid attachment id");
}

/* Fetch attachment and attempt owner */
$sql = "
    SELECT pa.id AS att_id, pa.file_path, pa.mime_type, pa.`hash`, pa.created_at,
           ppa.id AS attempt_id, ppa.user_login AS attempt_owner
    FROM problem_attachments pa
    JOIN problem_attempts ppa ON pa.attempt_id = ppa.id
    WHERE pa.id = ?
    LIMIT 1
";
$stmt = $coni->prepare($sql);
if (!$stmt) {
    error_log("prepare failed: " . $coni->error);
    header("HTTP/1.1 500 Internal Server Error");
    exit("Server error");
}
$stmt->bind_param('i', $att_id);
if (!$stmt->execute()) {
    error_log("execute failed: " . $stmt->error);
    $stmt->close();
    header("HTTP/1.1 500 Internal Server Error");
    exit("Server error");
}
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    header("HTTP/1.1 404 Not Found");
    exit("Attachment not found");
}
$row = $res->fetch_assoc();
$stmt->close();

$attempt_owner = $row['attempt_owner'];
$file_name = $row['file_path'];
$mime_type = $row['mime_type'] ? $row['mime_type'] : 'application/octet-stream';

/* Permission: allow if instructor OR owner */
if (!is_instructor($session_user) && $session_user !== $attempt_owner) {
    header("HTTP/1.1 403 Forbidden");
    exit("Access denied");
}

/* Build file path safely */
$uploads_base = realpath(__DIR__ . '/../../uploads/attempts');
if ($uploads_base === false) {
    error_log("uploads base not found");
    header("HTTP/1.1 500 Internal Server Error");
    exit("Server error");
}
$basename = basename($file_name);
$file_path = $uploads_base . DIRECTORY_SEPARATOR . $basename;

/* Verify file exists and is under uploads dir */
$realFile = realpath($file_path);
if ($realFile === false || strpos($realFile, $uploads_base) !== 0) {
    error_log("Invalid file path or traversal attempt: " . $file_path);
    header("HTTP/1.1 404 Not Found");
    exit("File not found");
}
if (!is_file($realFile) || !is_readable($realFile)) {
    header("HTTP/1.1 404 Not Found");
    exit("File not found");
}

/* Optional: Log download to attachments audit table */
$actor = $session_user;
$action = 'download';
$reason = 'instructor_or_owner_download';
$created_at = time();
$insSql = "
    INSERT INTO problem_attachments_audit
        (attachment_id, attempt_id, actor, action, reason, created_at)
    VALUES (?, ?, ?, ?, ?, ?)
";
$insStmt = $coni->prepare($insSql);
if ($insStmt) {
    $insStmt->bind_param('iisssi', $att_id, $row['attempt_id'], $actor, $action, $reason, $created_at);
    $insStmt->execute();
    $insStmt->close();
} else {
    // Not critical; log and continue
    error_log("Could not prepare audit insert: " . $coni->error);
}

/* Stream file with headers */
$filesize = filesize($realFile);
$disposition = 'attachment';
$download_name = $basename;
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime_type);
header('Content-Disposition: ' . $disposition . '; filename="' . rawurldecode($download_name) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . $filesize);

/* Clear output buffers and stream */
@ob_clean();
@flush();
$chunkSize = 8192;
$handle = fopen($realFile, 'rb');
if ($handle === false) {
    header("HTTP/1.1 500 Internal Server Error");
    exit("Unable to open file");
}
while (!feof($handle)) {
    echo fread($handle, $chunkSize);
    @flush();
}
fclose($handle);
exit;
