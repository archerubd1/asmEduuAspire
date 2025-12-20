<?php
/**
 * Astraal LXP - learner_pbl_attempts.php
 * v2 — Redirects to pbl_challenge.php after problem selection
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

/* MySQL Connection Fallback */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

/* Get theme slug from URL */
$theme_slug = isset($_GET['theme']) ? trim($_GET['theme']) : '';
if ($theme_slug == '') {
    header("Location: learner_pbl_themes.php");
    exit;
}

/* Fetch theme info */
$theme = null;
if ($mysqli instanceof mysqli) {
    $stmt = $mysqli->prepare("SELECT * FROM pbl_themes WHERE slug=? LIMIT 1");
    $stmt->bind_param('s', $theme_slug);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $theme = $res->fetch_assoc();
    }
    $stmt->close();
}

if (!$theme) {
    echo "<div style='padding:40px; font-family:sans-serif;'>
            <h3>Invalid Theme Selected</h3>
            <p><a href='learner_pbl_themes.php' style='color:#007bff;'>← Back to Project Management</a></p>
          </div>";
    exit;
}

/* AJAX endpoint for problems by level */
if (isset($_GET['action']) && $_GET['action'] == 'get_problems' && isset($_GET['level'])) {
    header('Content-Type: application/json');
    $level = trim($_GET['level']);
    $problems = [];

    if ($mysqli instanceof mysqli) {
        $stmt = $mysqli->prepare("SELECT * FROM pbl_problems WHERE theme_slug=? AND level=? ORDER BY id ASC");
        $stmt->bind_param('ss', $theme_slug, $level);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $problems[] = $row;
        }
        $stmt->close();
    }

    echo json_encode(['success'=>true, 'problems'=>$problems]);
    exit;
}

$page = "projectManagement";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <!-- Back Button -->
      <div class="mb-3">
        <a href="project-management.php" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-arrow-left"></i> Back to Project Management
        </a>
      </div>

      <!-- Theme Header -->
      <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-1"><?php echo htmlspecialchars($theme['title']); ?></h1>
            <p class="text-muted mb-0"><?php echo htmlspecialchars($theme['impact']); ?></p>
          </div>
          <i class="fa-solid <?php echo htmlspecialchars($theme['icon']); ?> fa-3x text-primary"></i>
        </div>
      </div>

      <!-- Level Selection -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3"><i class="fa-solid fa-layer-group me-2 text-primary"></i>Select Your Challenge Level</h5>

          <select id="levelSelect" class="form-select form-select-sm" style="max-width:300px;">
            <option value="">-- Choose Level --</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>
      </div>

      <!-- Problems List -->
      <div id="problemsContainer" class="mt-4"></div>

    </div>
  
<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<script>
(function(){
  const themeSlug = "<?php echo htmlspecialchars($theme_slug); ?>";
  const problemsContainer = document.getElementById('problemsContainer');

  function fetchProblems(level){
    problemsContainer.innerHTML = "<div class='text-muted'>Loading problems...</div>";
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'pbl_attempts.php?action=get_problems&theme=' + encodeURIComponent(themeSlug) + '&level=' + encodeURIComponent(level), true);
    xhr.onreadystatechange = function(){
      if(xhr.readyState === 4 && xhr.status === 200){
        try {
          const data = JSON.parse(xhr.responseText);
          if(data.success){ renderProblems(data.problems); }
          else { problemsContainer.innerHTML = "<div>No problems found.</div>"; }
        } catch(e){
          problemsContainer.innerHTML = "<div>Error loading problems.</div>";
        }
      }
    };
    xhr.send();
  }

  function renderProblems(problems){
    if(!problems.length){
      problemsContainer.innerHTML = "<div class='text-muted'>No problems available for this level.</div>";
      return;
    }

    let html = "<div class='row'>";
    for(let i=0;i<problems.length;i++){
      const p = problems[i];
      html += `
        <div class='col-md-6 mb-3'>
          <div class='card h-100 shadow-sm'>
            <div class='card-body'>
              <strong>Problem ${i+1}:</strong> ${p.statement}<br>
              <div class='text-muted small'><strong>Why:</strong> ${p.why}</div>
              <div class='text-muted small'><strong>Skills:</strong> ${p.skills}</div>
              <div class='mt-2 text-end'>
                <button class='btn btn-outline-primary btn-sm' onclick='viewProblem(${p.id})'>View</button>
                <button class='btn btn-success btn-sm' onclick='attemptProblem(${p.id})'>Attempt</button>
              </div>
            </div>
          </div>
        </div>`;
    }
    html += "</div>";
    problemsContainer.innerHTML = html;
  }

  document.getElementById('levelSelect').addEventListener('change', function(){
    const lvl = this.value;
    if(lvl) fetchProblems(lvl);
    else problemsContainer.innerHTML = '';
  });

  window.viewProblem = function(pid){
    const xhr = new XMLHttpRequest();
    const lvl = document.getElementById('levelSelect').value;
    xhr.open('GET', 'pbl_attempts.php?action=get_problems&theme=' + encodeURIComponent(themeSlug) + '&level=' + encodeURIComponent(lvl), false);
    xhr.send();
    if(xhr.status === 200){
      const data = JSON.parse(xhr.responseText);
      const p = data.problems.find(x => x.id == pid);
      if(!p) return;
      Swal.fire({
        title: "Problem Preview",
        html: `<div style='text-align:left; font-size:13px;'>
                 <div><strong>Statement:</strong> ${p.statement}</div>
                 <div><strong>Why:</strong> ${p.why}</div>
                 <div><strong>Skills:</strong> ${p.skills}</div>
               </div>`,
        icon: 'info'
      });
    }
  };

  window.attemptProblem = function(pid){
    const level = document.getElementById('levelSelect').value;
    if(!level){
      Swal.fire('Please select a level first.', '', 'warning');
      return;
    }
    window.location.href = "pbl_challenge.php?theme=" + encodeURIComponent(themeSlug) +
                           "&problem_id=" + encodeURIComponent(pid) +
                           "&level=" + encodeURIComponent(level);
  };
})();
</script>

<style>
.card-body { font-size: 14px; }
.text-muted.small { font-size: 12px; }
</style>

<?php require_once('../platformFooter.php'); ?>
