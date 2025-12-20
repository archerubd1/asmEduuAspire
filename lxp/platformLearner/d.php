<?php
// debug_variants.php — debug helper, PHP 5.4 compatible
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$type_slug = isset($_GET['type']) ? trim($_GET['type']) : '';
echo "<h3>Debug: problem_variants for slug: <code>" . htmlspecialchars($type_slug) . "</code></h3>";

echo "<pre>GET: " . htmlspecialchars(print_r($_GET, true)) . "</pre>";

if ($type_slug === '') {
    echo "<div style='color:orange'>No type provided. Call this page as ?type=authentic</div>";
    exit;
}

/* fetch problem_types row */
$stmt = $coni->prepare("SELECT * FROM problem_types WHERE slug = ? LIMIT 1");
if (!$stmt) { echo "<div style='color:red'>prepare error: " . htmlspecialchars($coni->error) . "</div>"; exit; }
$stmt->bind_param("s", $type_slug);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows) {
    $ptype = $res->fetch_assoc();
    echo "<h4>problem_types row:</h4><pre>" . htmlspecialchars(print_r($ptype, true)) . "</pre>";
    $ptype_id = (int)$ptype['id'];
} else {
    echo "<div style='color:red'>No problem_types row found for slug: " . htmlspecialchars($type_slug) . "</div>";
    $stmt->close();
    exit;
}
$stmt->close();

/* counts by level & difficulty */
$sql = "SELECT level, TRIM(difficulty_score) AS difficulty, COUNT(*) AS cnt
        FROM problem_variants
        WHERE problem_type_id = ?
        GROUP BY level, TRIM(difficulty_score)
        ORDER BY level, TRIM(difficulty_score)";
$stmt2 = $coni->prepare($sql);
if (!$stmt2) { echo "<div style='color:red'>prepare error: " . htmlspecialchars($coni->error) . "</div>"; exit; }
$stmt2->bind_param("i", $ptype_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$rows = array();
while ($r = $res2->fetch_assoc()) $rows[] = $r;
echo "<h4>Counts by level & difficulty:</h4><pre>" . htmlspecialchars(print_r($rows, true)) . "</pre>";
$stmt2->close();

/* show sample variants (trim difficulty, show lengths) */
$sql3 = "SELECT id, problem_slug, level, difficulty_score, TRIM(difficulty_score) AS diff_trim, LENGTH(difficulty_score) AS diff_len
         FROM problem_variants
         WHERE problem_type_id = ?
         ORDER BY level, id
         LIMIT 200";
$stmt3 = $coni->prepare($sql3);
if (!$stmt3) { echo "<div style='color:red'>prepare error: " . htmlspecialchars($coni->error) . "</div>"; exit; }
$stmt3->bind_param("i", $ptype_id);
$stmt3->execute();
$res3 = $stmt3->get_result();
$variants = array();
while ($r = $res3->fetch_assoc()) $variants[] = $r;
echo "<h4>Sample variants:</h4><pre>" . htmlspecialchars(print_r($variants, true)) . "</pre>";
$stmt3->close();

/* Try a trimmed, case-insensitive test query for level=teen and difficulty 'novice' */
$test_level = 'teen';
$test_diff = 'novice';
$sql4 = "SELECT COUNT(*) AS cnt FROM problem_variants WHERE problem_type_id = ? AND LOWER(TRIM(level)) = LOWER(TRIM(?)) AND LOWER(TRIM(difficulty_score)) = LOWER(TRIM(?))";
$stmt4 = $coni->prepare($sql4);
if ($stmt4) {
    $stmt4->bind_param("iss", $ptype_id, $test_level, $test_diff);
    $stmt4->execute();
    $r4 = $stmt4->get_result()->fetch_assoc();
    echo "<h4>Test (trim+lower) count for level='teen' & difficulty='novice':</h4><pre>" . htmlspecialchars(print_r($r4, true)) . "</pre>";
    $stmt4->close();
} else {
    echo "<div style='color:red'>prepare error (test query): " . htmlspecialchars($coni->error) . "</div>";
}

/* Finally, print mysqli error (if any) */
if ($coni->error) {
    echo "<div style='color:red'><strong>Last mysqli error:</strong> " . htmlspecialchars($coni->error) . "</div>";
} else {
    echo "<div style='color:green'>No immediate mysqli error reported.</div>";
}

echo "<div style='margin-top:20px;color:gray'>Remove or delete this debug file after use.</div>";
