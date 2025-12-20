<?php
// platformInstructor/undo_attachment.php
// POST: att_id=123  (Only instructors allowed)

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
if ($att_id <= 0) {
    http_response_code(400);
    exit(json_encode(array('ok' => false, 'msg' => 'Invalid attachment id')));
}

/* Fetch attachment */
$sql = "SELECT id, attempt_id, is_deleted, removed_at FROM problem_attachments WHERE id = ? LIMIT 1";
$stmt = $coni->prepare($sql);
if (!$stmt) {
    error_log("prepare failed: " . $coni->error);
    http_response_code(500);
    exit(json_encode(array('ok' => false, 'msg' => 'Server error')));
}
$stmt->bind_param('i', $att_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) { $stmt->close(); http_response_code(404); exit(json_encode(array('ok'=>false,'msg'=>'Attachment not found'))); }
$row = $res->fetch_assoc();
$stmt->close();

if ((int)$row['is_deleted'] !== 1) {
    exit(json_encode(array('ok' => false, 'msg' => 'Attachment not deleted')));
}

/* Optional: check undo window server-side (if you provided undo_expires_at earlier)
   Here we allow undo if removed_at not older than 5 minutes (configurable).
*/
$now = time();
$max_undo_seconds = 300; // 5 minutes
if ((int)$row['removed_at'] + $max_undo_seconds < $now) {
    exit(json_encode(array('ok' => false, 'msg' => 'Undo window expired')));
}

/* Restore */
$upd = "UPDATE problem_attachments SET is_deleted = 0, removed_by = NULL, removed_at = NULL WHERE id = ? LIMIT 1";
$uStmt = $coni->prepare($upd);
if (!$uStmt) {
    error_log("prepare failed: " . $coni->error);
    http_response_code(500);
    exit(json_encode(array('ok' => false, 'msg' => 'Server error')));
}
$uStmt->bind_param('i', $att_id);
if (!$uStmt->execute()) {
    error_log("execute failed: " . $uStmt->error);
    $uStmt->close();
    http_response_code(500);
    exit(json_encode(array('ok' => false, 'msg' => 'Failed to restore attachment')));
}
$uStmt->close();

/* Audit */
$ins = "INSERT INTO problem_attachments_audit (attachment_id, attempt_id, actor, action, reason, created_at)
        VALUES (?, ?, ?, 'undo_delete', ?, ?)";
$insSt = $coni->prepare($ins);
$reason = 'undo_by_instructor';
if ($insSt) {
    $insSt->bind_param('iissi', $att_id, $row['attempt_id'], $session_user, $reason, $now);
    $insSt->execute();
    $insSt->close();
}

echo json_encode(array('ok' => true, 'att_id' => $att_id));
exit;
