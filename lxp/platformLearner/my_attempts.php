<?php
// File: learners/my_attempts.php
// Learner "My Attempts" — uses problem_attempt_scores (no RUBRIC_JSON)
// Enhanced: page header shows problem variant and level (kid/teen/adult)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}
$phx_user_login = $_SESSION['phx_user_login'];

/* DB connection selection (keep your original approach) */
$mysqli = null;
if (!empty($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (!empty($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (!empty($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
else {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) die("DB connection failed: " . $mysqli->connect_error);
}

/* small helpers */
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function fmtDateBest($row) {
    $ts = 0;
    if (!empty($row['created_at'])) $ts = intval($row['created_at']);
    elseif (!empty($row['submitted_at'])) $ts = intval($row['submitted_at']);
    elseif (!empty($row['started_at'])) $ts = intval($row['started_at']);
    if (!$ts) return '';
    return date('n/j/Y, g:i A', $ts);
}
function parse_rubric_criteria_text($text) {
    $items = array();
    if ($text === null) return $items;
    $text_trim = trim($text);
    if ($text_trim !== '' && ($text_trim[0] === '[' || $text_trim[0] === '{')) {
        $decoded = json_decode($text_trim, true);
        if (is_array($decoded)) {
            foreach ($decoded as $entry) {
                if (is_string($entry)) { $label = trim($entry); if ($label !== '') $items[] = array('label'=>$label,'max'=>5.0); }
                elseif (is_array($entry)) { $label = isset($entry['label'])?trim($entry['label']):''; $max = isset($entry['max'])&&is_numeric($entry['max'])?floatval($entry['max']):5.0; if ($label!=='') $items[] = array('label'=>$label,'max'=>$max); }
            }
            return $items;
        }
    }
    $lines = preg_split('/\r\n|\r|\n/', $text_trim);
    foreach ($lines as $ln) {
        $lab = trim($ln); if ($lab==='') continue;
        if (strpos($lab,'::')!==false) { list($labPart,$maxPart)=array_map('trim',explode('::',$lab,2)); $max=is_numeric($maxPart)?floatval($maxPart):5.0; if($labPart!=='') $items[]=array('label'=>$labPart,'max'=>$max); }
        else $items[]=array('label'=>$lab,'max'=>5.0);
    }
    return $items;
}

/* Pagination & filters (unchanged) */
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; if ($page < 1) $page = 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; if ($limit < 1) $limit = 10; if ($limit > 100) $limit = 100;
$offset = ($page - 1) * $limit;
$type_filter  = isset($_GET['type']) ? trim($_GET['type']) : '';
$level_filter = isset($_GET['level']) ? trim($_GET['level']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

/* Build where clause and params (simple) */
$where = array('pa.user_login = ?');
$params = array($phx_user_login);
$types  = 's';

if ($type_filter !== '') {
    $sqlv = "SELECT id FROM problem_variants WHERE problem_slug LIKE CONCAT(?, '%')";
    $stv = $mysqli->prepare($sqlv);
    $stv->bind_param('s', $type_filter);
    $stv->execute();
    $ids_list = array();
    if (method_exists($stv, 'get_result')) {
        $rv = $stv->get_result();
        while ($r = $rv->fetch_assoc()) $ids_list[] = intval($r['id']);
    } else {
        // fallback
        $meta = $stv->result_metadata();
        $rows = array();
        if ($meta) {
            $row = array(); $bind = array();
            while ($field = $meta->fetch_field()) { $row[$field->name] = null; $bind[] = & $row[$field->name]; }
            call_user_func_array(array($stv, "bind_result"), $bind);
            while ($stv->fetch()) { $copy = array(); foreach ($row as $k => $v) $copy[$k] = $v; $rows[] = $copy; }
        }
        foreach ($rows as $r) $ids_list[] = intval($r['id']);
    }
    $stv->close();

    if (!empty($ids_list)) {
        $ph = implode(',', array_fill(0, count($ids_list), '?'));
        $where[] = "pa.problem_variant_id IN ($ph)";
        foreach ($ids_list as $vid) { $params[] = $vid; $types .= 'i'; }
    } else {
        $where[] = "1=0";
    }
}
if ($level_filter !== '') { $where[] = "pv.level = ?"; $params[] = $level_filter; $types .= 's'; }
if ($status_filter !== '') { $where[] = "pa.status = ?"; $params[] = $status_filter; $types .= 's'; }
$where_sql = implode(' AND ', $where);

/* TOTAL COUNT */
$sql_count = "SELECT COUNT(*) AS c
              FROM problem_attempts pa
              JOIN problem_variants pv ON pv.id = pa.problem_variant_id
              WHERE $where_sql";
$st = $mysqli->prepare($sql_count);
$bind_params = array(); $bind_params[] = & $types;
for ($i = 0; $i < count($params); $i++) $bind_params[] = & $params[$i];
call_user_func_array(array($st, 'bind_param'), $bind_params);
$st->execute();
if (method_exists($st, 'get_result')) {
    $count_row = $st->get_result()->fetch_assoc();
} else {
    // fallback
    $meta = $st->result_metadata();
    $row = array(); $bind = array();
    if ($meta) {
        while ($field = $meta->fetch_field()) { $row[$field->name] = null; $bind[] = & $row[$field->name]; }
        call_user_func_array(array($st, "bind_result"), $bind);
        if ($st->fetch()) $count_row = $row; else $count_row = array('c'=>0);
    } else $count_row = array('c'=>0);
}
$total = isset($count_row['c']) ? intval($count_row['c']) : 0;
$st->close();

/* FETCH PAGE */
$sql = "SELECT pa.*,
               pv.problem_slug,
               pv.level AS variant_level,
               pv.statement AS variant_statement,
               SUBSTRING_INDEX(pv.problem_slug, '-', 1) AS problem_type_slug,
               pr.criteria_text AS rubric_definition
        FROM problem_attempts pa
        JOIN problem_variants pv ON pv.id = pa.problem_variant_id
        LEFT JOIN problem_rubrics pr
               ON pr.problem_type_slug = SUBSTRING_INDEX(pv.problem_slug, '-', 1)
               AND pr.level = pv.level
        WHERE $where_sql
        ORDER BY pa.created_at DESC
        LIMIT ?, ?";
$st = $mysqli->prepare($sql);
$types2 = $types . 'ii';
$bind_params = array(); $bind_params[] = & $types2;
for ($i = 0; $i < count($params); $i++) $bind_params[] = & $params[$i];
$bind_params[] = & $offset; $bind_params[] = & $limit;
call_user_func_array(array($st, 'bind_param'), $bind_params);
$st->execute();
if (method_exists($st, 'get_result')) {
    $res = $st->get_result();
    $attempts = array();
    while ($r = $res->fetch_assoc()) $attempts[] = $r;
} else {
    // fallback
    $meta = $st->result_metadata();
    $row = array(); $bind = array(); $attempts = array();
    if ($meta) {
        while ($field = $meta->fetch_field()) { $row[$field->name] = null; $bind[] = & $row[$field->name]; }
        call_user_func_array(array($st, "bind_result"), $bind);
        while ($st->fetch()) { $copy = array(); foreach ($row as $k => $v) $copy[$k] = $v; $attempts[] = $copy; }
    }
}
$st->close();

/* Collect IDs for batch queries */
$attachments = array();
$ids = array();
foreach ($attempts as $a) $ids[] = intval($a['id']);

/* BATCH: attachments by attempt_id (safe to embed integers) */
if (!empty($ids)) {
    $in = implode(',', array_map('intval', $ids));
    $sqlA = "SELECT * FROM problem_attachments WHERE attempt_id IN ($in) AND is_deleted = 0";
    $rA = $mysqli->query($sqlA);
    if ($rA) {
        while ($ar = $rA->fetch_assoc()) $attachments[$ar['attempt_id']][] = $ar;
    }
}

/* BATCH: per-criterion scores from problem_attempt_scores */
$scores_by_attempt = array();
if (!empty($ids)) {
    $in = implode(',', array_map('intval', $ids));
    $sqlS = "SELECT attempt_id, rubric_criteria_label, max_score, score, comments
             FROM problem_attempt_scores
             WHERE attempt_id IN ($in)
             ORDER BY attempt_id ASC, id ASC";
    $rS = $mysqli->query($sqlS);
    if ($rS) {
        while ($row = $rS->fetch_assoc()) {
            $aid = intval($row['attempt_id']);
            $scores_by_attempt[$aid][] = array(
                'label' => $row['rubric_criteria_label'],
                'max' => isset($row['max_score']) ? floatval($row['max_score']) : 0.0,
                'score' => isset($row['score']) && $row['score'] !== null ? floatval($row['score']) : null,
                'comments' => isset($row['comments']) ? $row['comments'] : ''
            );
        }
    }
}

/* --- NEW: build header info for variant & level --- */
/* Priority:
   1) Explicit filters (type_filter, level_filter)
   2) If no filters and exactly one attempt returned, use that attempt's values
   3) Otherwise show "All"
*/
$header_variant = 'All';
$header_level = 'All';
if ($type_filter !== '') {
    $header_variant = $type_filter;
} elseif (count($attempts) === 1 && !empty($attempts[0]['problem_slug'])) {
    $header_variant = $attempts[0]['problem_slug'];
}
if ($level_filter !== '') {
    $header_level = $level_filter;
} elseif (count($attempts) === 1 && !empty($attempts[0]['variant_level'])) {
    $header_level = $attempts[0]['variant_level'];
}
$header_variant = esc($header_variant);
$header_level = esc(ucfirst($header_level)); // make level look nicer

/* Render page */
$page = "myAttempts";
require_once("learnerHead_Nav2.php");
?>
<div class="layout-page">
<?php require_once("learnersNav.php");  ?>
<div class="content-wrapper">
<div class="container-xxl container-p-y">

<!-- Header -->
<div class="card shadow-sm mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">My Attempts for <?php echo $header_variant; ?> (Level: <?php echo $header_level; ?>)</h3>
            <small class="text-muted">Your attempts, instructor grades & feedback, rubrics and files</small>
        </div>
        <a class="btn btn-outline-secondary btn-sm" href="problemSolving_skills.php">← Back</a>
    </div>
</div>

<!-- Filters (same as before) -->
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form class="row g-3" method="get">
            <div class="col-md-3">
                <label class="form-label small">Problem Type</label>
                <input name="type" class="form-control form-control-sm" value="<?php echo esc($type_filter); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Level</label>
                <select name="level" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="kid"   <?php echo $level_filter==="kid"?"selected":""; ?>>Kid</option>
                    <option value="teen"  <?php echo $level_filter==="teen"?"selected":""; ?>>Teen</option>
                    <option value="adult" <?php echo $level_filter==="adult"?"selected":""; ?>>Adult</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="draft"     <?php echo $status_filter==="draft"?"selected":""; ?>>Draft</option>
                    <option value="submitted" <?php echo $status_filter==="submitted"?"selected":""; ?>>Submitted</option>
                    <option value="graded"    <?php echo $status_filter==="graded"?"selected":""; ?>>Graded</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Per Page</label>
                <select name="limit" class="form-select form-select-sm">
                    <option <?php echo $limit==10?"selected":""; ?>>10</option>
                    <option <?php echo $limit==25?"selected":""; ?>>25</option>
                    <option <?php echo $limit==50?"selected":""; ?>>50</option>
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button class="btn btn-primary btn-sm">Apply</button>
            </div>
        </form>
    </div>
</div>

<!-- Attempts Table -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>Date</th>
                <th>Problem</th>
                <th style="text-align:right;">Score</th>
                <th>Evaluator</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
            </thead>
            <tbody>

<?php foreach ($attempts as $a):
    $aid = intval($a['id']);
    $files = isset($attachments[$aid]) ? $attachments[$aid] : array();
    $stored_score = ($a['score'] !== null && $a['score'] !== '') ? $a['score'] : null;
    $saved_rows = isset($scores_by_attempt[$aid]) ? $scores_by_attempt[$aid] : array();

    // If saved_rows present, compute totals & percentage from them; else fallback to stored attempt score
    $display_score = null;
    if (!empty($saved_rows)) {
        $sumScore = 0.0; $sumMax = 0.0;
        foreach ($saved_rows as $sr) {
            if ($sr['score'] !== null) $sumScore += floatval($sr['score']);
            if ($sr['max'] !== null) $sumMax += floatval($sr['max']);
        }
        if ($sumMax > 0) {
            $pct = round(($sumScore / $sumMax) * 100, 2);
            $display_score = $pct . '%';
        } else {
            $display_score = number_format($sumScore, 2);
        }
    } else {
        $display_score = ($stored_score !== null) ? (string)$stored_score . ' %' : null;
    }

    // plain instructor feedback (no RUBRIC_JSON)
    $instructor_feedback_clean = trim(isset($a['feedback']) ? $a['feedback'] : '');
?>
<tr>
    <td><?php echo esc(fmtDateBest($a)); ?></td>
    <td>
        <div class="fw-bold small"><?php echo esc($a['problem_slug']); ?> — <?php echo esc($a['variant_level']); ?></div>
        <div class="text-muted small"><?php echo esc(substr($a['variant_statement'],0,140)); ?><?php echo (strlen($a['variant_statement'])>140)?'...':''; ?></div>
    </td>
    <td style="text-align:right;"><?php echo ($display_score !== null ? esc($display_score) : '&mdash;'); ?></td>
    <td><?php echo esc(isset($a['evaluator_login']) && $a['evaluator_login'] ? $a['evaluator_login'] : '&mdash;'); ?></td>
    <td><?php echo esc(isset($a['status']) && $a['status'] ? $a['status'] : '&mdash;'); ?></td>
    <td><button class="btn btn-sm btn-outline-primary toggle" data-id="<?php echo $aid; ?>">View</button></td>
</tr>

<tr id="det-<?php echo $aid; ?>" style="display:none;">
    <td colspan="7" style="background:#fafafa;">
        <div class="p-3">
            <div class="fw-bold mb-1">Rubric / Scores</div>

            <?php if (!empty($saved_rows)): ?>
                <div class="mb-2">
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Criterion</th><th style="text-align:right;">Score</th><th style="text-align:right;">Max</th><th>Comments</th></tr></thead>
                        <tbody>
                        <?php foreach ($saved_rows as $sr): ?>
                            <tr>
                                <td><?php echo esc($sr['label']); ?></td>
                                <td style="text-align:right;"><?php echo ($sr['score'] !== null) ? esc(number_format($sr['score'], 2)) : '&mdash;'; ?></td>
                                <td style="text-align:right;"><?php echo ($sr['max'] !== null) ? esc(number_format($sr['max'], 2)) : '&mdash;'; ?></td>
                                <td><?php echo ($sr['comments'] !== '') ? nl2br(esc($sr['comments'])) : '<span class="text-muted">-</span>'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                    $sumScore = 0.0; $sumMax = 0.0;
                    foreach ($saved_rows as $sr) {
                        if ($sr['score'] !== null) $sumScore += floatval($sr['score']);
                        if ($sr['max'] !== null) $sumMax += floatval($sr['max']);
                    }
                ?>
                <div class="mb-2"><strong>Calculated total:</strong> <?php echo esc(number_format($sumScore,2)); ?></div>
                <div><strong>Max possible:</strong> <?php echo esc(number_format($sumMax,2)); ?></div>
                <div><strong>Percentage:</strong> <?php echo ($sumMax>0) ? esc(round(($sumScore/$sumMax)*100,2)) . '%' : '&mdash;'; ?></div>
                <div class="small text-muted mt-2">Stored attempt score: <?php echo ($stored_score !== null) ? esc($stored_score) . ' %' : '<em>not set</em>'; ?></div>

            <?php else: 
                $rubric_def = isset($a['rubric_definition']) ? parse_rubric_criteria_text($a['rubric_definition']) : array();
                if (!empty($rubric_def)): ?>
                    <div class="mb-2">
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Criterion</th><th style="text-align:right;">Max</th></tr></thead>
                            <tbody>
                            <?php foreach ($rubric_def as $entry): ?>
                                <tr>
                                    <td><?php echo esc(isset($entry['label']) ? $entry['label'] : ''); ?></td>
                                    <td style="text-align:right;"><?php echo esc(isset($entry['max']) ? $entry['max'] : ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="mb-3"><em>No rubric or scores available yet.</em></div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($instructor_feedback_clean)): ?>
                <div class="fw-bold mb-1">Instructor Feedback</div>
                <div class="mb-3" style="white-space:pre-wrap;"><?php echo esc($instructor_feedback_clean); ?></div>
            <?php endif; ?>

            <?php if (!empty($files)): ?>
                <div class="fw-bold mb-1">Attachments</div>
                <?php foreach ($files as $f): ?>
                    <div><a href="<?php echo esc($f['file_path']); ?>" target="_blank" rel="noopener"><?php echo esc(basename($f['file_path'])); ?></a></div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </td>
</tr>

<?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-3 d-flex justify-content-between">
        <span class="text-muted small">
            Showing <?php echo $offset + 1; ?> - <?php echo min($total, $offset + count($attempts)); ?> of <?php echo $total; ?>
        </span>

        <div class="btn-group">
            <?php
                $prev = max(1, $page - 1);
                $last = max(1, ceil($total / $limit));
                $next = ($page < $last) ? ($page + 1) : $last;
            ?>
            <a class="btn btn-sm btn-outline-secondary <?php echo ($page == 1 ? 'disabled' : ''); ?>"
               href="?<?php echo http_build_query(array_merge($_GET, array('page' => $prev))); ?>">Prev</a>

            <span class="btn btn-sm btn-light disabled"><?php echo $page . ' / ' . $last; ?></span>

            <a class="btn btn-sm btn-outline-secondary <?php echo ($page == $last ? 'disabled' : ''); ?>"
               href="?<?php echo http_build_query(array_merge($_GET, array('page' => $next))); ?>">Next</a>
        </div>
    </div>
</div>

</div>
</div>

<script>
(function(){
    var toggles = document.getElementsByClassName('toggle');
    for (var i = 0; i < toggles.length; i++) {
        (function(btn){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                var id = btn.getAttribute('data-id');
                var row = document.getElementById('det-' + id);
                if (!row) return;
                if (row.style.display === 'none' || row.style.display === '') {
                    row.style.display = 'table-row';
                    btn.textContent = 'Hide';
                } else {
                    row.style.display = 'none';
                    btn.textContent = 'View';
                }
            }, false);
        })(toggles[i]);
    }
})();
</script>

<style>
.rubric table { background: #fff; }
.rubric th { background: #f5f5f5; }
</style>

<?php /* end file */ ?>
