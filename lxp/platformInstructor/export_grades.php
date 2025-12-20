<?php
/**
 * File: platformInstructor/export_grades.php
 * CSV export of graded attempts (instructor-only)
 * Filters (optional, via GET):
 *   - date_from (YYYY-MM-DD)
 *   - date_to   (YYYY-MM-DD)
 *   - type_slug (problem_types.slug)
 * Outputs CSV: attempt_id, learner_login, learner_name, type_title, variant_id, score, graded_at, feedback_excerpt
 *
 * PHP 5.4+ compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit("Unauthorized");
}
$session_user = $_SESSION['phx_user_login'];

function is_instructor($user_login) {
    return true; // replace in prod
}
if (!is_instructor($session_user)) {
    http_response_code(403);
    exit("Access denied.");
}

/* Gather filters */
$date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$date_to   = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
$type_slug = isset($_GET['type_slug']) ? trim($_GET['type_slug']) : '';

$where = " WHERE pa.score IS NOT NULL "; // only graded attempts
$params = array();
$types = '';

if ($date_from !== '') {
    $ts = strtotime($date_from . ' 00:00:00');
    if ($ts !== false) {
        $where .= " AND pa.updated_at >= ? ";
        $types .= 'i';
        $params[] = (int)$ts;
    }
}
if ($date_to !== '') {
    $ts = strtotime($date_to . ' 23:59:59');
    if ($ts !== false) {
        $where .= " AND pa.updated_at <= ? ";
        $types .= 'i';
        $params[] = (int)$ts;
    }
}
if ($type_slug !== '') {
    $where .= " AND pt.slug = ? ";
    $types .= 's';
    $params[] = $type_slug;
}

/* Build SQL */
$sql = "
    SELECT pa.id AS attempt_id,
           pa.user_login,
           u.full_name AS learner_name,
           pt.title AS type_title,
           pv.id AS variant_id,
           pa.score,
           pa.updated_at,
           pa.feedback
    FROM problem_attempts pa
    LEFT JOIN problem_variants pv ON pa.problem_variant_id = pv.id
    LEFT JOIN problem_types pt ON pv.problem_type_id = pt.id
    LEFT JOIN users u ON pa.user_login = u.login
    {$where}
    ORDER BY pa.updated_at DESC
    LIMIT 10000
";

/* Prepare & bind if needed */
$stmt = $coni->prepare($sql);
if ($stmt === false) {
    // fallback to direct query if prepare fails for dynamic SQL (shouldn't)
    error_log("prepare failed: " . $coni->error);
    header("HTTP/1.1 500 Internal Server Error");
    exit("Server error");
}
if (!empty($params)) {
    // bind params dynamically (simple approach)
    $bind_names = array();
    $bind_names[] = $types;
    for ($i=0;$i<count($params);$i++) {
        $bind_names[] = $params[$i];
    }
    // call_user_func_array requires references
    $refs = array();
    foreach($bind_names as $k => $v) $refs[$k] = &$bind_names[$k];
    call_user_func_array(array($stmt, 'bind_param'), $refs);
}
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    $stmt->close();
    header("HTTP/1.1 500 Internal Server Error");
    exit("Server error");
}
$res = $stmt->get_result();

/* Send CSV headers */
$filename = 'grades_export_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

/* Open output stream and write BOM for Excel compatibility */
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM

/* CSV header row */
fputcsv($out, array('Attempt ID','Learner Login','Learner Name','Problem Type','Variant ID','Score (%)','Graded At','Feedback Excerpt'));

/* Write rows */
while ($row = $res->fetch_assoc()) {
    $attempt_id = (int)$row['attempt_id'];
    $login = isset($row['user_login']) ? $row['user_login'] : '';
    $name = isset($row['learner_name']) ? $row['learner_name'] : '';
    $type_title = isset($row['type_title']) ? $row['type_title'] : '';
    $variant_id = isset($row['variant_id']) ? $row['variant_id'] : '';
    $score = isset($row['score']) ? $row['score'] : '';
    $graded_at = isset($row['updated_at']) && is_numeric($row['updated_at']) ? date('Y-m-d H:i:s', (int)$row['updated_at']) : '';
    $fb = isset($row['feedback']) ? $row['feedback'] : '';
    // Extract a short feedback excerpt (strip RUBRIC_JSON)
    $excerpt = '';
    if ($fb !== '') {
        if (strpos($fb, 'RUBRIC_JSON:') === 0) {
            $parts = explode("\n\nInstructor feedback:\n", $fb, 2);
            $excerpt = isset($parts[1]) ? $parts[1] : '';
        } else {
            $excerpt = $fb;
        }
    }
    // Truncate excerpt to 300 chars for CSV
    if (strlen($excerpt) > 300) $excerpt = substr($excerpt, 0, 297) . '...';
    fputcsv($out, array($attempt_id, $login, $name, $type_title, $variant_id, $score, $graded_at, $excerpt));
}
fclose($out);
exit;
