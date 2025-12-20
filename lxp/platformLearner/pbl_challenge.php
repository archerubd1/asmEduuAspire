<?php
/**
 * Astraal LXP - learner_pbl_challenge.php
 * FINAL POLISHED VERSION (PHP 5.4 + MySQL5)
 * Milestones spaced • Dummy FA resource icons • Level dropdown hidden on selection
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

/* --- Session Validation --- */
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    if (isset($_GET['action'])) {
        header('Content-Type: application/json');
        echo json_encode(array('success' => false, 'error' => 'Session expired'));
        exit;
    }
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

/* --- Force $coni Connection --- */
if (isset($coni) && $coni instanceof mysqli) {
    // ok
} elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) {
    $coni = $GLOBALS['coni'];
} else {
    die("<div style='padding:40px;font-family:sans-serif;'>
          <h3>Database connection missing</h3>
          <p>Ensure <code>\$coni</code> is defined in config.php</p>
        </div>");
}

/* --- Verify Connection --- */
if (!$coni || $coni->connect_errno) {
    die("<div style='padding:40px;font-family:sans-serif;'>
          <h3>Database Connection Error</h3>
          <p>" . htmlspecialchars($coni->connect_error) . "</p>
        </div>");
}

$theme_slug = isset($_GET['theme']) ? trim($_GET['theme']) : '';

/* ===========================================================
   ✅ JSON (AJAX MODE)
   =========================================================== */
if (isset($_GET['action'])) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    $action = $_GET['action'];

    if ($action == 'get_problems' && isset($_GET['level'])) {
        $level = trim($_GET['level']);
        $problems = array();
        $stmt = $coni->prepare("SELECT * FROM pbl_problems WHERE theme_slug=? AND level=? ORDER BY id ASC");
        $stmt->bind_param('ss', $theme_slug, $level);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $problems[] = $r;
        echo json_encode(array('success' => true, 'problems' => $problems));
        exit;
    }

    if ($action == 'get_milestones' && isset($_GET['theme_slug'])) {
        $slug = $_GET['theme_slug'];
        $milestones = array();
        $res = $coni->query("SELECT * FROM pbl_milestones WHERE theme_slug='" . $coni->real_escape_string($slug) . "' GROUP BY milestone_no ORDER BY milestone_no ASC");
        while ($r = $res->fetch_assoc()) $milestones[] = $r;
        echo json_encode(array('success' => true, 'milestones' => $milestones));
        exit;
    }
}

/* ===========================================================
   🌐 PAGE RENDER
   =========================================================== */
if ($theme_slug == '') {
    header("Location: project-management.php");
    exit;
}

$theme = null;
$stmt = $coni->prepare("SELECT * FROM pbl_themes WHERE slug=? LIMIT 1");
$stmt->bind_param('s', $theme_slug);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) $theme = $res->fetch_assoc();
$stmt->close();

if (!$theme) {
    echo "<div style='padding:40px;font-family:sans-serif;'>
            <h3>Invalid Theme Selected</h3>
            <p><a href='project-management.php' style='color:#007bff;'>← Back</a></p>
          </div>";
    exit;
}

$page = "projectManagement";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <a href="project-management.php" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fa fa-arrow-left"></i> Back
      </a>

      <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h3 class="h5 mb-1"><?php echo htmlspecialchars($theme['title']); ?></h3>
            <p class="text-muted mb-0"><?php echo htmlspecialchars($theme['impact']); ?></p>
          </div>
          <i class="fa <?php echo htmlspecialchars($theme['icon']); ?> fa-3x text-primary"></i>
        </div>
      </div>

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
  

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<script>
(function(){
  var themeSlug = "<?php echo htmlspecialchars($theme_slug); ?>";
  var levelCard = document.getElementById('levelCard');
  var problemsContainer = document.getElementById('problemsContainer');
  var challengeContainer = document.getElementById('challengeContainer');

  document.getElementById('levelSelect').addEventListener('change', function(){
    var level = this.value;
    challengeContainer.innerHTML = '';
    if(!level) { problemsContainer.innerHTML = ''; return; }
    fetchProblems(level);
  });

  function fetchProblems(level){
    problemsContainer.innerHTML = "<div class='text-muted'>Loading problems...</div>";
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'pbl_challenge.php?action=get_problems&theme=' + encodeURIComponent(themeSlug) + '&level=' + encodeURIComponent(level), true);
    xhr.onreadystatechange = function(){
      if(xhr.readyState==4 && xhr.status==200){
        var data = JSON.parse(xhr.responseText);
        if(data.success && data.problems.length) renderProblems(data.problems);
        else problemsContainer.innerHTML = "<div class='text-muted'>No problems found.</div>";
      }
    };
    xhr.send();
  }

  function renderProblems(problems){
    var html = "<h5 class='mb-3'>Select a Problem</h5><div class='row'>";
    for(var i=0;i<problems.length;i++){
      var p = problems[i];
      html += "<div class='col-md-6 mb-3'><div class='card h-100 shadow-sm'><div class='card-body'>" +
              "<strong>Problem " + (i+1) + ":</strong> " + p.statement + "<br>" +
              "<div class='text-muted small'><strong>Why:</strong> " + p.why + "</div>" +
              "<div class='text-muted small'><strong>Skills:</strong> " + p.skills + "</div>" +
              "<div class='mt-2 text-end'><button class='btn btn-outline-primary btn-sm' onclick='viewChallenge(" + p.id + ")'>View Challenge</button></div>" +
              "</div></div></div>";
    }
    html += "</div>";
    problemsContainer.innerHTML = html;
  }

  window.viewChallenge = function(problemId){
    levelCard.style.display='none';
    problemsContainer.innerHTML="";
    challengeContainer.innerHTML="<div class='text-muted'>Loading challenge...</div>";

    var xhr1 = new XMLHttpRequest();
    var xhr2 = new XMLHttpRequest();
    xhr1.open('GET', 'pbl_challenge.php?action=get_problems&theme=' + encodeURIComponent(themeSlug) + '&level=' + encodeURIComponent(document.getElementById('levelSelect').value), true);
    xhr2.open('GET', 'pbl_challenge.php?action=get_milestones&theme_slug=' + encodeURIComponent(themeSlug), true);
    var problemsData=null, milestoneData=null;

    xhr1.onload=function(){ problemsData=JSON.parse(xhr1.responseText); if(milestoneData) renderChallenge(problemId,problemsData,milestoneData); };
    xhr2.onload=function(){ milestoneData=JSON.parse(xhr2.responseText); if(problemsData) renderChallenge(problemId,problemsData,milestoneData); };
    xhr1.send(); xhr2.send();
  };

  function renderChallenge(problemId, problemsData, milestoneData){
    var problem=null;
    for(var i=0;i<problemsData.problems.length;i++){ if(problemsData.problems[i].id==problemId) problem=problemsData.problems[i]; }
    if(!problem){ challengeContainer.innerHTML="<div>Problem not found.</div>"; return; }

    var html="<div class='card shadow-sm mb-4'><div class='card-body'>" +
             "<h4><i class='fa fa-lightbulb text-primary me-2'></i>" + (problem.title||'Challenge') + "</h4>" +
             "<p><strong>Driving Question:</strong> " + problem.driving_question + "</p>" +
             "<p><strong>Context:</strong> " + problem.authentic_context + "</p>" +
             "<p><strong>Why:</strong> " + problem.why + "</p>" +
             "<p><strong>Skills:</strong> " + problem.skills + "</p></div></div>";

    if(milestoneData.milestones && milestoneData.milestones.length){
      html+="<h5 class='mb-3'><i class='fa fa-road text-primary me-2'></i>Milestone Journey</h5>"+
            "<ul class='nav nav-pills mb-3 justify-content-center' id='milestoneTabs' role='tablist' style='gap:10px;'>";
      for(var i=0;i<milestoneData.milestones.length;i++){
        var ms=milestoneData.milestones[i];
        html+="<li class='nav-item' style='margin:0 5px;' role='presentation'>" +
              "<button class='nav-link "+(i==0?'active':'')+"' id='tab"+ms.milestone_no+"' data-bs-toggle='pill' data-bs-target='#milestone"+ms.milestone_no+"' type='button'>" +
              "<i class='fa fa-flag me-1'></i>M"+ms.milestone_no+"</button></li>";
      }
      html+="</ul><div class='tab-content' id='milestoneTabsContent'>";
      for(var j=0;j<milestoneData.milestones.length;j++){
        var ms2=milestoneData.milestones[j];
        html+="<div class='tab-pane fade "+(j==0?'show active':'')+"' id='milestone"+ms2.milestone_no+"' role='tabpanel'>" +
              "<p>"+(ms2.description||'')+"</p>";
        if(ms2.deliverable) html+="<p><strong>Deliverable:</strong> "+ms2.deliverable+"</p>";
        html+="<h6>Resources</h6><div class='d-flex flex-wrap gap-3 justify-content-center mt-2'>" +
              "<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fa fa-video me-1'></i> Video</a>" +
              "<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fa fa-file-lines me-1'></i> Document</a>" +
              "<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fa fa-link me-1'></i> Link</a>" +
              "<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fa fa-briefcase me-1'></i> Case Study</a>" +
              "<a href='#' class='btn btn-sm btn-outline-secondary'><i class='fa fa-user-tie me-1'></i> Ask Mentor</a>" +
              "</div></div>";
      }
      html+="</div>";
    }

    html+="<div class='text-center mt-4'><a href='pbl_submit.php?problem_id="+problem.id+"&theme="+encodeURIComponent(themeSlug)+"' class='btn btn-success btn-lg'>Proceed to Submission <i class='fa fa-arrow-right ms-2'></i></a></div>";
    challengeContainer.innerHTML=html;
  }
})();
</script>

<style>
.card-body{font-size:14px;}
.text-muted.small{font-size:12px;}
.nav-pills .nav-link{border-radius:8px; padding:8px 15px;}
.nav-item{margin:0 5px;}
.btn-outline-secondary i{color:#555;}
.tab-content{border-top:1px solid #ddd; padding-top:15px;}
</style>

<?php require_once('../platformFooter.php'); ?>
