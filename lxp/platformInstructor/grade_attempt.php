<?php
// File: platformInstructor/grade_attempt.php
/**
 * grade_attempt.php — relational storage for rubric scores in problem_attempt_scores
 * Compatible with PHP 5.4 / older mysqli: uses autocommit(FALSE) fallback
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}
$session_user = $_SESSION['phx_user_login'];

/* IMPORTANT: tighten in production */
function is_instructor($user_login) {
    return true;
}
if (!is_instructor($session_user)) {
    http_response_code(403);
    die("Access denied.");
}

/* Input */
$attempt_id = isset($_GET['attempt_id']) ? (int)$_GET['attempt_id'] : 0;
if ($attempt_id <= 0) die("Invalid attempt id.");

/* Load attempt + variant + problem type slug */
$sql = "SELECT pa.id, pa.user_login, pa.problem_variant_id, pa.status, pa.score, pa.feedback,
               pv.problem_slug, pv.level AS variant_level, pv.problem_type_id,
               pt.title AS type_title, pt.slug AS type_slug
        FROM problem_attempts pa
        LEFT JOIN problem_variants pv ON pa.problem_variant_id = pv.id
        LEFT JOIN problem_types pt ON pv.problem_type_id = pt.id
        WHERE pa.id = ? LIMIT 1";
$stmt = $coni->prepare($sql);
if (!$stmt) { error_log("prepare failed: " . $coni->error); die("Server error."); }
$stmt->bind_param('i', $attempt_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) { $stmt->close(); die("Attempt not found."); }
$attempt = $res->fetch_assoc();
$stmt->close();

/* Learner info */
$learner_login = isset($attempt['user_login']) ? $attempt['user_login'] : '';
$learner_name = '';
$learner_surname = '';
$learner_email = '';
$sqlU = "SELECT name, surname, email FROM users WHERE login = ? LIMIT 1";
$stmtU = $coni->prepare($sqlU);
if ($stmtU) {
    $stmtU->bind_param('s', $learner_login);
    if ($stmtU->execute()) {
        $resU = $stmtU->get_result();
        if ($resU && $resU->num_rows > 0) {
            $urow = $resU->fetch_assoc();
            $learner_name = isset($urow['name']) ? $urow['name'] : '';
            $learner_surname = isset($urow['surname']) ? $urow['surname'] : '';
            $learner_email = isset($urow['email']) ? $urow['email'] : '';
        }
    }
    $stmtU->close();
}

/* Attempt-derived values */
$type_slug = isset($attempt['type_slug']) ? $attempt['type_slug'] : '';
$variant_level = isset($attempt['variant_level']) ? $attempt['variant_level'] : '';

/* Helper: parse criteria_text into array of items with label & max */
function parse_rubric_criteria_text($text) {
    $items = array();
    if ($text === null) return $items;

    $text_trim = trim($text);
    // Try JSON first
    if ($text_trim !== '' && ($text_trim[0] === '[' || $text_trim[0] === '{')) {
        $decoded = json_decode($text_trim, true);
        if (is_array($decoded)) {
            foreach ($decoded as $entry) {
                if (is_string($entry)) {
                    $label = trim($entry);
                    if ($label !== '') $items[] = array('label' => $label, 'max' => 5.0);
                } elseif (is_array($entry)) {
                    $label = isset($entry['label']) ? trim($entry['label']) : '';
                    $max = isset($entry['max']) && is_numeric($entry['max']) ? floatval($entry['max']) : 5.0;
                    if ($label !== '') $items[] = array('label' => $label, 'max' => $max);
                }
            }
            return $items;
        }
    }

    // Fallback: newline-separated plain text (one label per line)
    $lines = preg_split('/\r\n|\r|\n/', $text_trim);
    foreach ($lines as $ln) {
        $lab = trim($ln);
        if ($lab === '') continue;
        if (strpos($lab, '::') !== false) {
            list($labPart, $maxPart) = array_map('trim', explode('::', $lab, 2));
            $max = is_numeric($maxPart) ? floatval($maxPart) : 5.0;
            if ($labPart !== '') $items[] = array('label' => $labPart, 'max' => $max);
        } else {
            $items[] = array('label' => $lab, 'max' => 5.0);
        }
    }
    return $items;
}

/* Build rubric_items from DB (problem_rubrics) if present; fallback to hard-coded 7 items */
$rubric_from_db = null;
if ($type_slug !== '' && $variant_level !== '') {
    $sqlR = "SELECT id, criteria_text FROM problem_rubrics
             WHERE problem_type_slug = ? AND level = ?
             ORDER BY id DESC LIMIT 1";
    $stmtR = $coni->prepare($sqlR);
    if ($stmtR) {
        $stmtR->bind_param('ss', $type_slug, $variant_level);
        if ($stmtR->execute()) {
            $resR = $stmtR->get_result();
            if ($resR && $resR->num_rows > 0) {
                $rrow = $resR->fetch_assoc();
                $rubric_from_db = isset($rrow['criteria_text']) ? $rrow['criteria_text'] : null;
            }
        } else {
            error_log("problem_rubrics execute failed: " . $stmtR->error);
        }
        $stmtR->close();
    } else {
        error_log("problem_rubrics prepare failed: " . $coni->error);
    }
}

$rubric_items = array();
if ($rubric_from_db !== null) {
    $parsed = parse_rubric_criteria_text($rubric_from_db);
    if (!empty($parsed)) {
        foreach ($parsed as $p) {
            $lab = isset($p['label']) ? $p['label'] : '';
            $max = isset($p['max']) ? floatval($p['max']) : 5.0;
            if ($lab === '') continue;
            $rubric_items[] = array('label' => $lab, 'score' => 0.0, 'max' => $max);
        }
    }
}

/* Hard-coded fallback */
if (empty($rubric_items)) {
    $rubric_items = array(
        array('label' => 'Problem Understanding', 'score' => 0.0, 'max' => 5.0),
        array('label' => 'Reasoning & Logic', 'score' => 0.0, 'max' => 5.0),
        array('label' => 'Concept Application', 'score' => 0.0, 'max' => 5.0),
        array('label' => 'Solution Quality', 'score' => 0.0, 'max' => 5.0),
        array('label' => 'Presentation / Structure', 'score' => 0.0, 'max' => 5.0),
        array('label' => 'Depth of Explanation', 'score' => 0.0, 'max' => 5.0),
        array('label' => 'Creativity (Out-of-box)', 'score' => 0.0, 'max' => 5.0)
    );
}

/* Preserve the canonical default list */
$default_rubric = $rubric_items;

/* Load saved per-criterion rows from problem_attempt_scores and merge */
$saved_rows = array();
$sqlS = "SELECT rubric_criteria_label, max_score, score, comments FROM problem_attempt_scores WHERE attempt_id = ?";
$stmtS = $coni->prepare($sqlS);
if ($stmtS) {
    $stmtS->bind_param('i', $attempt_id);
    if ($stmtS->execute()) {
        $resS = $stmtS->get_result();
        while ($r = $resS->fetch_assoc()) {
            $lbl = isset($r['rubric_criteria_label']) ? $r['rubric_criteria_label'] : '';
            if ($lbl === '') continue;
            $saved_rows[] = array(
                'label' => $lbl,
                'max' => isset($r['max_score']) ? floatval($r['max_score']) : 5.0,
                'score' => isset($r['score']) ? (is_null($r['score']) ? 0.0 : floatval($r['score'])) : 0.0,
                'comments' => isset($r['comments']) ? $r['comments'] : ''
            );
        }
    } else {
        error_log("problem_attempt_scores execute failed: " . $stmtS->error);
    }
    $stmtS->close();
}

/* Merge saved rows into default_rubric: fill scores for matching labels (case-insensitive),
   append any saved criteria not present in defaults as editable extras */
if (!empty($saved_rows)) {
    $default_map = array();
    foreach ($default_rubric as $i => $d) {
        $default_map[strtolower(trim($d['label']))] = $i;
    }
    $extras = array();
    foreach ($saved_rows as $sr) {
        $key = strtolower(trim($sr['label']));
        if (isset($default_map[$key])) {
            $idx = $default_map[$key];
            $default_rubric[$idx]['score'] = isset($sr['score']) ? floatval($sr['score']) : 0.0;
            if (isset($sr['max']) && is_numeric($sr['max']) && floatval($sr['max']) > 0) {
                $default_rubric[$idx]['max'] = floatval($sr['max']);
            }
        } else {
            $extras[] = array(
                'label' => $sr['label'],
                'score' => isset($sr['score']) ? floatval($sr['score']) : 0.0,
                'max' => isset($sr['max']) ? floatval($sr['max']) : 5.0,
                'comments' => isset($sr['comments']) ? $sr['comments'] : ''
            );
        }
    }
    foreach ($extras as $e) $default_rubric[] = $e;
}

/* Prepopulate instructor feedback text (plain text) */
$existing_feedback_text = '';
if (!empty($attempt['feedback'])) {
    if (strpos($attempt['feedback'], 'RUBRIC_JSON:') === 0) {
        $parts = explode("\n\nInstructor feedback:\n", $attempt['feedback'], 2);
        $existing_feedback_text = isset($parts[1]) ? $parts[1] : '';
    } else {
        $existing_feedback_text = $attempt['feedback'];
    }
}

/* If instructor posts grading */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $posted_labels = isset($_POST['label']) && is_array($_POST['label']) ? $_POST['label'] : array();
    $posted_scores = isset($_POST['score']) && is_array($_POST['score']) ? $_POST['score'] : array();
    $posted_maxes  = isset($_POST['max']) && is_array($_POST['max']) ? $_POST['max'] : array();
    $instructor_text = isset($_POST['instructor_feedback']) ? trim($_POST['instructor_feedback']) : '';

    // Build criteria_order, compute totals
    $criteria_order = array();
    $total = 0.0;
    $max_total = 0.0;
    for ($i = 0; $i < count($posted_labels); $i++) {
        $lab = trim((string)$posted_labels[$i]);
        if ($lab === '') continue;
        $rawscore = isset($posted_scores[$i]) && is_numeric($posted_scores[$i]) ? floatval($posted_scores[$i]) : 0.0;
        $rawmax = isset($posted_maxes[$i]) && is_numeric($posted_maxes[$i]) ? floatval($posted_maxes[$i]) : 5.0;
        if ($rawmax <= 0) $rawmax = 1.0;
        if ($rawscore < 0) $rawscore = 0.0;
        if ($rawscore > $rawmax) $rawscore = $rawmax;
        if ($rawmax > 1000) $rawmax = 1000;
        $criteria_order[] = array('label' => $lab, 'score' => $rawscore, 'max' => $rawmax);
        $total += $rawscore;
        $max_total += $rawmax;
    }

    $percentage = ($max_total > 0) ? round(($total / $max_total) * 100, 2) : 0.0;
    $now = time();

    /* Transactional save: delete existing then insert new rows
       Use autocommit(FALSE) fallback for older PHP/MySQLi (PHP 5.4) */
    $tx_started = false;
    if (method_exists($coni, 'begin_transaction')) {
        if (!$coni->begin_transaction()) { error_log("begin_transaction failed: " . $coni->error); die("Server error."); }
        $tx_started = true;
    } else {
        if (!$coni->autocommit(FALSE)) { error_log("autocommit(FALSE) failed: " . $coni->error); die("Server error."); }
        $tx_started = true;
    }

    try {
        // delete existing
        $delSql = "DELETE FROM problem_attempt_scores WHERE attempt_id = ?";
        $delSt = $coni->prepare($delSql);
        if (!$delSt) throw new Exception("prepare delete failed: " . $coni->error);
        $delSt->bind_param('i', $attempt_id);
        if (!$delSt->execute()) throw new Exception("delete execute failed: " . $delSt->error);
        $delSt->close();

        // insert new rows
        $insSql = "INSERT INTO problem_attempt_scores (attempt_id, rubric_criteria_label, max_score, score, comments, created_at, updated_at)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insSt = $coni->prepare($insSql);
        if (!$insSt) throw new Exception("prepare insert failed: " . $coni->error);

        foreach ($criteria_order as $c) {
            $label = $c['label'];
            $max = $c['max'];
            $score_val = $c['score'];
            $comments = ''; // keep empty string unless you add comments[] to the form
            $created = $now;
            $updated = $now;
            // types: i (attempt_id), s (label), d (max_score), d (score), s (comments), i (created_at), i (updated_at)
            if (!$insSt->bind_param('isddsii', $attempt_id, $label, $max, $score_val, $comments, $created, $updated)) {
                throw new Exception("bind_param failed: " . $insSt->error);
            }
            if (!$insSt->execute()) throw new Exception("insert execute failed: " . $insSt->error);
        }
        $insSt->close();

        // update problem_attempts table: score and feedback
        $sqlUp = "UPDATE problem_attempts SET score = ?, feedback = ?, updated_at = ? WHERE id = ? LIMIT 1";
        $stmtUp = $coni->prepare($sqlUp);
        if (!$stmtUp) throw new Exception("prepare update attempt failed: " . $coni->error);
        $stmtUp->bind_param('dsii', $percentage, $instructor_text, $now, $attempt_id);
        if (!$stmtUp->execute()) throw new Exception("update execute failed: " . $stmtUp->error);
        $stmtUp->close();

        // audit grading action
        $insSql2 = "INSERT INTO problem_attachments_audit (attachment_id, attempt_id, actor, action, reason, created_at)
                    VALUES (0, ?, ?, 'grade', ?, ?)";
        $insSt2 = $coni->prepare($insSql2);
        if ($insSt2) {
            $reason = 'graded_by:' . $session_user;
            $insSt2->bind_param('isss', $attempt_id, $session_user, $reason, $now);
            $insSt2->execute();
            $insSt2->close();
        }

        // commit and restore autocommit
        if (method_exists($coni, 'commit')) { $coni->commit(); } else { $coni->commit(); }
        if (method_exists($coni, 'autocommit')) { $coni->autocommit(TRUE); }

    } catch (Exception $e) {
        if (method_exists($coni, 'rollback')) { $coni->rollback(); }
        if (method_exists($coni, 'autocommit')) { $coni->autocommit(TRUE); }
        error_log("grading save failed: " . $e->getMessage());
        die("Server error while saving grade.");
    }

    header("Location: attempt.php?attempt_id={$attempt_id}&msg=" . urlencode(base64_encode("Graded successfully")));
    exit;
}

/* Render page (UI preserved) */
$page = "problemSolvingInstructor";
require_once('instructorHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
         <i class="bx bx-user-check me-2"></i>
            Grading for Variant  <?php if ($type_slug) echo "  " . htmlspecialchars($type_slug); ?>
        </h3>
             <p>
  Variant: <?php echo htmlspecialchars($attempt['problem_slug']); ?> |
  Learner: <strong><?php echo htmlspecialchars($learner_login); ?></strong>
  <?php if ($learner_name || $learner_surname): ?>
    | Name: <strong><?php echo htmlspecialchars(trim($learner_name . ' ' . $learner_surname)); ?></strong>
  <?php endif; ?>
  <?php if ($learner_email): ?> | Email: <?php echo htmlspecialchars($learner_email); ?><?php endif; ?>
  <?php if ($type_slug): ?> | Rubric: <strong><?php echo htmlspecialchars($type_slug); ?> / <?php echo htmlspecialchars($variant_level); ?></strong><?php endif; ?>
</p>
        </h3>
      </div>
    </div>
  </div>
</div>

<form method="POST" action="grade_attempt.php?attempt_id=<?php echo (int)$attempt_id; ?>">
  <div class="card mb-3">
    <div class="card-body">
      <h5>Rubric (default criteria from DB are fixed; you may add extra criteria)</h5>

      <div id="rubric-items">
        <?php
        // Render default (fixed) rubric rows first; label & max disabled if original DB list
        foreach ($default_rubric as $idx => $def):
            $label = isset($def['label']) ? $def['label'] : '';
            $maxv = isset($def['max']) ? $def['max'] : 5.0;
            $prefScore = isset($def['score']) ? $def['score'] : 0.0;
            $is_fixed = false;
            if ($rubric_from_db !== null) {
                $parsed_db = parse_rubric_criteria_text($rubric_from_db);
                foreach ($parsed_db as $pdb) { if (strtolower(trim($pdb['label'])) === strtolower(trim($label))) { $is_fixed = true; break; } }
            } else {
                $hard_labels = array_map(function($x){return strtolower(trim($x['label']));}, $rubric_items);
                if (in_array(strtolower(trim($label)), $hard_labels)) $is_fixed = true;
            }
        ?>
        <div class="row align-items-center mb-2 rubric-row <?php echo $is_fixed ? '' : 'extra-row'; ?>">
          <div class="col-5">
            <?php if ($is_fixed): ?>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($label); ?>" disabled>
              <input type="hidden" name="label[]" value="<?php echo htmlspecialchars($label); ?>">
            <?php else: ?>
              <input type="text" name="label[]" class="form-control" value="<?php echo htmlspecialchars($label); ?>">
            <?php endif; ?>
          </div>
          <div class="col-3">
            <input type="number" step="0.1" min="0" max="<?php echo (float)$maxv; ?>" name="score[]" class="form-control score-input" value="<?php echo htmlspecialchars($prefScore); ?>">
          </div>
          <div class="col-2">
            <?php if ($is_fixed): ?>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($maxv); ?>" disabled>
              <input type="hidden" name="max[]" value="<?php echo htmlspecialchars($maxv); ?>">
            <?php else: ?>
              <input type="number" step="0.1" min="0.1" name="max[]" class="form-control max-input" value="<?php echo htmlspecialchars($maxv); ?>">
            <?php endif; ?>
          </div>
          <div class="col-2 small text-muted">Max</div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-3">
        <button type="button" id="add-row" class="btn btn-sm btn-outline-secondary">Add extra criterion</button>
      </div>

      <div class="mt-3">
        <strong>Total:</strong> <span id="rubric-total">0</span>
        &nbsp;|&nbsp;
        <strong>Percentage:</strong> <span id="rubric-percent">0.00</span>%
      </div>

    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <h5>Instructor feedback (optional)</h5>
      <textarea name="instructor_feedback" class="form-control" rows="6"><?php echo htmlspecialchars($existing_feedback_text); ?></textarea>
    </div>
  </div>

  <div class="d-flex justify-content-end">
    <a href="attempt.php?attempt_id=<?php echo (int)$attempt_id; ?>" class="btn btn-outline-secondary me-2">Back</a>
    <button type="submit" class="btn btn-success">Save Grade</button>
  </div>
</form>

</div>
</div>

<?php require_once('../platformFooter.php'); ?>

<!-- JS: auto-calc totals, add extra row; default labels & max are disabled and posted via hidden inputs -->
<script>
(function(){
    function clamp(v, lo, hi) {
        if (isNaN(v)) return lo;
        if (v < lo) return lo;
        if (v > hi) return hi;
        return v;
    }

    function recalc() {
        var total=0, maxTotal=0;
        var rows = document.querySelectorAll('#rubric-items .rubric-row');
        for (var i=0;i<rows.length;i++) {
            var sc = rows[i].querySelector('.score-input');
            var mx = rows[i].querySelector('.max-input');
            var m = 0;
            if (mx) {
                m = parseFloat(mx.value);
            } else {
                var hiddenMax = rows[i].querySelector('input[type="hidden"][name="max[]"]');
                if (hiddenMax) m = parseFloat(hiddenMax.value);
            }
            var s = sc ? parseFloat(sc.value) : 0;
            if (isNaN(s)) s = 0;
            if (isNaN(m) || m <= 0) m = 1;
            s = clamp(s, 0, m);
            if (sc) sc.value = s;
            if (mx) mx.value = m;
            total += s;
            maxTotal += m;
        }
        var pct = (maxTotal>0) ? ((total/maxTotal)*100) : 0;
        document.getElementById('rubric-total').textContent = total.toFixed(2);
        document.getElementById('rubric-percent').textContent = pct.toFixed(2);
    }

    document.getElementById('rubric-items').addEventListener('input', recalc, false);

    document.getElementById('add-row').addEventListener('click', function(){
        var container = document.getElementById('rubric-items');
        var div = document.createElement('div');
        div.className = 'row align-items-center mb-2 rubric-row extra-row';
        div.innerHTML = '<div class="col-5"><input type="text" name="label[]" class="form-control" value=""></div>' +
                        '<div class="col-3"><input type="number" step="0.1" min="0" name="score[]" class="form-control score-input" value="0"></div>' +
                        '<div class="col-2"><input type="number" step="0.1" min="0.1" name="max[]" class="form-control max-input" value="5"></div>' +
                        '<div class="col-2 small text-muted">Max</div>';
        container.appendChild(div);
        recalc();
    }, false);

    // initial calc on load
    recalc();
})();
</script>

</div>
</div>
