<?php
/**
 * ct_admin.php
 * Critical Thinking Instructor Administration (router)
 * Mirrors problem_type_admin.php style (dashboard, list, create, edit, attempts, analytics)
 * PHP 5.4 compatible; uses $coni (mysqli) from config.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

$page = "criticalThinkingAdmin";
require_once('instructorHead_Nav2.php');

/* --------- read params --------- */
$action = isset($_GET['action']) ? trim($_GET['action']) : 'dashboard';
$type_slug = isset($_GET['type']) ? trim($_GET['type']) : '';

/* find mysqli connection ($coni etc.) */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

if (empty($mysqli) || !($mysqli instanceof mysqli)) {
    die("Database connection not found. Ensure config.php defines \$coni (mysqli).");
}

/* CT type mapping (make available everywhere) */
$mapping = array(
    'fact_vs_opinion' => 'Fact vs Opinion',
    'coffee_chat'     => 'Coffee House Chat',
    'worldly_words'   => 'Worldly Words',
    'alien_guide'     => 'Alien Travel Guide',
    'talk_it_out'     => 'Talk It Out',
    'elevator_pitch'  => 'Elevator Pitch'
);

/* sanitize type_slug for SQL where used directly */
if ($type_slug !== '') $type_slug = mysqli_real_escape_string($mysqli, $type_slug);

/* fetch type info if provided */
$type_title = '';
if ($type_slug !== '') {
    if (isset($mapping[$type_slug])) $type_title = $mapping[$type_slug];
    else $type_title = $type_slug;
}

/* Helper: bind params for mysqli stmt (PHP 5.4 compatible) */
function bindParamsDynamic($stmt, $types, $params) {
    if (empty($params)) return true;
    $refs = array();
    foreach ($params as $k => $v) {
        $refs[$k] = & $params[$k];
    }
    array_unshift($refs, $types);
    return call_user_func_array(array($stmt, 'bind_param'), $refs);
}

/* ----------------------------
   ACTION: dashboard (overview)
   ---------------------------- */
if ($action == 'dashboard') :
    // show all CT types with counts (statements + pending reviews)
    $types = array('fact_vs_opinion','coffee_chat','worldly_words','alien_guide','talk_it_out','elevator_pitch');

    $tiles = array();
    foreach ($types as $t) {
        $subassignment = $t . '_main'; // convention used in your JS

        // statements count
        $statements = 0;
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM ct_statements WHERE subassignment_id = ?");
        if ($stmt) {
            $stmt->bind_param('s', $subassignment);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $statements = isset($row['c']) ? (int)$row['c'] : 0;
            }
            $stmt->close();
        }

        // pending review queue count
        $pending = 0;
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM ct_review_queue WHERE subassignment_id = ? AND status = 'queued'");
        if ($stmt) {
            $stmt->bind_param('s', $subassignment);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $pending = isset($row['c']) ? (int)$row['c'] : 0;
            }
            $stmt->close();
        }

        $tiles[$t] = array('statements' => $statements, 'pending' => $pending);
    }
?>
<div class="layout-page">
<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-3">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-body d-flex flex-column flex-md-row align-items-center">
            <div>
              <h1 class="mb-1 h4">Critical Thinking - Instructor Dashboard</h1>
              <p class="mb-0 text-muted">Manage statements, review submissions (autoscore + override), and create new items.</p>
            </div>
            <div class="ms-auto text-center">
              <img src="../assets/img/ctimage.png" height="90" alt="Critical thinking illustration">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tiles -->
    <div class="row g-3">
      <?php foreach ($tiles as $slugKey => $counts):
          $title = isset($mapping[$slugKey]) ? $mapping[$slugKey] : $slugKey;
      ?>
      <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center">
          <h6><?php echo htmlspecialchars($title); ?></h6>
          <div class="mt-2">
            <a href="ct_admin.php?action=list&type=<?php echo urlencode($slugKey); ?>" class="badge bg-primary" style="cursor:pointer;">
              <?php echo (int)$counts['statements']; ?> Statements
            </a>
            <a href="ct_admin.php?action=attempts&type=<?php echo urlencode($slugKey); ?>" class="badge bg-warning" style="cursor:pointer;">
              <?php echo (int)$counts['pending']; ?> To Review
            </a>
          </div>
          <div class="mt-3">
            <a href="ct_admin.php?action=create&type=<?php echo urlencode($slugKey); ?>" class="btn btn-success btn-sm">Create Statement</a>
            <a href="ct_admin.php?action=list&type=<?php echo urlencode($slugKey); ?>" class="btn btn-primary btn-sm">Manage Statements</a>
            <a href="ct_admin.php?action=attempts&type=<?php echo urlencode($slugKey); ?>" class="btn btn-warning btn-sm">Review Submissions</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<?php
require_once('../platformFooter.php');
?>
</div>
<?php
endif; // dashboard


/* ----------------------------
   ACTION: list (statements for type)
   ---------------------------- */
if ($action == 'list' && $type_slug !== ''):

    $subassignment_id = $type_slug . '_main';

    // fetch statements
    $stmt = $mysqli->prepare("SELECT id, level, statement, sort_order, is_active, created_at FROM ct_statements WHERE subassignment_id = ? ORDER BY sort_order ASC");
    $statements = array();
    if ($stmt) {
        $stmt->bind_param('s', $subassignment_id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $statements[] = $r;
        }
        $stmt->close();
    }
?>
<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row mb-3">
      <div class="col-12">
        <div class="card">
          <div class="card-body d-flex justify-content-between align-items-start">
            <div>
              <h3 class="mb-0">Statements — <?php echo htmlspecialchars($type_title); ?></h3>
              <p class="text-muted mb-0">Create, edit, reorder, and toggle statements for this assignment type.</p>
            </div>
            <div>
              <a href="ct_admin.php?action=create&type=<?php echo urlencode($type_slug); ?>" class="btn btn-success">Add New Statement</a>
              <a href="ct_admin.php?action=dashboard" class="btn btn-secondary">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <?php if (empty($statements)): ?>
          <div class="text-center text-muted">No statements yet. Use "Add New Statement".</div>
        <?php else: ?>
          <div class="list-group">
            <?php foreach ($statements as $s): ?>
              <div class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                  <div><strong>[<?php echo htmlspecialchars($s['level']); ?>]</strong> <?php echo htmlspecialchars(strlen($s['statement'])>180 ? substr($s['statement'],0,180).'...' : $s['statement']); ?></div>
                  <small class="text-muted">#<?php echo (int)$s['id']; ?> &nbsp; • &nbsp; Created: <?php echo htmlspecialchars($s['created_at']); ?></small>
                </div>
                <div class="btn-group">
                  <a href="ct_admin.php?action=edit&type=<?php echo urlencode($type_slug); ?>&id=<?php echo (int)$s['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
<?php require_once('../platformFooter.php'); ?>
</div>
<?php
endif; // list

/* ----------------------------
   ACTION: create (new statement form)
   ---------------------------- */
if ($action == 'create' && $type_slug !== ''):
?>
<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row mb-3">
      <div class="col-12">
        <div class="card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h3 class="mb-0">Create Statement — <?php echo htmlspecialchars($type_title); ?></h3>
              <p class="text-muted mb-0">Add a new statement for Kid / Teen / Adult level.</p>
            </div>
            <div>
              <a href="ct_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>" class="btn btn-secondary">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form method="POST" action="ct_admin.php?action=save_new&type=<?php echo urlencode($type_slug); ?>">
          <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-control" required>
              <option value="">-- Select Level --</option>
              <option value="kid">Kid</option>
              <option value="teen">Teen</option>
              <option value="adult">Adult</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Statement Text</label>
            <textarea name="statement" rows="4" class="form-control" required></textarea>
          </div>

          <button type="submit" class="btn btn-success">Save Statement</button>
        </form>
      </div>
    </div>

  </div>
</div>
<?php require_once('../platformFooter.php'); ?>
</div>
<?php
endif; // create

/* ----------------------------
   ACTION: save_new
   ---------------------------- */
if ($action == 'save_new' && $_SERVER['REQUEST_METHOD'] === 'POST' && $type_slug !== ''):
    $subassignment_id = $type_slug . '_main';
    $level = isset($_POST['level']) ? mysqli_real_escape_string($mysqli, $_POST['level']) : 'adult';
    $statement = isset($_POST['statement']) ? mysqli_real_escape_string($mysqli, $_POST['statement']) : '';

    if ($statement === '') {
        header("Location: ct_admin.php?action=create&type=" . urlencode($type_slug) . "&error=" . urlencode(base64_encode("Statement required")));
        exit;
    }

    // compute next sort_order
    $mx = $mysqli->prepare("SELECT COALESCE(MAX(sort_order),0) AS mx FROM ct_statements WHERE subassignment_id = ?");
    if ($mx) {
        $mx->bind_param('s', $subassignment_id);
        $mx->execute();
        $mxr = $mx->get_result()->fetch_assoc();
        $mx->close();
    } else {
        $mxr = array('mx' => 0);
    }
    $next = isset($mxr['mx']) ? ((int)$mxr['mx'] + 1) : 1;

    $ins = $mysqli->prepare("INSERT INTO ct_statements (subassignment_id, level, statement, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
    if ($ins) {
        $ins->bind_param('sssi', $subassignment_id, $level, $statement, $next);
        $ins->execute();
        $ins->close();
    }

    header("Location: ct_admin.php?action=list&type=" . urlencode($type_slug) . "&msg=" . urlencode(base64_encode("Created successfully")));
    exit;
endif; // save_new

/* ----------------------------
   ACTION: edit (statement)
   ---------------------------- */
if ($action == 'edit' && $type_slug !== ''):
    $vid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $stmt = $mysqli->prepare("SELECT id, subassignment_id, level, statement, sort_order, is_active FROM ct_statements WHERE id = ? LIMIT 1");
    if (!$stmt) {
        die("Invalid statement ID");
    }
    $stmt->bind_param('i', $vid);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res || $res->num_rows == 0) {
        die("Statement not found");
    }
    $V = $res->fetch_assoc();
    $stmt->close();
?>
<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row mb-3">
      <div class="col-12">
        <div class="card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h3 class="mb-0">Edit Statement — <?php echo htmlspecialchars($type_title); ?></h3>
              <p class="text-muted mb-0">Edit the statement text and metadata.</p>
            </div>
            <div>
              <a href="ct_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>" class="btn btn-secondary">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <form method="POST" action="ct_admin.php?action=save_edit&type=<?php echo urlencode($type_slug); ?>&id=<?php echo $vid; ?>">
          <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="level" class="form-control" required>
              <option value="kid" <?php if ($V['level']=='kid') echo 'selected'; ?>>Kid</option>
              <option value="teen" <?php if ($V['level']=='teen') echo 'selected'; ?>>Teen</option>
              <option value="adult" <?php if ($V['level']=='adult') echo 'selected'; ?>>Adult</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Statement</label>
            <textarea name="statement" rows="4" class="form-control"><?php echo htmlspecialchars($V['statement']); ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Active</label>
            <select name="is_active" class="form-control">
              <option value="1" <?php if ($V['is_active']) echo 'selected'; ?>>Active</option>
              <option value="0" <?php if (!$V['is_active']) echo 'selected'; ?>>Inactive</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>

  </div>
</div>
<?php require_once('../platformFooter.php'); ?>
</div>
<?php
endif; // edit

/* ----------------------------
   ACTION: save_edit
   ---------------------------- */
if ($action == 'save_edit' && $_SERVER['REQUEST_METHOD'] === 'POST' && $type_slug !== ''):
    $vid = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $level = isset($_POST['level']) ? mysqli_real_escape_string($mysqli, $_POST['level']) : 'adult';
    $statement = isset($_POST['statement']) ? mysqli_real_escape_string($mysqli, $_POST['statement']) : '';
    $is_active = isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1;

    // single correct prepared update using appropriate types
    $upd = $mysqli->prepare("UPDATE ct_statements SET level = ?, statement = ?, is_active = ?, updated_at = NOW() WHERE id = ? LIMIT 1");
    if ($upd) {
        $upd->bind_param('ssii', $level, $statement, $is_active, $vid);
        $upd->execute();
        $upd->close();
    }

    header("Location: ct_admin.php?action=list&type=" . urlencode($type_slug) . "&msg=" . urlencode(base64_encode("Updated successfully")));
    exit;
endif; // save_edit

/* ----------------------------
   ACTION: attempts (review queue + CSV export)
   ---------------------------- */
if ($action == 'attempts'):

    // Allowed filters: type (optional) and variant_id (not used here, but preserved)
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $variant_id = isset($_GET['variant_id']) ? (int) $_GET['variant_id'] : 0;
    $export = isset($_GET['export']) && $_GET['export'] == '1' ? true : false;

    // Determine subassignment filter
    $where_parts = array();
    $params = array();
    $typestr = '';

    if ($type !== '') {
        $subassignment_id = $type . '_main';
        $where_parts[] = 'q.subassignment_id = ?';
        $params[] = $subassignment_id;
        $typestr .= 's';
    }

    // (variant filter optional) - if you track which statement was attempted in ct_submissions, add it here.
    if ($variant_id > 0) {
        $where_parts[] = 's.statement_id = ?'; // if submissions store statement_id
        $params[] = $variant_id;
        $typestr .= 'i';
    }

    $where_sql = '';
    if (count($where_parts) > 0) $where_sql = ' WHERE ' . implode(' AND ', $where_parts);

    // CSV export
    if ($export) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ct_attempts_export_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, array('queue_id','submission_id','user_login','submitted_at','status','auto_score','display_score','ai_feedback','instructor_score','instructor_comments','file_paths','response_text'));

        $sql =
          "SELECT q.id AS queue_id, q.submission_id, s.user_login, s.created_at AS submitted_at, q.status, q.auto_score, "
          . " COALESCE(ir.instructor_score, q.auto_score) AS display_score, afr.feedback_json, ir.instructor_score, ir.comments, att.file_paths, s.response_text "
          . "FROM ct_review_queue q "
          . "LEFT JOIN ct_submissions s ON s.id = q.submission_id "
          . "LEFT JOIN (SELECT submission_id, GROUP_CONCAT(file_path ORDER BY id SEPARATOR ';') AS file_paths FROM ct_attachments WHERE is_deleted = 0 GROUP BY submission_id) att ON att.submission_id = s.id "
          . "LEFT JOIN ct_ai_feedback afr ON afr.submission_id = s.id "
          . "LEFT JOIN ct_instructor_reviews ir ON ir.submission_id = s.id "
          . $where_sql
          . " ORDER BY q.created_at DESC";

        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            if (!empty($params)) {
                $bind = array();
                $bind[] = & $typestr;
                for ($i=0;$i<count($params);$i++) $bind[] = & $params[$i];
                call_user_func_array(array($stmt,'bind_param'), $bind);
            }
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                fputcsv($out, array(
                    $r['queue_id'],
                    $r['submission_id'],
                    $r['user_login'],
                    $r['submitted_at'],
                    $r['status'],
                    $r['auto_score'],
                    $r['display_score'],
                    isset($r['feedback_json']) ? $r['feedback_json'] : '',
                    isset($r['instructor_score']) ? $r['instructor_score'] : '',
                    isset($r['comments']) ? $r['comments'] : '',
                    isset($r['file_paths']) ? $r['file_paths'] : '',
                    isset($r['response_text']) ? $r['response_text'] : ''
                ));
            }
            $stmt->close();
        }
        fclose($out);
        exit;
    }

    // Normal render (server-side list)
    $list_sql =
      "SELECT q.id AS queue_id, q.submission_id, s.user_login, s.created_at AS submitted_at, q.status, q.auto_score, "
      . " COALESCE(ir.instructor_score, q.auto_score) AS display_score, afr.feedback_json, ir.instructor_score, ir.comments, att.file_paths, s.response_text "
      . "FROM ct_review_queue q "
      . "LEFT JOIN ct_submissions s ON s.id = q.submission_id "
      . "LEFT JOIN (SELECT submission_id, GROUP_CONCAT(file_path ORDER BY id SEPARATOR ';') AS file_paths FROM ct_attachments WHERE is_deleted = 0 GROUP BY submission_id) att ON att.submission_id = s.id "
      . "LEFT JOIN ct_ai_feedback afr ON afr.submission_id = s.id "
      . "LEFT JOIN ct_instructor_reviews ir ON ir.submission_id = s.id "
      . $where_sql
      . " ORDER BY q.created_at DESC";

    $attempt_rows = array();
    $stmt = $mysqli->prepare($list_sql);
    if ($stmt) {
        if (!empty($params)) {
            $bind = array();
            $bind[] = & $typestr;
            for ($i=0;$i<count($params);$i++) $bind[] = & $params[$i];
            call_user_func_array(array($stmt,'bind_param'), $bind);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $attempt_rows[] = $r;
        $stmt->close();
    }

?>
<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row mb-2">
      <div class="col-md-3">
        <a href="ct_admin.php?action=dashboard" class="btn btn-secondary w-100"><i class="bx bx-arrow-back"></i> Back</a>
      </div>
      <div class="col-md-3">
        <?php
          $qs = $_GET;
          $qs['export'] = '1';
          $exportUrl = 'ct_admin.php?' . htmlspecialchars(http_build_query($qs));
        ?>
        <a class="btn btn-outline-secondary" href="<?php echo $exportUrl; ?>">Export CSV</a>
      </div>
      <div class="col-md-6 text-end">
        <div class="small text-muted">Showing <?php echo count($attempt_rows); ?> rows.</div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="ctAttemptsTable" class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Queue ID</th>
                <th>Submission</th>
                <th>User</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Auto</th>
                <th>Display Score</th>
                <th>AI Feedback</th>
                <th>Instructor</th>
                <th>Files</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($attempt_rows as $ar):
                $qid = (int)$ar['queue_id'];
                $sid = (int)$ar['submission_id'];
                $user_login = htmlspecialchars($ar['user_login']);
                $submitted = (!empty($ar['submitted_at'])) ? htmlspecialchars($ar['submitted_at']) : '';
                $status = htmlspecialchars($ar['status']);
                $auto = ($ar['auto_score'] !== null) ? htmlspecialchars($ar['auto_score']) : '-';
                $display = ($ar['display_score'] !== null) ? htmlspecialchars($ar['display_score']) : '-';
                $ai_fb = isset($ar['feedback_json']) ? htmlspecialchars($ar['feedback_json']) : '';
                $instr_score = isset($ar['instructor_score']) ? htmlspecialchars($ar['instructor_score']) : '';
                $instr_comments = isset($ar['comments']) ? htmlspecialchars($ar['comments']) : '';
                $file_cell = '-';
                if (!empty($ar['file_paths'])) {
                    $parts = explode(';', $ar['file_paths']);
                    $links = array();
                    foreach ($parts as $f) {
                        $f_trim = trim($f);
                        if ($f_trim === '') continue;
                        $url = '../../uploads/attempts/' . rawurlencode($f_trim);
                        $links[] = '<a target="_blank" rel="noopener" href="'.htmlspecialchars($url).'">'.htmlspecialchars($f_trim).'</a>';
                    }
                    if (!empty($links)) $file_cell = implode('<br>', $links);
                }
            ?>
              <tr>
                <td><?php echo $qid; ?></td>
                <td><?php echo $sid; ?></td>
                <td><?php echo $user_login; ?></td>
                <td><?php echo $submitted; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $auto; ?></td>
                <td><?php echo $display; ?></td>
                <td><?php echo $ai_fb; ?></td>
                <td><?php echo $instr_score . ' — ' . $instr_comments; ?></td>
                <td><?php echo $file_cell; ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-primary" href="ct_attempt.php?submission_id=<?php echo $sid; ?>">View</a>
                  <a class="btn btn-sm btn-outline-success" href="ct_grade_attempt.php?submission_id=<?php echo $sid; ?>">Grade</a>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
jQuery(document).ready(function($){
    try {
        $('#ctAttemptsTable').DataTable({
            "pageLength": 25,
            "lengthChange": false,
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": 10 }
            ],
            "deferRender": true,
            "autoWidth": false
        });
    } catch (err) {
        console.error('DataTables init failed:', err);
    }
});
</script>

<?php require_once('../platformFooter.php'); ?>
</div>
<?php
endif; // attempts

/* ----------------------------
   ACTION: analytics (simple CT analytics)
   ---------------------------- */
if ($action == 'analytics' && $type_slug !== ''):
    // total submissions for this subassignment
    $subassignment_id = $type_slug . '_main';
    $totalAttempts = 0;
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM ct_review_queue WHERE subassignment_id = ?");
    if ($stmt) {
        $stmt->bind_param('s', $subassignment_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $totalAttempts = isset($row['c']) ? (int)$row['c'] : 0;
        $stmt->close();
    }

    // average auto_score
    $avgAuto = null;
    $stmt = $mysqli->prepare("SELECT AVG(auto_score) AS a FROM ct_review_queue WHERE subassignment_id = ? AND auto_score IS NOT NULL");
    if ($stmt) {
        $stmt->bind_param('s', $subassignment_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $avgAuto = isset($row['a']) ? round((float)$row['a'],2) : null;
        $stmt->close();
    }

?>
<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h3 class="mb-0">Analytics — <?php echo htmlspecialchars($type_title); ?></h3>
              <p class="text-muted mb-0">Simple performance summary for this critical thinking assignment.</p>
            </div>
            <div>
              <a href="ct_admin.php?action=dashboard" class="btn btn-secondary">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 mt-3">
      <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center">
          <h6>Total Submissions</h6>
          <h3><?php echo (int)$totalAttempts; ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center">
          <h6>Average Auto Score</h6>
          <h3><?php echo ($avgAuto !== null ? $avgAuto . '%' : '—'); ?></h3>
        </div>
      </div>
    </div>

  </div>
</div>
<?php require_once('../platformFooter.php'); ?>
</div>
<?php
endif; // analytics

/* Footer (if not already printed by earlier blocks) */
if ($action !== 'dashboard' && $action !== 'list' && $action !== 'create' && $action !== 'edit' && $action !== 'attempts' && $action !== 'analytics') {
    // default catch - redirect to dashboard
    header("Location: ct_admin.php?action=dashboard");
    exit;
}
?>
