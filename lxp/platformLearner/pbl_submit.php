<?php
/**
 * Astraal LXP - learner_pbl_submit.php
 * FINAL FULL VERSION — PHP 5.4 + MySQL5 COMPATIBLE
 * ✅ Uses $coni connection
 * ✅ Returns clean JSON (no more "Unexpected token <" errors)
 * ✅ milestone_no included in pbl_submissions
 * ✅ FA icons + tooltips intact
 * ✅ My Submissions Summary Card integrated
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id = (int)$_SESSION['phx_user_id'];

/* ---------------- DB CONNECTION ---------------- */
$coni = isset($coni) ? $coni :
        (isset($GLOBALS['coni']) ? $GLOBALS['coni'] :
        (isset($GLOBALS['mysqli']) ? $GLOBALS['mysqli'] :
        (isset($conn) ? $conn : null)));

if (!$coni || $coni->connect_errno) {
    die("<div style='padding:40px;font-family:sans-serif;'>Database connection error</div>");
}

/* ---------------- AJAX MODE ---------------- */
if (isset($_GET['action'])) {
    if (ob_get_length()) ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');

    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $theme_for_ajax = '';
    if (isset($_GET['theme'])) $theme_for_ajax = trim($_GET['theme']);
    elseif (isset($_GET['theme_slug'])) $theme_for_ajax = trim($_GET['theme_slug']);

    if (!$coni || $coni->connect_errno) {
        echo json_encode(array('success'=>false,'error'=>'DB connection failed'));
        exit;
    }

    // Fetch problems for theme + level
    if ($action == 'get_problems' && isset($_GET['level'])) {
        $level = trim($_GET['level']);
        $problems = array();
        if ($theme_for_ajax != '' && $level != '') {
            $stmt = $coni->prepare("SELECT * FROM pbl_problems WHERE theme_slug=? AND level=? ORDER BY id ASC");
            $stmt->bind_param('ss', $theme_for_ajax, $level);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $problems[] = $r;
            $stmt->close();
            echo json_encode(array('success'=>true,'problems'=>$problems));
        } else {
            echo json_encode(array('success'=>false,'error'=>'Missing theme or level'));
        }
        exit;
    }

    // Fetch milestones for theme
    if ($action == 'get_milestones' && $theme_for_ajax != '') {
        $milestones = array();
        $res = $coni->query("SELECT * FROM pbl_milestones WHERE theme_slug='" . $coni->real_escape_string($theme_for_ajax) . "' GROUP BY milestone_no ORDER BY milestone_no ASC");
        while ($r = $res->fetch_assoc()) $milestones[$r['milestone_no']] = $r;
        echo json_encode(array('success'=>true,'milestones'=>$milestones));
        exit;
    }

    echo json_encode(array('success'=>false,'error'=>'Invalid action'));
    exit;
}

/* ---------------- SUBMISSION HANDLER ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['milestone_no'])) {
    $milestone_no = (int)$_POST['milestone_no'];
    $theme_slug   = isset($_POST['theme_slug']) ? trim($_POST['theme_slug']) : '';
    $problem_id   = isset($_POST['problem_id']) ? (int)$_POST['problem_id'] : 0;
    $level        = isset($_POST['level']) ? trim($_POST['level']) : '';
    $reflection   = isset($_POST['reflection']) ? trim($_POST['reflection']) : '';
    $submission_link = isset($_POST['submission_link']) ? trim($_POST['submission_link']) : '';
    $attachment_name = null;

    if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === 0) {
        $uploadDir = "../../uploads/pbl_submissions/";
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        $attachment_name = time() . "_" . basename($_FILES['submission_file']['name']);
        move_uploaded_file($_FILES['submission_file']['tmp_name'], $uploadDir . $attachment_name);
    }

    $response = "Link: " . $submission_link . "\nReflection: " . $reflection;

    $stmt = $coni->prepare("INSERT INTO pbl_submissions (user_id, theme_slug, problem_id, milestone_no, level, response, attachment, submitted_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("isiisss", $phx_user_id, $theme_slug, $problem_id, $milestone_no, $level, $response, $attachment_name);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Milestone M{$milestone_no} submitted successfully!');window.location.href='pbl_submit.php?theme=" . urlencode($theme_slug) . "';</script>";
    exit;
}

/* ---------------- FETCH SUBMISSION SUMMARY ---------------- */
$theme_slug = isset($_GET['theme']) ? trim($_GET['theme']) : '';
if ($theme_slug == '') {
    die("<div style='padding:40px;font-family:sans-serif;'><h3>Invalid Theme Selected</h3><p><a href='project-management.php'>← Back</a></p></div>");
}

$theme = null;
$stmt = $coni->prepare("SELECT * FROM pbl_themes WHERE slug=? LIMIT 1");
$stmt->bind_param('s', $theme_slug);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) $theme = $res->fetch_assoc();
$stmt->close();

if (!$theme) {
    echo "<div style='padding:40px;font-family:sans-serif;'>Invalid Theme.</div>";
    exit;
}

/* ---------------- SUBMISSIONS SUMMARY CARD ---------------- */
$submissions = array();
$q = "SELECT problem_id, level, milestone_no, submitted_at FROM pbl_submissions 
      WHERE user_id=$phx_user_id AND theme_slug='" . $coni->real_escape_string($theme_slug) . "'";
$res = $coni->query($q);
if ($res) while ($r = $res->fetch_assoc()) $submissions[] = $r;

$submittedProblems = array();
foreach ($submissions as $s) {
    $pid = $s['problem_id'];
    if (!isset($submittedProblems[$pid])) {
        $submittedProblems[$pid] = array('level'=>$s['level'], 'milestones'=>array());
    }
    $submittedProblems[$pid]['milestones'][] = $s['milestone_no'];
}

$problemTitles = array();
if (count($submittedProblems)) {
    $pids = implode(',', array_keys($submittedProblems));
    $res2 = $coni->query("SELECT id, statement FROM pbl_problems WHERE id IN ($pids)");
    while ($r2 = $res2->fetch_assoc()) $problemTitles[$r2['id']] = $r2['statement'];
}

$page = "projectManagement";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

  <!-- HEADER -->
  <div class="card shadow-sm mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <h3 class="h5 mb-1">Project Based Learning Theme: <i><?php echo htmlspecialchars($theme['title']); ?></i></h3>
        <p class="text-muted mb-0"><?php echo htmlspecialchars($theme['impact']); ?></p>
      </div>
      <i class="fa <?php echo htmlspecialchars($theme['icon']); ?> fa-3x text-primary"></i>
    </div>
  </div>

  <!-- SUBMISSION SUMMARY CARD -->
  <?php if (count($submittedProblems)): ?>
  <div class="card border-success shadow-sm mb-4">
    <div class="card-body">
      <h5 class="mb-3 text-success"><i class="fa fa-clipboard-check me-2"></i>My Submissions Summary</h5>
      <?php 
        $keys = array_keys($submittedProblems);
        $lastKey = end($keys);
        foreach ($submittedProblems as $pid=>$info): ?>
        <div class="mb-3">
          <p><strong>Level:</strong> <?php echo ucfirst($info['level']); ?></p>
          <p><strong>Problem:</strong> <?php echo htmlspecialchars(substr($problemTitles[$pid], 0, 120)); ?>...</p>
          <p>
            <strong>Submitted Milestones:</strong>
            <?php for ($i=1; $i<=5; $i++): ?>
              <?php if (in_array($i, $info['milestones'])): ?>
                <i class="fa fa-check-circle text-success me-2" title="M<?php echo $i; ?> Submitted"></i>
              <?php else: ?>
                <i class="fa fa-circle text-muted me-2" title="M<?php echo $i; ?> Pending"></i>
              <?php endif; ?>
            <?php endfor; ?>
          </p>
        </div>
        <?php if ($pid != $lastKey): ?><hr><?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- LEVEL SELECT -->
  <div id="levelCard" class="card shadow-sm mb-3">
    <div class="card-body">
      <h5 class="mb-3"><i class="fa fa-layer-group text-primary me-2"></i>Select Level</h5>
      <select id="levelSelect" class="form-select form-select-sm" style="max-width:300px;">
        <option value="">-- Choose Level --</option>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
      </select>
    </div>
  </div>

  <div id="problemsContainer"></div>
  <div id="challengeContainer" class="mt-4"></div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function(){
  var themeSlug = "<?php echo htmlspecialchars($theme_slug); ?>";
  var levelCard = document.getElementById('levelCard');
  var problemsContainer = document.getElementById('problemsContainer');
  var challengeContainer = document.getElementById('challengeContainer');

  document.getElementById('levelSelect').addEventListener('change', function(){
    var level = this.value;
    challengeContainer.innerHTML = '';
    if(!level) return problemsContainer.innerHTML = '';
    fetchProblems(level);
  });

  function fetchProblems(level){
    problemsContainer.innerHTML = "<div class='text-muted'>Loading problems...</div>";
    fetch("pbl_submit.php?action=get_problems&theme=" + encodeURIComponent(themeSlug) + "&level=" + encodeURIComponent(level))
    .then(r=>r.json()).then(data=>{
      if(data.success && data.problems.length) renderProblems(data.problems);
      else problemsContainer.innerHTML = "<div class='text-muted'>No problems found.</div>";
    });
  }

  function renderProblems(problems){
    var html = "<h5 class='mb-3'><i class='fa fa-question-circle text-primary me-2'></i>Select a Problem Statement</h5><div class='row'>";
    for(var i=0;i<problems.length;i++){
      var p = problems[i];
      html += "<div class='col-md-6 mb-3'><div class='card h-100 shadow-sm'><div class='card-body'>" +
              "<strong>Problem " + (i+1) + ":</strong> " + p.statement + "<br>" +
              "<div class='text-muted small'><strong>Why:</strong> " + p.why + "</div>" +
              "<div class='text-muted small'><strong>Skills:</strong> " + p.skills + "</div>" +
              "<div class='mt-2 text-end'>" +
              "<button class='btn btn-outline-primary btn-sm' onclick='viewChallenge(" + p.id + ")'>Select</button>" +
              "</div></div></div></div>";
    }
    html += "</div>";
    problemsContainer.innerHTML = html;
  }

  window.viewChallenge = function(problemId){
    levelCard.style.display='none';
    problemsContainer.innerHTML = '';
    challengeContainer.innerHTML = "<div class='text-muted'>Loading...</div>";

    Promise.all([
      fetch("pbl_submit.php?action=get_problems&theme=" + encodeURIComponent(themeSlug) + "&level=" + encodeURIComponent(document.getElementById('levelSelect').value)).then(r=>r.json()),
      fetch("pbl_submit.php?action=get_milestones&theme_slug=" + encodeURIComponent(themeSlug)).then(r=>r.json())
    ]).then(function(res){
      var problemsData = res[0];
      var milestoneData = res[1];
      var problem = null;
      for(var i=0;i<problemsData.problems.length;i++){ if(problemsData.problems[i].id==problemId) problem = problemsData.problems[i]; }
      renderChallenge(problem, milestoneData.milestones);
    });
  }

  function renderChallenge(problem, milestones){
    var html = "<div class='card shadow-sm mb-4'><div class='card-body'>" +
      "<h4><i class='fa fa-lightbulb text-primary me-2'></i>" + (problem.title || 'Challenge') + "</h4>" +
      "<p><i class='fa fa-question-circle text-primary me-2'></i><strong>Driving Question:</strong> " + problem.driving_question + "</p>" +
      "<p><i class='fa fa-globe text-info me-2'></i><strong>Context:</strong> " + problem.authentic_context + "</p>" +
      "<p><i class='fa fa-heart text-danger me-2'></i><strong>Why:</strong> " + problem.why + "</p>" +
      "<p><i class='fa fa-graduation-cap text-success me-2'></i><strong>Skills:</strong> " + problem.skills + "</p>" +
      "</div></div>";

    if(Object.keys(milestones).length){
      html += "<h4 class='mb-3'><i class='fa fa-road text-primary me-2'></i>Your Project Milestones</h4><div class='accordion' id='submitAccordion'>";
      var mNos = Object.keys(milestones);
      for(var i=0;i<mNos.length;i++){
        var ms = milestones[mNos[i]];
        html += "<div class='accordion-item mb-2'><h2 class='accordion-header'>" +
          "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse" + ms.milestone_no + "'>" +
          "M" + ms.milestone_no + " — " + ms.title + "</button></h2>" +
          "<div id='collapse" + ms.milestone_no + "' class='accordion-collapse collapse'><div class='accordion-body'>" +
          "<p>" + ms.description + "</p>" +
          "<h6>Resources</h6><div class='d-flex flex-wrap gap-4 fs-4'>" +
          "<i class='fa fa-video text-danger' title='Video Resource' data-bs-toggle='tooltip'></i>" +
          "<i class='fa fa-file-alt text-primary' title='Document Resource' data-bs-toggle='tooltip'></i>" +
          "<i class='fa fa-link text-success' title='Reference Link' data-bs-toggle='tooltip'></i>" +
          "<i class='fa fa-briefcase text-warning' title='Case Study' data-bs-toggle='tooltip'></i>" +
          "<i class='fa fa-user-tie text-info' title='Ask a Mentor' data-bs-toggle='tooltip'></i></div>" +

          "<form method='post' enctype='multipart/form-data' class='mt-3 border-top pt-3'>" +
          "<input type='hidden' name='theme_slug' value='" + themeSlug + "'>" +
          "<input type='hidden' name='problem_id' value='" + problem.id + "'>" +
          "<input type='hidden' name='level' value='" + problem.level + "'>" +
          "<input type='hidden' name='milestone_no' value='" + ms.milestone_no + "'>" +
          "<div class='mb-2'><label><strong>Upload Deliverable:</strong></label><input type='file' name='submission_file' class='form-control form-control-sm'></div>" +
          "<div class='mb-2'><label><strong>Share a Link:</strong></label><input type='url' name='submission_link' class='form-control form-control-sm' placeholder='https://...'></div>" +
          "<div class='mb-2'><label><strong>Reflection / Notes:</strong></label><textarea name='reflection' rows='3' class='form-control'></textarea></div>" +
          "<button type='submit' class='btn btn-success btn-sm'>Submit Milestone</button></form></div></div></div>";
      }
      html += "</div>";
    }

    challengeContainer.innerHTML = html;
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });
  }
})();
</script>

<style>
.card-body { font-size:14px; }
.text-muted.small { font-size:12px; }
.fs-4 i { margin-right:15px; cursor:pointer; font-size:20px; }
</style>

<?php require_once('../platformFooter.php'); ?>
