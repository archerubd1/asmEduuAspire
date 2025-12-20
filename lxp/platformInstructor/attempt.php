<?php
/**
 * platformInstructor/attempt.php
 * Instructor view for a single attempt — prefers problem_attempt_scores (no RUBRIC_JSON)
 * PHP 5.4 compatible
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

function is_instructor($user_login) {
    return true; // tighten in production
}

if (!is_instructor($session_user)) {
    http_response_code(403);
    die("Access denied.");
}

/* Helper: dynamic bind for PHP 5.4 */
function bindParamsDynamic($stmt, $types, $params)
{
    if (empty($params)) return true;
    $refs = array();
    foreach ($params as $k => $v) {
        $refs[$k] = &$params[$k];
    }
    array_unshift($refs, $types);
    return call_user_func_array(array($stmt, 'bind_param'), $refs);
}

/* Helper: parse criteria_text into array of items with label & max */
function parse_rubric_criteria_text($text) {
    $items = array();
    if ($text === null) return $items;

    $text_trim = trim($text);
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

/* Inputs */
$attempt_id = isset($_GET['attempt_id']) ? (int)$_GET['attempt_id'] : 0;
if ($attempt_id <= 0) {
    die("Invalid attempt id.");
}

/* Load attempt row + variant + problem type */
$sql = "
    SELECT pa.*, pv.id AS variant_id, pv.problem_slug, pv.problem_type_id, pv.level, pv.difficulty_score,
           pt.slug AS type_slug, pt.title AS type_title
    FROM problem_attempts pa
    LEFT JOIN problem_variants pv ON pa.problem_variant_id = pv.id
    LEFT JOIN problem_types pt ON pv.problem_type_id = pt.id
    WHERE pa.id = ?
    LIMIT 1
";
$stmt = $coni->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $coni->error . " SQL: " . $sql);
    die("DB error.");
}

bindParamsDynamic($stmt, 'i', array($attempt_id));
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    $stmt->close();
    die("DB error.");
}
$res = $stmt->get_result();
if ($res->num_rows == 0) {
    $stmt->close();
    die("Attempt not found.");
}
$attempt = $res->fetch_assoc();
$stmt->close();

/* Derived values */
$variant_id = (int)$attempt['variant_id'];
$type_id    = (int)$attempt['problem_type_id'];
$type_slug  = isset($attempt['type_slug']) ? $attempt['type_slug'] : '';
$type_title = isset($attempt['type_title']) ? $attempt['type_title'] : '';
$variant_level = isset($attempt['level']) ? $attempt['level'] : '';
$started_at = !empty($attempt['started_at']) && is_numeric($attempt['started_at']) ? date('Y-m-d H:i:s', (int)$attempt['started_at']) : '';
$submitted_at = !empty($attempt['submitted_at']) && is_numeric($attempt['submitted_at']) ? date('Y-m-d H:i:s', (int)$attempt['submitted_at']) : '';
$score = ($attempt['score'] !== null && $attempt['score'] !== '') ? $attempt['score'] : null;
$status = $attempt['status'];
$answer_text = isset($attempt['answer_text']) ? $attempt['answer_text'] : '';
$learner_login = isset($attempt['user_login']) ? $attempt['user_login'] : '';

/* Try to load learner profile (if users table exists and has name/email) */
$learner_name = '';
$learner_email = '';
$sqlU = "SELECT name, surname, email FROM users WHERE login = ? LIMIT 1";
$stmtU = $coni->prepare($sqlU);
if ($stmtU) {
    $stmtU->bind_param('s', $learner_login);
    if ($stmtU->execute()) {
        $resU = $stmtU->get_result();
        if ($resU && $resU->num_rows > 0) {
            $urow = $resU->fetch_assoc();
            $learner_name = (isset($urow['name']) ? $urow['name'] : '') . (isset($urow['surname']) ? ' ' . $urow['surname'] : '');
            $learner_email = isset($urow['email']) ? $urow['email'] : '';
        }
    }
    $stmtU->close();
}

/* Load attachments from problem_attachments table */
$attachments = array();
$sqlAttach = "
    SELECT id, file_path, mime_type, `hash`, created_at
    FROM problem_attachments
    WHERE attempt_id = ?
    ORDER BY id ASC
";
$stmt2 = $coni->prepare($sqlAttach);
if ($stmt2) {
    bindParamsDynamic($stmt2, 'i', array($attempt_id));
    if ($stmt2->execute()) {
        $resA = $stmt2->get_result();
        while ($row = $resA->fetch_assoc()) {
            $attachments[] = $row;
        }
    } else {
        error_log("Attachment query execute failed: " . $stmt2->error);
    }
    $stmt2->close();
} else {
    error_log("Attachment prepare failed: " . $coni->error . " SQL: " . $sqlAttach);
}

/* --- Load per-criterion saved rows from problem_attempt_scores (preferred) --- */
$saved_attempt_scores = array();
$sqlScores = "SELECT rubric_criteria_label, max_score, score, comments FROM problem_attempt_scores WHERE attempt_id = ? ORDER BY id ASC";
$stmtS = $coni->prepare($sqlScores);
if ($stmtS) {
    bindParamsDynamic($stmtS, 'i', array($attempt_id));
    if ($stmtS->execute()) {
        $resS = $stmtS->get_result();
        while ($r = $resS->fetch_assoc()) {
            $saved_attempt_scores[] = array(
                'label' => isset($r['rubric_criteria_label']) ? $r['rubric_criteria_label'] : '',
                'max'   => isset($r['max_score']) ? floatval($r['max_score']) : 0.0,
                'score' => isset($r['score']) && $r['score'] !== null ? floatval($r['score']) : null,
                'comments' => isset($r['comments']) ? $r['comments'] : ''
            );
        }
    } else {
        error_log("problem_attempt_scores execute failed: " . $stmtS->error);
    }
    $stmtS->close();
}

/* If no saved rows, load canonical rubric for the problem type & level */
$canonical_rubric = array();
if (empty($saved_attempt_scores) && $type_slug !== '' && $variant_level !== '') {
    $sqlR = "SELECT criteria_text FROM problem_rubrics WHERE problem_type_slug = ? AND level = ? ORDER BY id DESC LIMIT 1";
    $stmtR = $coni->prepare($sqlR);
    if ($stmtR) {
        $stmtR->bind_param('ss', $type_slug, $variant_level);
        if ($stmtR->execute()) {
            $resR = $stmtR->get_result();
            if ($resR && $resR->num_rows > 0) {
                $rrow = $resR->fetch_assoc();
                $criteria_text = isset($rrow['criteria_text']) ? $rrow['criteria_text'] : null;
                $parsed = parse_rubric_criteria_text($criteria_text);
                if (!empty($parsed)) {
                    $canonical_rubric = $parsed; // each item: ['label','max']
                }
            }
        } else {
            error_log("problem_rubrics execute failed: " . $stmtR->error);
        }
        $stmtR->close();
    } else {
        error_log("problem_rubrics prepare failed: " . $coni->error);
    }
}

/* Instructor feedback (plain field) */
$instructor_feedback = isset($attempt['feedback']) ? $attempt['feedback'] : '';

/* Render page */
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
            Attempts for Variant  <?php if ($type_title) echo "  " . htmlspecialchars($type_title); ?>
        </h3>
      </div>
    </div>
  </div>
</div>

<div class="row mb-2">
  <div class="col-md-3">
    <a href="problem-solving-skills.php" class="btn btn-secondary w-100"><i class="bx bx-arrow-back"></i> Back to Dashboard</a>
  </div>
  <div class="col-md-3">
    <?php if ($type_slug !== ''): ?>
    <a href="problem_type_admin.php?action=attempts&type=<?php echo htmlspecialchars(urlencode($type_slug)); ?>&variant_id=<?php echo (int)$variant_id; ?>" class="btn btn-outline-secondary w-100">Back to Attempts List</a>
    <?php else: ?>
    <a href="problem_type_admin.php?action=attempts" class="btn btn-outline-secondary w-100">Back to Attempts List</a>
    <?php endif; ?>
  </div>
  <div class="col-md-3 text-end">
    <a href="grade_attempt.php?attempt_id=<?php echo (int)$attempt_id; ?>" class="btn btn-success w-100">Grade Attempt</a>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">

    <h5>Learner</h5>
    <div class="mb-2">
        <strong>Login:</strong> <?php echo htmlspecialchars($learner_login); ?>
        <?php if ($learner_name): ?> | <strong>Name:</strong> <?php echo htmlspecialchars($learner_name); ?><?php endif; ?>
        <?php if ($learner_email): ?> | <strong>Email:</strong> <?php echo htmlspecialchars($learner_email); ?><?php endif; ?>
    </div>

    <h5>Metadata</h5>
    <div class="mb-2">
      <strong>Status:</strong> <?php echo htmlspecialchars($status); ?> |
      <strong>Score:</strong> <?php echo ($score !== null) ? htmlspecialchars($score) . ' %' : '-'; ?> |
      <strong>Started:</strong> <?php echo htmlspecialchars($started_at); ?> |
      <strong>Submitted:</strong> <?php echo htmlspecialchars($submitted_at); ?>
    </div>

    <h5 class="mt-3">Answer</h5>
    <div class="card card-body mb-3" style="white-space:pre-wrap;"><?php echo nl2br(htmlspecialchars($answer_text)); ?></div>

    <h5>Attached files</h5>
    <div class="mb-3">
    <?php if (!empty($attachments)): ?>
        <ul class="mb-0" id="attachments-list">
        <?php foreach ($attachments as $att):
            $fp = isset($att['file_path']) ? $att['file_path'] : '';
            $displayName = htmlspecialchars($fp);
            $downloadUrl = 'download_attachment.php?att_id=' . (int)$att['id'];
            $mime = isset($att['mime_type']) ? htmlspecialchars($att['mime_type']) : 'unknown';
            $hash = isset($att['hash']) ? htmlspecialchars($att['hash']) : '';
            $created = isset($att['created_at']) && is_numeric($att['created_at']) ? date('Y-m-d H:i:s', (int)$att['created_at']) : '';
        ?>
            <li id="att-<?php echo (int)$att['id']; ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <a href="<?php echo htmlspecialchars($downloadUrl); ?>" target="_blank" rel="noopener"><?php echo $displayName; ?></a>
                        <span class="text-muted small"> (<?php echo $mime; ?><?php echo $created ? ', ' . $created : ''; ?><?php echo $hash ? ', hash:' . $hash : ''; ?>)</span>
                    </div>
                    <div>
                        <a class="btn btn-sm btn-outline-primary me-1" href="<?php echo htmlspecialchars($downloadUrl); ?>" target="_blank" rel="noopener">
                            <i class="bx bx-download"></i> Download
                        </a>
                        <?php if (is_instructor($session_user)): ?>
                            <button class="btn btn-sm btn-outline-danger btn-remove-attachment" data-att-id="<?php echo (int)$att['id']; ?>">
                                <i class="bx bx-trash"></i> Remove
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <span class="text-muted">No files attached.</span>
    <?php endif; ?>
    </div>

    <h5>Instructor Feedback / Rubric</h5>

    <?php if (!empty($saved_attempt_scores)): ?>
        <!-- Show per-criterion rows stored in problem_attempt_scores -->
        <div class="mb-3">
            <strong>Saved per-criterion scores</strong>
            <div class="card card-body mb-2">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr><th>Criterion</th><th>Score</th><th>Max</th><th>Comments</th></tr>
                    </thead>
                    <tbody>
                    <?php
                        $sumScore = 0.0;
                        $sumMax = 0.0;
                        foreach ($saved_attempt_scores as $row):
                            $lab = isset($row['label']) ? $row['label'] : '';
                            $sc = isset($row['score']) && $row['score'] !== null ? $row['score'] : '';
                            $mx = isset($row['max']) ? $row['max'] : '';
                            $com = isset($row['comments']) ? $row['comments'] : '';
                            if ($sc !== '') $sumScore += floatval($sc);
                            if ($mx !== '') $sumMax += floatval($mx);
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($lab); ?></td>
                            <td><?php echo ($sc !== '') ? htmlspecialchars($sc) : '-'; ?></td>
                            <td><?php echo ($mx !== '') ? htmlspecialchars($mx) : '-'; ?></td>
                            <td><?php echo ($com !== '') ? nl2br(htmlspecialchars($com)) : '<span class="text-muted">-</span>'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="mt-2"><strong>Calculated total:</strong> <?php echo htmlspecialchars(number_format($sumScore, 2)); ?></div>
                <div><strong>Max possible:</strong> <?php echo htmlspecialchars(number_format($sumMax, 2)); ?></div>
                <div><strong>Percentage:</strong>
                    <?php
                        if ($sumMax > 0) { $pct = round(($sumScore / $sumMax) * 100, 2); echo htmlspecialchars($pct) . '%'; }
                        else { echo '-'; }
                    ?>
                </div>
                <div class="small text-muted mt-2">Overall stored attempt score: <?php echo ($score !== null) ? htmlspecialchars($score) . ' %' : '<em>not set</em>'; ?></div>
            </div>
        </div>

    <?php elseif (!empty($canonical_rubric)): ?>
        <!-- attempt not graded yet — show canonical rubric (labels + max) -->
        <div class="mb-3">
            <strong>Canonical rubric for this problem type (<?php echo htmlspecialchars($type_slug . ' / ' . $variant_level); ?>)</strong>
            <div class="card card-body mb-2">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr><th>Criterion</th><th>Max</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($canonical_rubric as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['label']); ?></td>
                            <td><?php echo htmlspecialchars($c['max']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="small text-muted">These are the default rubric criteria that will be used when grading. Click <a href="grade_attempt.php?attempt_id=<?php echo (int)$attempt_id; ?>">Grade Attempt</a> to apply scores.</div>
        </div>

    <?php else: ?>
        <div class="alert alert-info">No rubric defined for this problem type/level and this attempt has not been graded yet.</div>
    <?php endif; ?>

    <div class="card card-body">
        <?php if (!empty($instructor_feedback)): ?>
            <div><?php echo nl2br(htmlspecialchars($instructor_feedback)); ?></div>
        <?php else: ?>
            <div class="text-muted">No instructor feedback yet.</div>
        <?php endif; ?>
    </div>

  </div>
</div>

</div>
</div>

<?php require_once('../platformFooter.php'); ?>

<!-- SweetAlert + Remove AJAX -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function () {
    var removeBtns = document.querySelectorAll('.btn-remove-attachment');
    if (!removeBtns) return;

    function postJson(url, data, cb) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                var ok = xhr.status >= 200 && xhr.status < 300;
                var resp = null;
                try { resp = JSON.parse(xhr.responseText); } catch (e) { resp = null; }
                cb(ok, resp);
            }
        };
        var fd = new FormData();
        for (var k in data) if (data.hasOwnProperty(k)) fd.append(k, data[k]);
        xhr.send(fd);
    }

    Array.prototype.forEach.call(removeBtns, function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var attId = btn.getAttribute('data-att-id');
            if (!attId) return;

            Swal.fire({
                title: 'Remove attachment?',
                input: 'text',
                inputLabel: 'Reason (brief) — saved to audit log',
                inputPlaceholder: 'e.g. wrong file / PII present',
                showCancelButton: true,
                confirmButtonText: 'Remove',
                cancelButtonText: 'Cancel',
                preConfirm: function (reason) { if (reason === null) reason = ''; return reason; }
            }).then(function (res) {
                if (!res.isConfirmed) return;
                var reason = res.value || 'removed_by_instructor';
                postJson('remove_attachment.php', { att_id: attId, reason: reason }, function (ok, resp) {
                    if (ok && resp && resp.ok) {
                        var li = document.getElementById('att-' + attId);
                        if (li) {
                            li.classList.add('text-muted', 'opacity-50');
                            var actions = li.querySelector('div > div:last-child');
                            if (actions) { actions.innerHTML = ''; 
                                var undoBtn = document.createElement('button');
                                undoBtn.className = 'btn btn-sm btn-outline-secondary btn-undo-attachment';
                                undoBtn.textContent = 'Undo';
                                undoBtn.setAttribute('data-att-id', attId);
                                actions.appendChild(undoBtn);
                            }
                            Swal.fire({
                                title: 'Attachment removed',
                                text: 'You can undo within ' + (resp && resp.undo_window ? resp.undo_window : '30') + ' seconds.',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Undo',
                                cancelButtonText: 'Close'
                            }).then(function (choice) {
                                if (choice.isConfirmed) {
                                    postJson('undo_attachment.php', { att_id: attId }, function (ok2, resp2) {
                                        if (ok2 && resp2 && resp2.ok) {
                                            if (li) {
                                                li.classList.remove('text-muted', 'opacity-50');
                                                var actions2 = li.querySelector('div > div:last-child');
                                                if (actions2) {
                                                    actions2.innerHTML = '<a class="btn btn-sm btn-outline-primary me-1" href="download_attachment.php?att_id=' + attId + '" target="_blank">Download</a>' +
                                                                         '<button class="btn btn-sm btn-outline-danger btn-remove-attachment" data-att-id="' + attId + '">Remove</button>';
                                                    var newRemove = actions2.querySelector('.btn-remove-attachment');
                                                    if (newRemove) newRemove.addEventListener('click', function(ev){ ev.preventDefault(); /* no-op here */ this.click(); });
                                                }
                                            }
                                            Swal.fire({ icon: 'success', title: 'Undo successful' });
                                        } else {
                                            Swal.fire({ icon: 'error', title: 'Undo failed', text: (resp2 && resp2.msg) ? resp2.msg : 'Could not undo' });
                                        }
                                    });
                                }
                            });
                        }
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: (resp && resp.msg) ? resp.msg : 'Failed to remove' });
                    }
                });
            });
        }, false);
    });

    // delegation for undo buttons added dynamically
    document.addEventListener('click', function (e) {
        var el = e.target;
        if (el && el.classList && el.classList.contains('btn-undo-attachment')) {
            var attId = el.getAttribute('data-att-id');
            if (!attId) return;
            e.preventDefault();
            postJson('undo_attachment.php', { att_id: attId }, function (ok, resp) {
                var li = document.getElementById('att-' + attId);
                if (ok && resp && resp.ok) {
                    if (li) {
                        li.classList.remove('text-muted', 'opacity-50');
                        var actions = li.querySelector('div > div:last-child');
                        if (actions) {
                            actions.innerHTML = '<a class="btn btn-sm btn-outline-primary me-1" href="download_attachment.php?att_id=' + attId + '" target="_blank">Download</a>' +
                                                '<button class="btn btn-sm btn-outline-danger btn-remove-attachment" data-att-id="' + attId + '">Remove</button>';
                            var newRemove = actions.querySelector('.btn-remove-attachment');
                            if (newRemove) newRemove.addEventListener('click', function(ev){ ev.preventDefault(); /* no-op */ this.click(); });
                        }
                    }
                    Swal.fire({ icon: 'success', title: 'Undo successful' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Undo failed', text: (resp && resp.msg) ? resp.msg : 'Could not undo' });
                }
            });
        }
    }, false);

})();
</script>

</div>
