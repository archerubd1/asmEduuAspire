<?php
// debug_exec_problem_solving.php
// Very verbose runtime debugger for problem_solving.php logic.
// PHP 5.4 + MySQL 5.x compatible.
// Drop into learners/ and open with your usual GET params.

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

header('Content-Type: text/plain; charset=utf-8');

echo "DEBUG RUN - problem_solving query executor\n";
echo "------------------------------------------\n";

if (!isset($_SESSION['phx_user_login'])) {
    echo "SESSION: phx_user_login not set. Redirected? Current session keys:\n";
    echo htmlspecialchars(print_r(array_keys($_SESSION), true)) . "\n";
    exit;
}
$user_login = $_SESSION['phx_user_login'];
echo "SESSION phx_user_login: {$user_login}\n\n";

/* Read incoming GET exactly as the page would */
$get = $_GET;
echo "Raw GET: " . print_r($get, true) . "\n";

/* Inputs */
$type_slug = isset($_GET['type']) ? trim($_GET['type']) : '';
$level     = isset($_GET['level']) ? trim($_GET['level']) : '';
$d_raw     = isset($_GET['difficulty']) ? trim($_GET['difficulty']) : '0';
$page      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page  = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

echo "Parsed inputs:\n";
echo " type_slug: '{$type_slug}'\n";
echo " level:     '{$level}'\n";
echo " difficulty_raw: '{$d_raw}'\n";
echo " page: {$page}, per_page: {$per_page}\n\n";

/* sanity */
if ($type_slug === '') {
    echo "ERROR: type (slug) is required. Abort.\n";
    exit;
}

/* Validate level list (we allow empty) */
$validLevels = array('kid','teen','adult');
if ($level !== '' && !in_array($level, $validLevels, true)) {
    echo "NOTE: Provided level '{$level}' is not in validLevels; it will be treated as EMPTY.\n";
    $level = '';
}

/* Normalize difficulty same as page */
$allowed_text = array('novice','beginner','intermediate','advance','expert','advanced');
$numeric_to_text = array('1'=>'novice','2'=>'beginner','3'=>'intermediate','4'=>'advance','5'=>'expert');

if ($d_raw === '' || $d_raw === '0') {
    $d_filter = '0';
} elseif (array_key_exists($d_raw, $numeric_to_text)) {
    $d_filter = $numeric_to_text[$d_raw];
} elseif (in_array(strtolower($d_raw), $allowed_text, true)) {
    $d_filter = strtolower($d_raw);
} else {
    $d_filter = '0';
}

echo "Normalized difficulty (d_filter): '{$d_filter}'\n\n";

/* Check $coni exists and is mysqli */
if (!isset($coni) || !($coni instanceof mysqli)) {
    echo "ERROR: \$coni is not set or not an instance of mysqli.\n";
    echo "Var type: " . gettype($GLOBALS['coni']) . "\n";
    var_export(isset($GLOBALS['coni']) ? $GLOBALS['coni'] : null);
    echo "\n";
    exit;
} else {
    echo "\$coni is a mysqli instance. Server: " . $coni->host_info . "\n\n";
}

/* Fetch problem_type */
if ($stmt = $coni->prepare("SELECT id, title, slug, active FROM problem_types WHERE slug = ? LIMIT 1")) {
    $stmt->bind_param("s", $type_slug);
    $ok = $stmt->execute();
    echo "Execute problem_types prepare() returned: " . ($ok ? "true" : "false") . "\n";
    if (!$ok) echo "Stmt error: " . $stmt->error . "\n";
    $res = $stmt->get_result();
    $ptype = $res ? $res->fetch_assoc() : null;
    echo "problem_types row: " . print_r($ptype, true) . "\n";
    $stmt->close();
} else {
    echo "Prepare failed for problem_types: " . htmlspecialchars($coni->error) . "\n";
    exit;
}
if (!$ptype) { echo "ERROR: problem type not found or inactive. Abort.\n"; exit; }
$ptype_id = (int)$ptype['id'];

/* If level is empty, we stop here in the real page; but debug continues */
if ($level === '') {
    echo "NOTE: level not provided — the real page would show an age selector instead of running queries.\n";
    echo "To test full query, re-run with &level=kid (or teen/adult)\n";
    // not exiting to allow a test run if desired
}

/* Build WHERE same as the robust page */
$where_sql = " WHERE problem_type_id = ? AND LOWER(TRIM(level)) = LOWER(TRIM(?)) ";
$bind_types = "is";
$bind_vals = array($ptype_id, $level);

if ($d_filter !== '0') {
    $where_sql .= " AND (LOWER(TRIM(difficulty_score)) = LOWER(TRIM(?)) OR LOWER(TRIM(problem_slug)) LIKE CONCAT('%-', LOWER(TRIM(?)))) ";
    $bind_types .= "ss";
    $bind_vals[] = $d_filter;
    $bind_vals[] = $d_filter;
}

echo "Constructed WHERE SQL:\n" . $where_sql . "\n";
echo "Bind types: {$bind_types}\n";
echo "Bind values: " . print_r($bind_vals, true) . "\n\n";

/* COUNT query */
$count_sql = "SELECT COUNT(*) AS cnt FROM problem_variants " . $where_sql;
echo "COUNT SQL: " . $count_sql . "\n";

if ($stmt = $coni->prepare($count_sql)) {
    // bind dynamically
    $bind_arr = array();
    $bind_arr[] = & $bind_types;
    for ($i = 0; $i < count($bind_vals); $i++) $bind_arr[] = & $bind_vals[$i];
    call_user_func_array(array($stmt,'bind_param'), $bind_arr);
    $exe = $stmt->execute();
    echo "COUNT stmt execute returned: " . ($exe ? "true" : "false") . "\n";
    if (!$exe) echo "COUNT stmt error: " . $stmt->error . "\n";
    $res = $stmt->get_result();
    $cnt = ($res && ($r = $res->fetch_assoc())) ? (int)$r['cnt'] : 0;
    echo "COUNT result: {$cnt}\n";
    $stmt->close();
} else {
    echo "COUNT prepare failed: " . htmlspecialchars($coni->error) . "\n";
}

/* Now fetch rows (limit test) */
$orderField = "FIELD(difficulty_score,'novice','beginner','intermediate','advance','expert')";
$sqlVar = "SELECT id, problem_slug, statement, expected_outcome, difficulty_score
           FROM problem_variants
           {$where_sql}
           ORDER BY {$orderField} ASC
           LIMIT ?, ?";

echo "SELECT SQL (with LIMIT placeholders):\n" . $sqlVar . "\n";

if ($stmt = $coni->prepare($sqlVar)) {
    $all_types = $bind_types . "ii";
    $all_vals = $bind_vals;
    $offset = max(0, ($page - 1) * $per_page);
    $all_vals[] = $offset;
    $all_vals[] = $per_page;

    echo "Final bind types: {$all_types}\n";
    echo "Final bind values (with offset, per_page): " . print_r($all_vals, true) . "\n";

    $bind_all = array();
    $bind_all[] = & $all_types;
    for ($i = 0; $i < count($all_vals); $i++) $bind_all[] = & $all_vals[$i];
    call_user_func_array(array($stmt,'bind_param'), $bind_all);

    $exe = $stmt->execute();
    echo "SELECT execute returned: " . ($exe ? "true" : "false") . "\n";
    if (!$exe) echo "SELECT stmt error: " . $stmt->error . "\n";

    $res = $stmt->get_result();
    $rows = array();
    if ($res) {
        while ($r = $res->fetch_assoc()) $rows[] = $r;
    }
    echo "Returned rows count: " . count($rows) . "\n";
    if (!empty($rows)) {
        foreach ($rows as $i => $row) {
            echo "Row[$i]: " . print_r($row, true) . "\n";
        }
    } else {
        echo "No rows returned by SELECT.\n";
    }
    $stmt->close();
} else {
    echo "SELECT prepare failed: " . htmlspecialchars($coni->error) . "\n";
}

/* Show last mysqli error if any */
if ($coni->error) {
    echo "\nLast mysqli error: " . htmlspecialchars($coni->error) . "\n";
} else {
    echo "\nNo mysqli error reported.\n";
}

echo "\nNetwork request check: ensure the browser request for the page includes the GET params you expect (use DevTools Network tab)\n";
echo "Also check page source for <!-- DEBUG: total_count=... --> marker if you used the page version.\n";
echo "\nEnd debug.\n";
