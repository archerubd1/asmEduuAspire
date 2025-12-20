<?php
/**
 * Astraal LXP ct_admin.php — Critical Thinking Instructor Admin
 * Modules 1 & 2 — Core Setup + Manage Statements
 * PHP 5.4+ compatible
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

/* ---------- mysqli connection ---------- */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

if (empty($mysqli) || !($mysqli instanceof mysqli)) {
    die("Database connection not found. Ensure config.php defines \$coni (mysqli).");
}

/* ---------- Params ---------- */
$action    = isset($_GET['action']) ? trim($_GET['action']) : 'list';
$type_slug = isset($_GET['type']) ? trim($_GET['type']) : '';


/* ---------- Mapping ---------- */
$mapping = array(
    'fact_opinion' => 'Fact vs Opinion',
    'coffee_chat'     => 'Coffee House Chat',
    'worldly_words'   => 'Worldly Words',
    'alien_guide'     => 'Alien Travel Guide',
    'talk_it_out'     => 'Talk It Out',
    'elevator_pitch'  => 'Elevator Pitch'
);

$type_title = isset($mapping[$type_slug]) ? $mapping[$type_slug] : ucfirst(str_replace('_',' ',$type_slug));
$page = "criticalThinkingAdmin";
require_once('instructorHead_Nav2.php');



/* ----------------------------------------------------
   ACTION: list — Show all statements for given assignment type ALONG with the CTAs Edit / Attempts 
   ---------------------------------------------------- */
if ($action === 'list' && $type_slug !== '') {

    // Fetch all statements for the given assignment type (supports both legacy & numeric subassignments)
    $statements = [];
    $stmt = $mysqli->prepare("
        SELECT st.id, st.level, st.statement, st.sort_order, st.is_active, st.created_at, st.subassignment_id
        FROM ct_statements st
        WHERE st.subassignment_id = ?
        OR st.subassignment_id IN (
            SELECT id FROM ct_subassignments WHERE assignment_id = ?
        )
        ORDER BY st.sort_order ASC
    ");
    if ($stmt) {
        $stmt->bind_param('ss', $type_slug, $type_slug);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
            $statements[] = $r;
        }
        $stmt->close();
    }

    // Determine which subassignment+level combinations have submissions
    $submissionMap = [];
    $chk = $mysqli->prepare("
        SELECT DISTINCT s.subassignment_id, s.level
        FROM ct_submissions s
        WHERE (s.subassignment_id IN (
            SELECT id FROM ct_subassignments WHERE assignment_id = ?
        )
        OR s.subassignment_id = ?)
    ");
    if ($chk) {
        $chk->bind_param('ss', $type_slug, $type_slug);
        $chk->execute();
        $res = $chk->get_result();
        while ($r = $res->fetch_assoc()) {
            $submissionMap[$r['subassignment_id'] . '|' . $r['level']] = true;
        }
        $chk->close();
    }

    $type_title = isset($mapping[$type_slug]) ? $mapping[$type_slug] : ucfirst($type_slug);
?>
<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-3">
  <img src="../assets/img/ctimage1.png" alt="Critical Thinking Icon" style="height:45px; width:auto; border-radius:6px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
  <div>
    <h3 class="mb-0">Manage Critical Thinking Statements for <i> <?php echo htmlspecialchars($type_title); ?></i></h3>
    <p class="text-muted mb-0">
      Review, edit, and manage Active statements. 
      <strong>Edit</strong> is disabled if learner attempts exist.
    </p>
  </div>
</div>

              <div>
                <a href="critical-thinking.php" class="btn btn-secondary">Back</a>
                <a href="ct_admin.php?action=create&type=<?php echo urlencode($type_slug); ?>" class="btn btn-success">Add Statement</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <?php if (empty($statements)): ?>
            <div class="text-center text-muted py-4">No statements found for this assignment.</div>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-striped align-middle">
              <thead>
               <tr>
                  <th style="text-align:center;">
                      <span style="display:inline-flex; align-items:center; gap:6px;">
                          <i class="fa fa-hashtag"></i> <span>ID</span>
                      </span>
                  </th>
                  <th style="text-align:center;">
                      <span style="display:inline-flex; align-items:center; gap:6px;">
                          <i class="fa fa-tasks"></i> <span>Level</span>
                      </span>
                  </th>
                  <th style="text-align:center;">
                      <span style="display:inline-flex; align-items:center; gap:6px;">
                          <i class="fa fa-layer-group"></i> <span>Statement</span>
                      </span>
                  </th>
                  <th colspan="2" style="text-align:center;">
                      <span style="display:inline-flex; align-items:center; gap:6px;">
                          <i class="fa fa-cogs"></i> <span>Actions</span>
                      </span>
                  </th>
               </tr>
              </thead>
              <tbody>
              <?php foreach ($statements as $s):
                $key = $s['subassignment_id'] . '|' . $s['level'];
                $hasSub = isset($submissionMap[$key]);
              ?>
               <tr>
  <td style="text-align:center;"><?php echo (int)$s['id']; ?></td>
  <td style="text-align:center;"><?php echo htmlspecialchars($s['level']); ?></td>
  <td style="text-align:left;">
    <?php echo htmlspecialchars(strlen($s['statement']) > 120 ? substr($s['statement'], 0, 120) . '…' : $s['statement']); ?>
  </td>

  <td style="text-align:center;">
    <?php if ($hasSub): ?>
      <button class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-1"
              disabled title="Cannot edit — learner submissions exist">
        <i class="fa fa-lock"></i> <span>Edit</span>
      </button>
    <?php else: ?>
      <a href="ct_admin.php?action=edit&type=<?php echo urlencode($type_slug); ?>&id=<?php echo (int)$s['id']; ?>"
         class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center gap-1">
         <i class="fa fa-edit"></i> <span>Edit</span>
      </a>
    <?php endif; ?>
  </td>

  <td style="text-align:center;">
    <?php if ($hasSub): ?>
      <a href="ct_admin.php?action=attempts&type=<?php echo urlencode($type_slug); ?>&sub=<?php echo urlencode($s['subassignment_id']); ?>&lvl=<?php echo urlencode($s['level']); ?>"
         class="btn btn-sm btn-outline-info d-inline-flex align-items-center justify-content-center gap-1">
         <i class="fa fa-eye"></i> <span>Attempts</span>
      </a>
    <?php endif; ?>
  </td>
</tr>

              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>
<?php
} // end action=list



/* ----------------------------------------------------
   ACTION: attempts — Show all submissions for type or subassignment+level
   ---------------------------------------------------- */
if ($action === 'attempts' && $type_slug !== '') {

    $sub_id_filter = isset($_GET['sub']) ? trim($_GET['sub']) : null;
    $level_filter  = isset($_GET['lvl']) ? trim($_GET['lvl']) : null;

    $submissions = [];

    // Case 1: Filtered by subassignment + level (from Manage Statements)
    if (!empty($sub_id_filter) && !empty($level_filter)) {
        $sql = "
            SELECT s.id, s.user_id, u.login, u.name, u.surname, s.subassignment_id, s.level, s.created_at,
                   COALESCE(s.auto_score, 0) AS auto_score,
                   COALESCE(s.final_score, s.auto_score) AS final_score,
                   COALESCE(r.comments, ai.feedback_json) AS feedback
            FROM ct_submissions s
            INNER JOIN users u ON u.id = s.user_id
            LEFT JOIN ct_ai_feedback ai ON ai.submission_id = s.id
            LEFT JOIN ct_instructor_reviews r ON r.submission_id = s.id
            WHERE s.subassignment_id = ? AND s.level = ?
            ORDER BY s.created_at DESC
        ";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $sub_id_filter, $level_filter);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) $submissions[] = $row;
            $stmt->close();
        }

    // Case 2: General view (from critical_thinking.php)
    } else {
        $sql = "
            SELECT s.id, s.user_id, u.login, u.name, u.surname, s.subassignment_id, s.level, s.created_at,
                   COALESCE(s.auto_score, 0) AS auto_score,
                   COALESCE(s.final_score, s.auto_score) AS final_score,
                   COALESCE(r.comments, ai.feedback_json) AS feedback
            FROM ct_submissions s
            INNER JOIN users u ON u.id = s.user_id
            INNER JOIN ct_subassignments sa ON sa.id = s.subassignment_id
            LEFT JOIN ct_ai_feedback ai ON ai.submission_id = s.id
            LEFT JOIN ct_instructor_reviews r ON r.submission_id = s.id
            WHERE sa.assignment_id = ?
            ORDER BY s.created_at DESC
        ";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $type_slug);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) $submissions[] = $row;
            $stmt->close();
        }
    }

    $type_title = isset($mapping[$type_slug]) ? $mapping[$type_slug] : ucfirst($type_slug);
?>
<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <i class="fa fa-file-alt text-primary fs-4"></i>
                <div>
                  <h3 class="mb-0">View Attempts for <i><?php echo htmlspecialchars($type_title); ?> Assignment</i> of Critical Thinking</h3>
                  <p class="text-muted mb-0">
                    Review learner submissions, AI scores, and instructor overrides.
                    <?php if ($sub_id_filter): ?>
                      <br><small>
                        Filter: Subassignment = <strong><?php echo htmlspecialchars($sub_id_filter); ?></strong>
                        <?php if ($level_filter) echo " | Level = <strong>".htmlspecialchars($level_filter)."</strong>"; ?>
                      </small>
                    <?php endif; ?>
                  </p>
                </div>
              </div>
              <a href="critical-thinking.php" class="btn btn-secondary d-inline-flex align-items-center gap-1">
                <i class="fa fa-arrow-left"></i> <span>Back</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <?php if (empty($submissions)): ?>
            <div class="text-center text-muted py-4">No submissions found for this selection.</div>
          <?php else: ?>
          <div class="table-responsive">
            <table id="ctSubmissionsTable" class="table table-striped table-bordered align-middle">
              <thead class="table-light">
                <tr class="text-center">
                  <th class="text-center" style="width:5%;">ID</th>
                  <th class="text-center" style="width:20%;">Name(login)</th>
                  <th class="text-center" style="width:10%;">Level</th>
                  <th class="text-center" style="width:10%;">AI Score</th>
                 
                  <th class="text-center" style="width:25%;">Feedback</th>
                  <th class="text-center" style="width:20%;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($submissions as $s): ?>
                  <tr class="text-center">
                    <td><?php echo htmlspecialchars($s['id']); ?></td>
                    <td class="text-start">
                      <?php 
                        echo htmlspecialchars($s['name'] . ' ' . $s['surname']);
                        echo $s['login'] ? ' <small class="text-muted">(' . htmlspecialchars($s['login']) . ')</small>' : '';
                      ?>
                    </td>
                    <td><?php echo htmlspecialchars($s['level']); ?></td>
                    <td><?php echo htmlspecialchars($s['auto_score']); ?>%</td>
                    
                    <td class="text-start">
                      <?php
                        $fb = $s['feedback'];
                        echo $fb ? htmlspecialchars(substr(strip_tags($fb), 0, 80)) . '…' : '<span class="text-muted">—</span>';
                      ?>
                    </td>
                    <td>
                      <a href="ct_admin.php?action=view&submission_id=<?php echo urlencode($s['id']); ?>&type=<?php echo urlencode($type_slug); ?>"
                         class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                         <i class="fa fa-eye"></i> <span>View</span>
                      </a>
                      <a href="ct_admin.php?action=grade&submission_id=<?php echo urlencode($s['id']); ?>&type=<?php echo urlencode($type_slug); ?>"
                         class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1">
                         <i class="fa fa-marker"></i> <span>Grade</span>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

  <?php require_once('../platformFooter.php'); ?>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
jQuery(function($){
  $('#ctSubmissionsTable').DataTable({
    pageLength: 25,
    lengthChange: false,
    order: [[0, 'desc']],
    columnDefs: [{ orderable: false, targets: 5 }],
    autoWidth: false
  });
});
</script>
<?php
} // end action=attempts





/* ----------------------------------------------------
   ACTION: view — Display detailed submission information + CT domain analytics
   ---------------------------------------------------- */
if ($action === 'view' && isset($_GET['submission_id'])) {

    $submission_id = (int) $_GET['submission_id'];

    // Fetch full submission info with joins
    $sql = "
        SELECT 
            s.id,
            s.user_id,
            u.login AS user_login,
            CONCAT(u.name, ' ', u.surname) AS full_name,
            u.email,
            s.subassignment_id,
            s.level,
            s.content_text AS response_text,
            s.auto_score,
            s.final_score,
            s.created_at,
            s.updated_at,
            s.instructor_notes,
            s.is_overridden,
            s.status,
            s.ai_feedback_id,
            s.statement_id,
            sa.assignment_id,
            st.statement AS statement_text
        FROM ct_submissions s
        INNER JOIN users u ON u.id = s.user_id
        LEFT JOIN ct_subassignments sa ON sa.id = s.subassignment_id
        LEFT JOIN ct_statements st ON st.id = s.statement_id
        WHERE s.id = ?
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) die("Invalid submission ID (prepare failed): " . $mysqli->error);
    $stmt->bind_param('i', $submission_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) die("Submission not found.");
    $S = $res->fetch_assoc();
    $stmt->close();

    // Decode AI feedback JSON if available
    $ai_feedback = '';
    if (!empty($S['ai_feedback_id'])) {
        $decoded = json_decode($S['ai_feedback_id'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $ai_feedback = "<pre class='small text-muted'>" . htmlspecialchars(print_r($decoded, true)) . "</pre>";
        } else {
            $ai_feedback = nl2br(htmlspecialchars($S['ai_feedback_id']));
        }
    }

    /* ---------- Mapping ---------- */
    $mapping = array(
        'fact_opinion'   => 'Fact vs Opinion',
        'coffee_chat'    => 'Coffee House Chat',
        'worldly_words'  => 'Worldly Words',
        'alien_guide'    => 'Alien Travel Guide',
        'talk_it_out'    => 'Talk It Out',
        'elevator_pitch' => 'Elevator Pitch'
    );

    $assignment_key = strtolower(trim($S['assignment_id']));
    $assignment_title = isset($mapping[$assignment_key])
        ? $mapping[$assignment_key]
        : ucwords(str_replace('_', ' ', $assignment_key));

    /* --- Fetch domain-specific scores --- */
    $domain_scores = array();
    $domain_q = $mysqli->query("
        SELECT domain_key, score, label, last_updated
        FROM ct_domain_scores
        WHERE submission_id = " . (int)$submission_id . "
        ORDER BY domain_key ASC
    ");
    if ($domain_q && $domain_q->num_rows > 0) {
        while ($d = $domain_q->fetch_assoc()) {
            $domain_scores[] = $d;
        }
    }
?>
<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <!-- Header -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <i class="fa fa-user-graduate text-primary fs-4"></i>
                <div>
                  <h3 class="mb-0">
                    Submission Details for <i><?php echo htmlspecialchars($assignment_title); ?> Assignment</i> — Critical Thinking
                  </h3>
                  <p class="text-muted mb-0">Review learner response, AI evaluation, domain scores, and instructor notes.</p>
                </div>
              </div>
              <a href="ct_admin.php?action=attempts&type=<?php echo urlencode($S['assignment_id']); ?>" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Statement Info -->
      <div class="card mb-3 shadow-sm border-info">
        <div class="card-body">
          <h5 class="mb-1 text-primary">
            <i class="fa fa-lightbulb me-2"></i>
            Statement <?php echo htmlspecialchars($S['subassignment_id']); ?> 
            <small class="text-muted">(Level: <?php echo strtoupper(htmlspecialchars($S['level'])); ?>)</small>
          </h5>
          <p class="mb-0 text-dark">
            <?php echo htmlspecialchars($S['statement_text']); ?>
          </p>
        </div>
      </div>

      <!-- Submission Card -->
      <div class="card shadow-sm">
        <div class="card-body">

          <div class="mb-3">
            <h6 class="text-muted mb-1">Learner Details:</h6>
            <p class="mb-0"><strong><i class="fa fa-users text-secondary me-1"></i> <?php echo htmlspecialchars($S['full_name']); ?></strong></p>
            <small class="text-muted d-flex align-items-center flex-wrap gap-3">
              <span><i class="fa fa-user me-1 text-primary"></i> Login: <?php echo htmlspecialchars($S['user_login']); ?></span>
              <span><i class="fa fa-envelope me-1 text-success"></i> Email: <?php echo htmlspecialchars($S['email']); ?></span>
              <span><i class="fa fa-clock me-1 text-secondary"></i> Submitted At: <?php echo htmlspecialchars($S['created_at']); ?></span>
            </small>
          </div>

          <div class="mb-3">
            <h6 class="text-muted mb-1">Learner Response:</h6>
            <div class="p-3 bg-light rounded border" style="white-space:pre-wrap;">
              <?php echo nl2br(htmlspecialchars($S['response_text'])); ?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <h6 class="text-muted mb-1">AI Auto Score:</h6>
              <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($S['auto_score']); ?>%</span>
            </div>
            <div class="col-md-6 mb-3">
              <h6 class="text-muted mb-1">Final Score:</h6>
              <span class="badge bg-success fs-6"><?php echo htmlspecialchars($S['final_score']); ?>%</span>
            </div>
          </div>

          <!-- CT Domain Evaluation Section -->
          <div class="mb-3">
            <h6 class="text-muted mb-1"><i class="fa fa-brain me-1 text-warning"></i> Critical Thinking Domain Evaluation:</h6>
            <?php if (!empty($domain_scores)): ?>
              <table class="table table-sm table-bordered align-middle mt-2">
                <thead class="table-light">
                  <tr>
                    <th>Domain</th>
                    <th>Score (%)</th>
                    <th>Last Updated</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($domain_scores as $d): ?>
                    <tr>
                      <td><i class="fa fa-check-circle text-success me-1"></i> <?php echo htmlspecialchars($d['label']); ?></td>
                      <td><strong><?php echo htmlspecialchars($d['score']); ?></strong></td>
                      <td class="text-muted small"><?php echo htmlspecialchars($d['last_updated']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p class="text-muted">No domain evaluations available for this submission.</p>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <h6 class="text-muted mb-1">AI Feedback Data:</h6>
            <?php echo $ai_feedback ?: '<p class="text-muted">No AI feedback available.</p>'; ?>
          </div>

          <div class="mb-3">
            <h6 class="text-muted mb-1">Instructor Notes:</h6>
            <?php if (!empty($S['instructor_notes'])): ?>
              <div class="p-3 bg-light border rounded small" style="white-space:pre-wrap;">
                <?php echo nl2br(htmlspecialchars($S['instructor_notes'])); ?>
              </div>
            <?php else: ?>
              <p class="text-muted">No instructor notes available.</p>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <h6 class="text-muted mb-1">Status:</h6>
            <span class="badge <?php echo ($S['status'] === 'submitted') ? 'bg-secondary' : 'bg-success'; ?>">
              <?php echo htmlspecialchars(ucfirst($S['status'])); ?>
            </span>
          </div>

          <div class="text-end mt-4">
            <a href="ct_admin.php?action=grade&submission_id=<?php echo (int)$S['id']; ?>&type=<?php echo urlencode($S['assignment_id']); ?>" 
               class="btn btn-success d-inline-flex align-items-center gap-1">
              <i class="fa fa-marker"></i> <span>Grade / Override</span>
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>
<?php
}


/* ----------------------------------------------------
   ACTION: grade — Instructor grading + domain-based CT evaluation (submission-specific)
   ---------------------------------------------------- */
if ($action === 'grade' && isset($_GET['submission_id'])) {

    $submission_id = (int) $_GET['submission_id'];

    // --- Fetch submission context
    $sql = "
        SELECT 
            s.id, s.user_id, s.subassignment_id, s.statement_id, s.level,
            s.content_text AS response_text, s.auto_score, s.final_score,
            s.created_at, sa.assignment_id, st.statement AS statement_text,
            u.login AS user_login, CONCAT(u.name, ' ', u.surname) AS full_name, u.email,
            ir.instructor_score, ir.comments AS instructor_comments
        FROM ct_submissions s
        INNER JOIN users u ON u.id = s.user_id
        LEFT JOIN ct_subassignments sa ON sa.id = s.subassignment_id
        LEFT JOIN ct_statements st ON st.id = s.statement_id
        LEFT JOIN ct_instructor_reviews ir ON ir.submission_id = s.id
        WHERE s.id = ?
        LIMIT 1
    ";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) die("Invalid submission ID (prepare failed): " . $mysqli->error);
    $stmt->bind_param('i', $submission_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) die("Submission not found.");
    $S = $res->fetch_assoc();
    $stmt->close();

    // --- Assignment mapping
    $mapping = array(
        'fact_opinion'   => 'Fact vs Opinion',
        'coffee_chat'    => 'Coffee House Chat',
        'worldly_words'  => 'Worldly Words',
        'alien_guide'    => 'Alien Travel Guide',
        'talk_it_out'    => 'Talk It Out',
        'elevator_pitch' => 'Elevator Pitch'
    );
    $assignment_key = strtolower(trim($S['assignment_id']));
    $assignment_title = isset($mapping[$assignment_key])
        ? $mapping[$assignment_key]
        : ucwords(str_replace('_', ' ', $assignment_key));

    /* --- Handle POST save --- */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $score = isset($_POST['instructor_score']) ? (float) $_POST['instructor_score'] : null;
        $comments = mysqli_real_escape_string($mysqli, trim($_POST['comments']));

        // Six domain parameters
        $ct_params = array(
            'ct_clarity'    => isset($_POST['ct_clarity']) ? (float) $_POST['ct_clarity'] : 0,
            'ct_evidence'   => isset($_POST['ct_evidence']) ? (float) $_POST['ct_evidence'] : 0,
            'ct_reasoning'  => isset($_POST['ct_reasoning']) ? (float) $_POST['ct_reasoning'] : 0,
            'ct_creativity' => isset($_POST['ct_creativity']) ? (float) $_POST['ct_creativity'] : 0,
            'ct_relevance'  => isset($_POST['ct_relevance']) ? (float) $_POST['ct_relevance'] : 0,
            'ct_reflection' => isset($_POST['ct_reflection']) ? (float) $_POST['ct_reflection'] : 0
        );

        // Auto average if no overall
        if ($score === null || $score == 0) {
            $score = round(array_sum($ct_params) / count($ct_params), 2);
        }

        if ($score >= 0 && $score <= 100) {

            // Upsert instructor review
            $check = $mysqli->prepare("SELECT COUNT(*) AS cnt FROM ct_instructor_reviews WHERE submission_id=?");
            $check->bind_param('i', $submission_id);
            $check->execute();
            $cnt = $check->get_result()->fetch_assoc();
            $cnt = $cnt['cnt'];
            $check->close();

            if ($cnt > 0) {
                $upd = $mysqli->prepare("
                    UPDATE ct_instructor_reviews 
                    SET instructor_score=?, comments=?, updated_at=NOW() 
                    WHERE submission_id=?
                ");
                $upd->bind_param('dsi', $score, $comments, $submission_id);
                $upd->execute();
                $upd->close();
            } else {
                $ins = $mysqli->prepare("
                    INSERT INTO ct_instructor_reviews 
                        (submission_id, instructor_score, comments, created_at, updated_at)
                    VALUES (?, ?, ?, NOW(), NOW())
                ");
                $ins->bind_param('ids', $submission_id, $score, $comments);
                $ins->execute();
                $ins->close();
            }

            // --- Maintain domain tables (submission-specific)
            foreach ($ct_params as $k => $v) {
                $domain_key = str_replace('ct_', '', $k);
                $label = ucwords($domain_key);

                // Insert into history
                $mysqli->query("
                    INSERT INTO ct_domain_score_history 
                    (submission_id, user_id, domain_key, score, label, snapshot_ts)
                    VALUES (" . (int)$submission_id . ", " . (int)$S['user_id'] . ", 
                    '" . $mysqli->real_escape_string($domain_key) . "', " . (float)$v . ", 
                    '" . $mysqli->real_escape_string($label) . "', NOW())
                ");

                // Check if a domain record exists for this submission
                $res2 = $mysqli->query("
                    SELECT id FROM ct_domain_scores 
                    WHERE user_id=" . (int)$S['user_id'] . " 
                    AND submission_id=" . (int)$submission_id . " 
                    AND domain_key='" . $mysqli->real_escape_string($domain_key) . "' 
                    LIMIT 1
                ");

                if ($res2 && $res2->num_rows > 0) {
                    $row2 = $res2->fetch_assoc();
                    $mysqli->query("
                        UPDATE ct_domain_scores 
                        SET score=" . (float)$v . ", label='" . $mysqli->real_escape_string($label) . "', last_updated=NOW() 
                        WHERE id=" . (int)$row2['id']
                    );
                } else {
                    $mysqli->query("
                        INSERT INTO ct_domain_scores 
                        (submission_id, user_id, domain_key, score, label, last_updated)
                        VALUES (" . (int)$submission_id . ", " . (int)$S['user_id'] . ", 
                        '" . $mysqli->real_escape_string($domain_key) . "', " . (float)$v . ", 
                        '" . $mysqli->real_escape_string($label) . "', NOW())
                    ");
                }
            }

            // Update final score
            $mysqli->query("
                UPDATE ct_submissions 
                SET final_score=" . (float)$score . ", is_overridden=1, instructor_notes='" . $comments . "' 
                WHERE id=" . (int)$submission_id
            );

            header("Location: ct_admin.php?action=view&submission_id=" . $submission_id . 
                   "&type=" . urlencode($S['assignment_id']) . 
                   "&msg=" . urlencode(base64_encode('Grade saved successfully')));
            exit;
        } else {
            echo "<div class='alert alert-danger'>Invalid score. Must be between 0–100.</div>";
        }
    }
?>
<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h3 class="mb-0"><i class="fa fa-marker text-success me-2"></i> Grading of Submission for <i><?php echo htmlspecialchars($assignment_title); ?> Assignment of Critical Thinking</i> </h3>
            <p class="text-muted mb-0">Evaluate across critical thinking parameters, override AI score, and record analytics.</p>
          </div>
          <a href="ct_admin.php?action=view&submission_id=<?php echo (int)$S['id']; ?>&type=<?php echo urlencode($S['assignment_id']); ?>" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back
          </a>
        </div>
      </div>

      <div class="card mb-3 border-info shadow-sm">
        <div class="card-body">
          <h5 class="text-primary mb-1"><i class="fa fa-lightbulb me-2"></i>
            Statement <?php echo htmlspecialchars($S['subassignment_id']); ?>
            <small class="text-muted">(Level: <?php echo strtoupper(htmlspecialchars($S['level'])); ?>)</small>
          </h5>
          <p><?php echo htmlspecialchars($S['statement_text']); ?></p>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <small class="text-muted d-flex gap-3 mb-3">
            <span><i class="fa fa-user text-primary"></i> <?php echo htmlspecialchars($S['full_name']); ?></span>
            <span><i class="fa fa-envelope text-success"></i> <?php echo htmlspecialchars($S['email']); ?></span>
            <span><i class="fa fa-clock text-secondary"></i> <?php echo htmlspecialchars($S['created_at']); ?></span>
          </small>

          <div class="mb-3">
            <h6><i class="fa fa-pen-to-square text-info me-1"></i> Learner Response:</h6>
            <div class="p-3 bg-light border rounded"><?php echo nl2br(htmlspecialchars($S['response_text'])); ?></div>
          </div>

          <div class="mb-3">
            <span class="badge bg-info"><i class="fa fa-robot me-1"></i> AI Auto Score: <?php echo htmlspecialchars($S['auto_score']); ?>%</span>
          </div>

          <form method="POST">
            <h5 class="mb-3"><i class="fa fa-scale-balanced text-warning"></i> Evaluate Critical Thinking Domains</h5>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label><i class="fa fa-lightbulb text-warning me-1"></i> Clarity</label>
                <input type="number" name="ct_clarity" min="0" max="100" class="form-control" placeholder="Is the expression clear and unambiguous?">
              </div>
              <div class="col-md-4 mb-3">
                <label><i class="fa fa-scale-balanced text-info me-1"></i> Evidence</label>
                <input type="number" name="ct_evidence" min="0" max="100" class="form-control" placeholder="Is the response credible examples?">
              </div>
              <div class="col-md-4 mb-3">
                <label><i class="fa fa-brain text-primary me-1"></i> Reasoning</label>
                <input type="number" name="ct_reasoning" min="0" max="100" class="form-control" placeholder="Is the logic consistent and justified?">
              </div>
              <div class="col-md-4 mb-3">
                <label><i class="fa fa-palette text-danger me-1"></i> Creativity</label>
                <input type="number" name="ct_creativity" min="0" max="100" class="form-control" placeholder="Is the approach original, insightful, or novel?">
              </div>
              <div class="col-md-4 mb-3">
                <label><i class="fa fa-link text-success me-1"></i> Relevance</label>
                <input type="number" name="ct_relevance" min="0" max="100" class="form-control" placeholder="Are points focused on the topic or question?">
              </div>
              <div class="col-md-4 mb-3">
                <label><i class="fa fa-magnifying-glass text-secondary me-1"></i> Reflection</label>
                <input type="number" name="ct_reflection" min="0" max="100" class="form-control" placeholder="Does the learner demonstrate self-awareness?">
              </div>
            </div>

            <div class="mb-3">
              <label><i class="fa fa-star text-warning me-1"></i> Overall Instructor Score (0–100)</label>
              <input type="number" name="instructor_score" min="0" max="100" value="<?php echo htmlspecialchars($S['instructor_score']); ?>" class="form-control" placeholder="Final overall score">
            </div>

            <div class="mb-3">
              <label><i class="fa fa-comment-dots text-info me-1"></i> Instructor Comments</label>
              <textarea name="comments" class="form-control" rows="4" placeholder="Provide personalized feedback..."><?php echo htmlspecialchars($S['instructor_comments']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Grade</button>
            <a href="ct_admin.php?action=view&submission_id=<?php echo (int)$S['id']; ?>&type=<?php echo urlencode($S['assignment_id']); ?>" class="btn btn-outline-secondary ms-2">
              <i class="fa fa-times"></i> Cancel
            </a>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>
<?php
}
















/* ----------------------------------------------------
   ACTION: edit — Edit an existing statement
   ---------------------------------------------------- */
if ($action === 'edit' && $type_slug !== '') {

    // IDs may be integer or string (UUID); do not force int
    $statement_id = isset($_GET['id']) ? trim($_GET['id']) : '';

    // Fetch statement using subassignment mapping
    $stmt = $mysqli->prepare("
        SELECT st.id, st.subassignment_id, st.level, st.statement, st.is_active, st.sort_order, st.created_at
        FROM ct_statements st
        WHERE st.id = ?
          AND (st.subassignment_id = ?
            OR st.subassignment_id IN (
                SELECT id FROM ct_subassignments WHERE assignment_id = ?
            ))
        LIMIT 1
    ");
    if (!$stmt) die("Invalid request: statement lookup failed.");
    $stmt->bind_param('sss', $statement_id, $type_slug, $type_slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        die("<div class='alert alert-danger m-3'>Statement not found for this assignment.</div>");
    }
    $S = $res->fetch_assoc();
    $stmt->close();

    $assignment_title = isset($mapping[$type_slug]) ? $mapping[$type_slug] : ucfirst($type_slug);

    // Handle POST (update)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $level     = mysqli_real_escape_string($mysqli, $_POST['level']);
        $text      = mysqli_real_escape_string($mysqli, $_POST['statement']);
        $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

        if ($text === '') {
            echo "<div class='alert alert-danger text-center mt-3'>Statement text cannot be empty.</div>";
        } else {
            $update = $mysqli->prepare("
                UPDATE ct_statements
                SET level = ?, statement = ?, is_active = ?, updated_at = NOW()
                WHERE id = ?
                  AND (subassignment_id = ?
                    OR subassignment_id IN (
                        SELECT id FROM ct_subassignments WHERE assignment_id = ?
                    ))
                LIMIT 1
            ");
            if ($update) {
                $update->bind_param('ssisss', $level, $text, $is_active, $statement_id, $type_slug, $type_slug);
                $update->execute();
                $update->close();
                header("Location: ct_admin.php?action=list&type=" . urlencode($type_slug) . "&msg=" . urlencode(base64_encode("Statement updated successfully")));
                exit;
            } else {
                echo "<div class='alert alert-danger text-center mt-3'>Error updating record.</div>";
            }
        }
    }
?>
<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
  <i class="fa fa-pencil-alt text-primary fs-4"></i>
  <div>
    <h3 class="mb-0">
      Edit Statement for <i><?php echo htmlspecialchars($assignment_title); ?> Assignment</i> of Critical Thinking
    </h3>
    <p class="text-muted mb-0">
           Update the statement text or toggle its visibility status.
    </p>
  </div>
</div>

              <div>
                <a href="ct_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>" class="btn btn-secondary">
                  <i class="fa fa-arrow-left"></i> Back
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <form method="POST" action="ct_admin.php?action=edit&type=<?php echo urlencode($type_slug); ?>&id=<?php echo htmlspecialchars($S['id']); ?>">

            <div class="mb-3">
              <label class="form-label fw-bold">Level</label>
              <select name="level" class="form-control" required>
                <option value="">-- Select Level --</option>
                <option value="kid" <?php if ($S['level'] === 'kid') echo 'selected'; ?>>Kid</option>
                <option value="teen" <?php if ($S['level'] === 'teen') echo 'selected'; ?>>Teen</option>
                <option value="adult" <?php if ($S['level'] === 'adult') echo 'selected'; ?>>Adult</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Statement Text</label>
              <textarea name="statement" rows="5" class="form-control" required><?php echo htmlspecialchars($S['statement']); ?></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Active</label>
              <select name="is_active" class="form-control">
                <option value="1" <?php if ($S['is_active']) echo 'selected'; ?>>Active</option>
                <option value="0" <?php if (!$S['is_active']) echo 'selected'; ?>>Inactive</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="fa fa-save"></i> Save Changes
            </button>
            <a href="ct_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>" class="btn btn-outline-secondary ms-2">
              <i class="fa fa-times"></i> Cancel
            </a>
          </form>
        </div>
      </div>

    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>
<?php
} // end action=edit



/* ----------------------------------------------------
   ACTION: create — Add new statement (beautified)
   ---------------------------------------------------- */
if ($action === 'create' && $type_slug !== '') {

    $assignment_title = isset($mapping[$type_slug]) ? $mapping[$type_slug] : ucfirst($type_slug);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $level     = mysqli_real_escape_string($mysqli, $_POST['level']);
        $text      = mysqli_real_escape_string($mysqli, $_POST['statement']);
        $is_active = isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1;

        if ($text === '') {
            echo "<div class='alert alert-danger text-center mt-3'><i class='fa fa-exclamation-circle me-2'></i>Statement text cannot be empty.</div>";
        } else {
            // Resolve subassignment ID
            $subassign_id = null;
            $getSub = $mysqli->prepare("SELECT id FROM ct_subassignments WHERE assignment_id = ? LIMIT 1");
            if ($getSub) {
                $getSub->bind_param('s', $type_slug);
                $getSub->execute();
                $res = $getSub->get_result()->fetch_assoc();
                if ($res) $subassign_id = $res['id'];
                $getSub->close();
            }

            if (!$subassign_id) {
                echo "<div class='alert alert-danger text-center mt-3'><i class='fa fa-triangle-exclamation me-2'></i>No subassignment found for this assignment type.</div>";
            } else {
                $max_sort = 0;
                $getSort = $mysqli->prepare("SELECT COALESCE(MAX(sort_order),0) AS max_sort FROM ct_statements WHERE subassignment_id = ?");
                if ($getSort) {
                    $getSort->bind_param('s', $subassign_id);
                    $getSort->execute();
                    $res = $getSort->get_result()->fetch_assoc();
                    if ($res) $max_sort = (int)$res['max_sort'];
                    $getSort->close();
                }
                $next_sort = $max_sort + 1;

                $ins = $mysqli->prepare("
                    INSERT INTO ct_statements (subassignment_id, level, statement, sort_order, is_active, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                ");
                if ($ins) {
                    $ins->bind_param('sssii', $subassign_id, $level, $text, $next_sort, $is_active);
                    $ins->execute();
                    $ins->close();
                    header("Location: ct_admin.php?action=list&type=" . urlencode($type_slug) . "&msg=" . urlencode(base64_encode('Statement added successfully')));
                    exit;
                } else {
                    echo "<div class='alert alert-danger text-center mt-3'><i class='fa fa-bug me-2'></i>Database insert failed. Please try again.</div>";
                }
            }
        }
    }
?>
<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h3 class="mb-0"><i class="fa fa-plus-circle me-2 text-success"></i>Add New Statement for <i><?php echo htmlspecialchars($assignment_title); ?> Assignment</i> of Critical Thinking</h3>
                <p class="text-muted mb-0">Define a new statement for this assignment type and learner level.</p>
              </div>
              <div>
                <a href="ct_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>" class="btn btn-secondary">
                  <i class="fa fa-arrow-left"></i> Back
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <form method="POST" action="ct_admin.php?action=create&type=<?php echo urlencode($type_slug); ?>">

            <div class="mb-3">
              <label class="form-label fw-bold">
                <i class="fa fa-layer-group me-1 text-primary"></i> Level
              </label>
              <select name="level" class="form-control" required>
                <option value="">-- Select Level --</option>
                <option value="kid">Kid</option>
                <option value="teen">Teen</option>
                <option value="adult">Adult</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">
                <i class="fa fa-quote-left me-1 text-primary"></i> Statement Text
              </label>
              <textarea name="statement" rows="5" class="form-control" required placeholder="Enter the critical thinking statement here..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">
                <i class="fa fa-toggle-on me-1 text-primary"></i> Active Status
              </label>
              <select name="is_active" class="form-control">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-2">
                <i class="fa fa-save"></i> <span>Save Statement</span>
              </button>
              <a href="ct_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>" class="btn btn-outline-secondary ms-2 d-inline-flex align-items-center gap-2">
                <i class="fa fa-times"></i> <span>Cancel</span>
              </a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>
<?php
} // end action=create
