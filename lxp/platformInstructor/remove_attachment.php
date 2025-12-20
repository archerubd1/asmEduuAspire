<?php
// platformInstructor/remove_attachment.php
// POST: att_id=123 & reason=...  (Only instructors allowed) — soft-delete

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

header('Content-Type: application/json');

if (!isset($_SESSION['phx_user_login'])) {
    http_response_code(401);
    exit(json_encode(array('ok' => false, 'msg' => 'Unauthorized')));
}
$session_user = $_SESSION['phx_user_login'];

/* Replace with your real instructor check */
function is_instructor($user_login) {
    return true; // tighten this in production
}

if (!is_instructor($session_user)) {
    http_response_code(403);
    exit(json_encode(array('ok' => false, 'msg' => 'Insufficient permissions')));
}

$att_id = isset($_POST['att_id']) ? (int)$_POST['att_id'] : 0;
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
if ($att_id <= 0) {
    http_response_code(400);
    exit(json_encode(array('ok' => false, 'msg' => 'Invalid attachment id')));
}

/* Fetch attachment */
$sql = "SELECT id, attempt_id, file_path, is_deleted FROM problem_attachments WHERE id = ? LIMIT 1";
$stmt = $coni->prepare($sql);
if (!$stmt) {
    error_log("prepare failed: " . $coni->error);
    http_response_code(500);
    exit(json_encode(array('ok' => false, 'msg' => 'Server error')));
}
$stmt->bind_param('i', $att_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    http_response_code(404);
    exit(json_encode(array('ok' => false, 'msg' => 'Attachment not found')));
}
$row = $res->fetch_assoc();
$stmt->close();

if ((int)$row['is_deleted'] === 1) {
    // already removed
    exit(json_encode(array('ok' => false, 'msg' => 'Attachment already removed')));
}

/* Soft-delete */
$now = time();
$upd = "UPDATE problem_attachments SET is_deleted = 1, removed_by = ?, removed_at = ? WHERE id = ? LIMIT 1";
$uStmt = $coni->prepare($upd);
if (!$uStmt) {
    error_log("prepare update failed: " . $coni->error);
    http_response_code(500);
    exit(json_encode(array('ok' => false, 'msg' => 'Server error')));
}
$uStmt->bind_param('sii', $session_user, $now, $att_id);
if (!$uStmt->execute()) {
    error_log("execute update failed: " . $uStmt->error);
    $uStmt->close();
    http_response_code(500);
    exit(json_encode(array('ok' => false, 'msg' => 'Failed to mark attachment removed')));
}
$uStmt->close();

/* Audit */
$ins = "INSERT INTO problem_attachments_audit (attachment_id, attempt_id, actor, action, reason, created_at)
        VALUES (?, ?, ?, 'soft_delete', ?, ?)";
$insSt = $coni->prepare($ins);
if ($insSt) {
    $insSt->bind_param('iissi', $att_id, $row['attempt_id'], $session_user, $reason, $now);
    $insSt->execute();
    $insSt->close();
}

/* Provide undo token (timestamp) and undo window seconds */
$undo_window_seconds = 30; // allow 30s undo from client (configurable)
$undo_expires_at = $now + $undo_window_seconds;

echo json_encode(array(
    'ok' => true,
    'att_id' => $att_id,
    'undo_expires_at' => $undo_expires_at,
    'undo_window' => $undo_window_seconds
));
exit;
