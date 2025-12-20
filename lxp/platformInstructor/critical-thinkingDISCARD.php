<?php
// File: instructor_ct_cards_sneat.php
// Sneat-aligned Instructor Critical Thinking dashboard
// PHP 5.4 compatible, uses $coni (mysqli_connect) from config.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');        // expects $coni
require_once('../../session-guard.php'); // session guard

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}
$phx_user_id = (int) $_SESSION['phx_user_id'];

// ensure $coni exists
if (!isset($coni) || (!is_resource($coni) && !($coni instanceof mysqli))) {
    die("Database connection \$coni not found. Ensure config.php defines \$coni = mysqli_connect(...).");
}

// CSRF
if (empty($_SESSION['ct_csrf'])) {
    $bytes = openssl_random_pseudo_bytes(16);
    $_SESSION['ct_csrf'] = bin2hex($bytes);
}
$CSRF = $_SESSION['ct_csrf'];

// small helper
function fetch_all_assoc($res) {
    $out = array();
    if (!$res) return $out;
    while ($r = mysqli_fetch_assoc($res)) $out[] = $r;
    return $out;
}

// JSON endpoints
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST['action'];
    $csrf_required = array('save_review','create_statement');

    if (in_array($action, $csrf_required)) {
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['ct_csrf']) {
            echo json_encode(array('error'=>'Invalid CSRF')); exit;
        }
    }

    try {
        if ($action === 'get_assignments') {
            $sql = "SELECT id, title, description, difficulty FROM ct_assignments WHERE is_active = 1 ORDER BY sort_order ASC";
            $res = mysqli_query($coni, $sql);
            echo json_encode(array('ok'=>1,'assignments'=>fetch_all_assoc($res))); exit;
        }

        if ($action === 'get_subassignments') {
            $aid = isset($_POST['assignment_id']) ? $_POST['assignment_id'] : '';
            $stmt = mysqli_prepare($coni, "SELECT id, title, instructions, response_type FROM ct_subassignments WHERE assignment_id = ? AND is_active = 1 ORDER BY id ASC");
            mysqli_stmt_bind_param($stmt, 's', $aid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $rows = fetch_all_assoc($res);
            mysqli_stmt_close($stmt);
            echo json_encode(array('ok'=>1,'subassignments'=>$rows)); exit;
        }

        if ($action === 'get_submissions') {
            $subid = isset($_POST['subassignment_id']) ? $_POST['subassignment_id'] : '';
            $sql = "SELECT q.id AS queue_id, q.submission_id, q.auto_score, q.ai_confidence, q.reason, q.status, q.created_at AS queued_at,
                           s.response_text, s.user_id, s.created_at AS submitted_at
                    FROM ct_review_queue q
                    LEFT JOIN ct_submissions s ON s.id = q.submission_id
                    WHERE q.subassignment_id = ?
                    ORDER BY q.created_at DESC";
            $stmt = mysqli_prepare($coni, $sql);
            mysqli_stmt_bind_param($stmt, 's', $subid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $rows = fetch_all_assoc($res);
            mysqli_stmt_close($stmt);

            foreach ($rows as $i => $r) {
                $rows[$i]['ai_feedback'] = null;
                $rows[$i]['instructor_review'] = null;
                if (!empty($r['submission_id'])) {
                    $sid = (int)$r['submission_id'];
                    $af = mysqli_prepare($coni, "SELECT * FROM ct_ai_feedback WHERE submission_id = ? ORDER BY created_at DESC LIMIT 1");
                    mysqli_stmt_bind_param($af, 'i', $sid);
                    mysqli_stmt_execute($af);
                    $afr = fetch_all_assoc(mysqli_stmt_get_result($af));
                    mysqli_stmt_close($af);
                    $rows[$i]['ai_feedback'] = isset($afr[0]) ? $afr[0] : null;

                    $ir = mysqli_prepare($coni, "SELECT * FROM ct_instructor_reviews WHERE submission_id = ? ORDER BY created_at DESC LIMIT 1");
                    mysqli_stmt_bind_param($ir, 'i', $sid);
                    mysqli_stmt_execute($ir);
                    $irr = fetch_all_assoc(mysqli_stmt_get_result($ir));
                    mysqli_stmt_close($ir);
                    $rows[$i]['instructor_review'] = isset($irr[0]) ? $irr[0] : null;
                }
            }

            echo json_encode(array('ok'=>1,'items'=>$rows)); exit;
        }

        if ($action === 'save_review') {
            $submission_id = isset($_POST['submission_id']) ? (int) $_POST['submission_id'] : 0;
            $instructor_score = isset($_POST['instructor_score']) && is_numeric($_POST['instructor_score']) ? (float) $_POST['instructor_score'] : null;
            $comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';

            $ins = mysqli_prepare($coni, "INSERT INTO ct_instructor_reviews (submission_id, instructor_id, rubric_override, instructor_score, comments, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $null_ro = null;
            mysqli_stmt_bind_param($ins, 'iidss', $submission_id, $phx_user_id, $null_ro, $instructor_score, $comments);
            mysqli_stmt_execute($ins);
            mysqli_stmt_close($ins);

            $up = mysqli_prepare($coni, "UPDATE ct_review_queue SET status = 'overridden', updated_at = NOW() WHERE submission_id = ?");
            mysqli_stmt_bind_param($up, 'i', $submission_id);
            mysqli_stmt_execute($up);
            mysqli_stmt_close($up);

            echo json_encode(array('ok'=>1,'message'=>'Saved')); exit;
        }

        if ($action === 'create_statement') {
            $subassignment_id = isset($_POST['subassignment_id']) ? $_POST['subassignment_id'] : '';
            $level = isset($_POST['level']) ? $_POST['level'] : 'adult';
            $statement_text = isset($_POST['statement']) ? trim($_POST['statement']) : '';
            if ($statement_text === '') { echo json_encode(array('error'=>'Statement required')); exit; }

            $mx = mysqli_prepare($coni, "SELECT COALESCE(MAX(sort_order),0) AS mx FROM ct_statements WHERE subassignment_id = ?");
            mysqli_stmt_bind_param($mx, 's', $subassignment_id);
            mysqli_stmt_execute($mx);
            $mxr = mysqli_stmt_get_result($mx);
            $mxrow = mysqli_fetch_assoc($mxr);
            mysqli_stmt_close($mx);
            $next = isset($mxrow['mx']) ? ((int)$mxrow['mx'] + 1) : 1;

            $ins = mysqli_prepare($coni, "INSERT INTO ct_statements (subassignment_id, level, statement, sort_order, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
            mysqli_stmt_bind_param($ins, 'sssi', $subassignment_id, $level, $statement_text, $next);
            mysqli_stmt_execute($ins);
            $newid = mysqli_insert_id($coni);
            mysqli_stmt_close($ins);

            echo json_encode(array('ok'=>1,'id'=>$newid,'message'=>'Created')); exit;
        }

        echo json_encode(array('error'=>'Unknown action'));
    } catch (Exception $e) {
        echo json_encode(array('error'=>'Server error','detail'=>$e->getMessage()));
    }
    exit;
}

// FRONT-END (Sneat header/nav expected to supply styles/scripts)
$page = "critical_thinking_cards";
require_once('instructorHead_Nav2.php');

?>

<!-- Layout container (Sneat) -->
<div class="layout-page">
  <?php require_once('instructorNav.php');  ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

       <div class="row">
	   
	   
        <div>
          <h4 class="fw-bold mb-0">Critical Thinking — Instructor</h4>
          <small class="text-muted">Manage assignments, review submissions (AI autoscore) and add statements inline</small>
        </div>
        <div>
          <button class="btn btn-sm btn-outline-secondary" onclick="loadAssignments();">Refresh</button>
        </div>
      </div>

      <div id="assignmentsRow" class="row gy-3"></div>

    </div>
 

<!-- Submissions modal (Bootstrap 5 per Sneat) -->
<div class="modal fade" id="submissionsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Submissions for <span id="modalSubTitle"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="submissionsModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Minimal inline tweaks only (no external CSS) -->
<style>
/* tiny spacing tweaks, Sneat styles remain authoritative */
.card-action { margin-top: .75rem; }
.statement-form { margin-top: .75rem; }
.small-muted { font-size: .9rem; color: #6c757d; }
</style>

<script>
var CSRF = "<?php echo $CSRF; ?>";
function esc(s){ if (s===null||s===undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function loadAssignments(){
  var data = new URLSearchParams(); data.append('action','get_assignments');
  fetch('', {method:'POST', body: data})
    .then(r=>r.json()).then(d=>{
      if (d.ok) renderAssignmentCards(d.assignments);
      else console.error(d);
    });
}

function renderAssignmentCards(assignments){
  var row = document.getElementById('assignmentsRow'); row.innerHTML = '';
  if (!assignments || assignments.length === 0) {
    row.innerHTML = '<div class="col-12"><div class="alert alert-info mb-0">No CT assignments found.</div></div>';
    return;
  }

  assignments.forEach(function(a){
    var col = document.createElement('div'); col.className = 'col-md-6';
    col.innerHTML = ''
      + '<div class="card shadow-sm">'
      +   '<div class="card-body">'
      +     '<div class="d-flex justify-content-between align-items-start">'
      +       '<div>'
      +         '<h5 class="card-title mb-1">'+esc(a.title)+'</h5>'
      +         '<p class="small-muted mb-0">'+esc(a.description || '')+'</p>'
      +       '</div>'
      +       '<div class="text-end">'
      +         '<span class="badge bg-primary">'+esc(a.difficulty || '—')+'</span>'
      +       '</div>'
      +     '</div>'
      +     '<div class="card-action">'
      +       '<button class="btn btn-sm btn-outline-primary me-1" onclick="toggleSubassignments(this, \''+esc(a.id)+'\')">Subassignments</button>'
      +       '<button class="btn btn-sm btn-outline-success me-1" onclick="openStatementManager(\''+esc(a.id)+'\')">Manage Statements</button>'
      +     '</div>'
      +     '<div id="subwrap-'+esc(a.id)+'" class="mt-3" style="display:none"></div>'
      +   '</div>'
      + '</div>';
    row.appendChild(col);
  });
}

function toggleSubassignments(btn, assignmentId){
  var wrap = document.getElementById('subwrap-'+assignmentId);
  if (wrap.style.display === 'block') { wrap.style.display = 'none'; wrap.innerHTML = ''; return; }
  var data = new URLSearchParams(); data.append('action','get_subassignments'); data.append('assignment_id', assignmentId);
  fetch('', {method:'POST', body: data})
    .then(r=>r.json()).then(d=>{
      if (d.ok) {
        var html = '<div class="list-group">';
        d.subassignments.forEach(function(s){
          html += '<div class="list-group-item d-flex justify-content-between align-items-center">'
               + '<div><strong>'+esc(s.id)+'</strong> — '+esc(s.title)+'<div class="small-muted">'+esc(s.instructions||'')+'</div></div>'
               + '<div>'
               +   '<button class="btn btn-sm btn-primary me-1" onclick="viewSubmissions(\''+esc(s.id)+'\',\''+esc(s.title)+'\')">View Submissions</button>'
               +   '<button class="btn btn-sm btn-outline-secondary" onclick="showStatementForm(\''+esc(s.id)+'\')">Statements</button>'
               + '</div>'
               + '</div>';
        });
        html += '</div>';
        wrap.innerHTML = html; wrap.style.display = 'block';
      } else {
        wrap.innerHTML = '<div class="alert alert-warning mb-0">Unable to load subassignments.</div>'; wrap.style.display='block';
      }
    });
}

function viewSubmissions(subassignmentId, title){
  document.getElementById('modalSubTitle').textContent = title || subassignmentId;
  var data = new URLSearchParams(); data.append('action','get_submissions'); data.append('subassignment_id', subassignmentId);
  fetch('', {method:'POST', body: data})
    .then(r=>r.json()).then(d=>{
      if (d.ok) {
        renderSubmissionsModal(d.items);
        var m = new bootstrap.Modal(document.getElementById('submissionsModal'));
        m.show();
      } else alert(d.error || 'Error');
    });
}

function renderSubmissionsModal(items){
  var body = document.getElementById('submissionsModalBody'); body.innerHTML = '';
  if (!items || items.length === 0) { body.innerHTML = '<div class="alert alert-info mb-0">No submissions yet.</div>'; return; }

  items.forEach(function(it){
    var ai_fb = it.ai_feedback ? (it.ai_feedback.feedback_json || it.ai_feedback.score_suggestion || '') : '';
    var auto = (it.auto_score !== null && it.auto_score !== undefined) ? it.auto_score + '%' : '—';
    var instr = it.instructor_review ? (it.instructor_review.instructor_score + ' — ' + (it.instructor_review.comments||'')) : '';

    var card = document.createElement('div'); card.className = 'card mb-3';
    card.innerHTML = '<div class="card-body">'
      + '<div class="d-flex justify-content-between"><div><strong>Submission #'+esc(it.submission_id)+'</strong> <small class="text-muted">('+(it.submitted_at||'')+')</small></div><div><strong>Auto:</strong> '+esc(auto)+'</div></div>'
      + '<pre class="mt-2" style="background:#f8f9fa;padding:10px;border-radius:.4rem;">'+esc(it.response_text||'')+'</pre>'
      + '<div class="mt-2 small-muted"><strong>AI feedback:</strong> '+esc(ai_fb)+'</div>'
      + '<div class="row g-2 align-items-center mt-3">'
      +   '<div class="col-md-3"><label class="form-label">Instructor score (%)</label><input type="number" min="0" max="100" class="form-control ins-score" data-sub="'+esc(it.submission_id)+'" value="'+(it.instructor_review && it.instructor_review.instructor_score !== null ? esc(it.instructor_review.instructor_score) : '')+'"></div>'
      +   '<div class="col-md-6"><label class="form-label">Comments</label><input class="form-control ins-comments" data-sub="'+esc(it.submission_id)+'" value="'+esc(it.instructor_review && it.instructor_review.comments ? it.instructor_review.comments : '')+'"></div>'
      +   '<div class="col-md-3 text-end"><button class="btn btn-success" onclick="saveReview('+esc(it.submission_id)+')">Save</button></div>'
      + '</div></div>';
    body.appendChild(card);
  });
}

function saveReview(submissionId){
  var scoreEl = document.querySelector('.ins-score[data-sub="'+submissionId+'"]');
  var commentsEl = document.querySelector('.ins-comments[data-sub="'+submissionId+'"]');
  var score = scoreEl ? scoreEl.value : '';
  var comments = commentsEl ? commentsEl.value : '';
  var data = new URLSearchParams();
  data.append('action','save_review');
  data.append('submission_id', submissionId);
  data.append('instructor_score', score);
  data.append('comments', comments);
  data.append('csrf', CSRF);
  fetch('', {method:'POST', body: data})
    .then(r=>r.json()).then(d=>{ if (d.ok) { alert('Saved'); } else alert(d.error || 'Error saving'); });
}

// Statement manager helpers
function openStatementManager(assignmentId, subassignmentId){
  if (subassignmentId) { showStatementForm(subassignmentId); return; }
  var data = new URLSearchParams(); data.append('action','get_subassignments'); data.append('assignment_id', assignmentId);
  fetch('', {method:'POST', body: data}).then(r=>r.json()).then(d=>{
    if (d.ok) {
      var html = '<div class="card mb-3"><div class="card-body"><h6 class="mb-2">Subassignments</h6><div class="list-group">';
      d.subassignments.forEach(function(s){
        html += '<div class="list-group-item d-flex justify-content-between align-items-center"><div><strong>'+esc(s.id)+'</strong> — '+esc(s.title)+'</div><div><button class="btn btn-sm btn-outline-primary" onclick="showStatementForm(\''+esc(s.id)+'\')">Manage</button></div></div>';
      });
      html += '</div></div></div>';
      var container = document.querySelector('.container-xxl');
      var tmp = document.createElement('div'); tmp.innerHTML = html;
      container.insertBefore(tmp, container.firstChild);
      window.scrollTo(0,0);
    } else alert('Unable to load');
  });
}

function showStatementForm(subassignmentId){
  var existing = document.getElementById('stmtFormWrap-'+subassignmentId);
  if (existing) { existing.parentNode.removeChild(existing); return; }
  var html = '<div id="stmtFormWrap-'+esc(subassignmentId)+'" class="card mb-3 statement-form"><div class="card-body">'
           + '<h6 class="mb-2">Manage statements for '+esc(subassignmentId)+'</h6>'
           + '<div class="row g-2"><div class="col-md-3"><label class="form-label">Level</label><select id="stmt_level_'+esc(subassignmentId)+'" class="form-control"><option value="kid">kid</option><option value="teen">teen</option><option value="adult">adult</option></select></div>'
           + '<div class="col-md-9"><label class="form-label">Statement</label><textarea id="stmt_text_'+esc(subassignmentId)+'" class="form-control" rows="2"></textarea></div></div>'
           + '<div class="mt-2"><button class="btn btn-primary" onclick="createStatement(\''+esc(subassignmentId)+'\')">Create</button> <button class="btn btn-link" onclick="document.getElementById(\'stmtFormWrap-'+esc(subassignmentId)+'\').remove()">Close</button></div>'
           + '<div id="stmtMsg_'+esc(subassignmentId)+'" class="mt-2"></div></div></div>';
  var container = document.querySelector('.container-xxl');
  var tmp = document.createElement('div'); tmp.innerHTML = html;
  container.insertBefore(tmp, container.firstChild);
  window.scrollTo(0,0);
}

function createStatement(subassignmentId){
  var level = document.getElementById('stmt_level_'+subassignmentId).value;
  var text = document.getElementById('stmt_text_'+subassignmentId).value;
  var data = new URLSearchParams();
  data.append('action','create_statement');
  data.append('subassignment_id', subassignmentId);
  data.append('level', level);
  data.append('statement', text);
  data.append('csrf', CSRF);
  fetch('', {method:'POST', body: data}).then(r=>r.json()).then(d=>{
    var msg = document.getElementById('stmtMsg_'+subassignmentId);
    if (d.ok) { msg.innerHTML = '<div class="alert alert-success">Saved (id:'+d.id+')</div>'; document.getElementById('stmt_text_'+subassignmentId).value = ''; }
    else msg.innerHTML = '<div class="alert alert-danger">'+(d.error||'Error')+'</div>';
  });
}

document.addEventListener('DOMContentLoaded', function(){ loadAssignments(); });
</script>

<?php require_once('../platformFooter.php'); ?>
