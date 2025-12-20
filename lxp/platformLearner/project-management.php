<?php
/**
 * Astraal LXP - learner_pbl_themes.php
 * PHP 5.4 Compatible — Final Unified Version
 * ✅ JSON-based Preview Restored
 * ✅ “In Progress” + “Grades” logic (5/5 milestones)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
  header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
  exit;
}

$phx_user_id = (int)$_SESSION['phx_user_id'];

/* Connection */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

/* ---------- AJAX endpoint ---------- */
if (isset($_GET['action']) && $_GET['action'] === 'check_has_attempts_all') {
  header('Content-Type: application/json; charset=utf-8');

  $themes = array('ai_everyday','digital_wellbeing','sustainability','entrepreneurship',
                  'cybersecurity','content_creation','robotics','problem_solving','future_skills');
  $result = array();
  foreach ($themes as $slug) $result[$slug] = array('completed'=>0,'inprogress'=>0);

  if ($mysqli instanceof mysqli) {
    foreach ($themes as $slug) {
      $sql = "SELECT problem_id, COUNT(DISTINCT milestone_no) AS milestone_count
              FROM pbl_submissions WHERE user_id=? AND theme_slug=? GROUP BY problem_id";
      if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('is', $phx_user_id, $slug);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
          $count = (int)$r['milestone_count'];
          if ($count >= 5) $result[$slug]['completed']++;
          elseif ($count > 0) $result[$slug]['inprogress']++;
        }
        $stmt->close();
      }
    }
  }

  echo json_encode(array('success'=>true,'status'=>$result));
  exit;
}

/* ---------- Page setup ---------- */
$page = "projectManagement";
require_once('learnerHead_Nav2.php');

/* ---------- Themes List ---------- */
$pblThemes = array(
  array('id'=>'ai_everyday','title'=>'AI for Everyday Life','impact'=>'How AI transforms creativity, decision-making, and learning.','why'=>'Builds ethical awareness & creative problem-solving in an AI-driven world.','skills'=>'<ul><li>AI Literacy</li><li>Ethical Thinking</li><li>Creativity</li></ul>','icon'=>'fa-robot'),
  array('id'=>'digital_wellbeing','title'=>'Digital Wellbeing & Mindful Tech','impact'=>'Design balanced relationships with technology.','why'=>'Promotes emotional health & digital discipline.','skills'=>'<ul><li>Self-Awareness</li><li>Digital Balance</li><li>Empathy</li></ul>','icon'=>'fa-heart'),
  array('id'=>'sustainability','title'=>'Sustainability & Climate Innovation','impact'=>'Create ideas for cleaner, greener cities.','why'=>'Encourages eco-conscious innovation and system thinking.','skills'=>'<ul><li>Design Thinking</li><li>Environmental Awareness</li></ul>','icon'=>'fa-leaf'),
  array('id'=>'entrepreneurship','title'=>'Entrepreneurship Challenge','impact'=>'Build and pitch your startup idea.','why'=>'Teaches resilience, creative risk-taking, and real-world execution.','skills'=>'<ul><li>Entrepreneurial Thinking</li><li>Strategic Problem-Solving</li></ul>','icon'=>'fa-lightbulb'),
  array('id'=>'cybersecurity','title'=>'Cybersecurity & Digital Safety','impact'=>'Defend data and promote responsible digital citizenship.','why'=>'Develops awareness of privacy, ethics, and safe online practices.','skills'=>'<ul><li>Risk Analysis</li><li>Privacy Awareness</li></ul>','icon'=>'fa-shield-halved'),
  array('id'=>'content_creation','title'=>'Creative Communication & Content Creation','impact'=>'Tell stories that engage, inform, and inspire.','why'=>'Combines media literacy with creativity & influence.','skills'=>'<ul><li>Storytelling</li><li>Media Design</li></ul>','icon'=>'fa-pen-nib'),
  array('id'=>'robotics','title'=>'Robotics & Automation','impact'=>'Design, code, and test robotic ideas.','why'=>'Bridges tech learning with future-of-work readiness.','skills'=>'<ul><li>Systems Thinking</li><li>Automation Logic</li></ul>','icon'=>'fa-cogs'),
  array('id'=>'problem_solving','title'=>'Problem-Solving at Work','impact'=>'Use design thinking to improve workflows.','why'=>'Builds collaboration, logic, and solution-oriented thinking.','skills'=>'<ul><li>Critical Thinking</li><li>Team Collaboration</li></ul>','icon'=>'fa-puzzle-piece'),
  array('id'=>'future_skills','title'=>'Future Skills & Career Readiness','impact'=>'Prepare for the jobs of tomorrow.','why'=>'Empowers adaptability and lifelong learning.','skills'=>'<ul><li>Collaboration</li><li>Adaptability</li></ul>','icon'=>'fa-graduation-cap')
);
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="card shadow-sm mb-4">
  <div class="card-body d-flex justify-content-between align-items-center">
    <div>
      <h1 class="h4 mb-1">Project Based Learning Themes Catalogue</h1>
      <p class="text-muted">Discover real-world learning themes. See their impact, preview problem statements, and build your portfolio.</p>
    </div>
    <i class="fa-solid fa-layer-group fa-3x text-primary"></i>
  </div>
</div>

<div class="row g-3">
<?php foreach($pblThemes as $th): ?>
  <div class="col-sm-6 col-md-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">
        <div class="d-flex align-items-center mb-3">
          <i class="fa-solid <?php echo htmlspecialchars($th['icon']); ?> fa-2x text-primary me-3"></i>
          <div>
            <h5 class="card-title mb-0"><?php echo htmlspecialchars($th['title']); ?></h5>
            <small class="text-muted"><?php echo htmlspecialchars($th['impact']); ?></small>
          </div>
        </div>

        <div class="mt-auto d-flex justify-content-between">
          <div>
         <!---   <button class="btn btn-outline-primary btn-sm btn-view"
              data-title="<?php //echo htmlspecialchars($th['title']); ?>"
              data-why="<?php //echo htmlspecialchars($th['why']); ?>"
              data-skills="<?php //echo htmlspecialchars($th['skills']); ?>">View</button>    ---->

            <button class="btn btn-primary btn-sm btn-preview"
              data-type-slug="<?php echo htmlspecialchars($th['id']); ?>"
              data-title="<?php echo htmlspecialchars($th['title']); ?>">Preview</button>
          </div>
          <div class="text-end">
            <button class="btn btn-sm btn-success btn-inprogress" data-type-slug="<?php echo htmlspecialchars($th['id']); ?>" style="display:none;">
              In&nbsp;Progress <span class="badge badge-count">0</span>
            </button>
            <button class="btn btn-sm btn-info btn-mygrades" data-type-slug="<?php echo htmlspecialchars($th['id']); ?>" style="display:none;">
              Grades <span class="badge badge-count">0</span>
            </button>
            <button class="btn btn-sm btn-success btn-start" data-type-slug="<?php echo htmlspecialchars($th['id']); ?>">Attempt</button>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<script>
(function(){
  var THEMES_JSON = [];

  /* ---------- Load Preview JSON ---------- */
  function loadJSON(callback){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'pbl_themes.json?rand=' + Math.random(), true);
    xhr.onreadystatechange = function(){
      if(xhr.readyState === 4 && xhr.status === 200){
        try { THEMES_JSON = JSON.parse(xhr.responseText); } catch(e){ console.log(e); }
        if(callback) callback();
      }
    };
    xhr.send();
  }

  
  
  function buildPreviewHtml(type){
  var html = "";
  var found = 0;
  for(var i=0; i<THEMES_JSON.length; i++){
    if(THEMES_JSON[i].theme_id === type){
      found++;
      html +=
        "<div class='mb-3 p-2 border rounded'>" +
          "<p><i class='fa fa-layer-group text-primary me-2'></i><strong>" + THEMES_JSON[i].level.toUpperCase() + " Problem:</strong> " +
          THEMES_JSON[i].statement + "</p>" +
          "<p><i class='fa fa-heart text-danger me-2'></i><strong>Why It Matters:</strong> " + THEMES_JSON[i].why + "</p>" +
          "<p><i class='fa fa-graduation-cap text-success me-2'></i><strong>Skills Focus:</strong> " + THEMES_JSON[i].skills.join(', ') + "</p>" +
        "</div>";
    }
  }
  if(!found)
    html = "<em><i class='fa fa-info-circle text-muted me-2'></i>No preview data available.</em>";
  return html;
}

function setupPreviewButtons(){
  var btns = document.getElementsByClassName('btn-preview');
  for(var i=0; i<btns.length; i++){
    btns[i].onclick = function(){
      var slug = this.getAttribute('data-type-slug');
      var title = this.getAttribute('data-title');
      var iconClass = this.closest('.card').querySelector('i.fa-solid').className;
      var html = buildPreviewHtml(slug);
      Swal.fire({
        title: "<i class='" + iconClass + " fa-lg text-primary'></i>" + title + " — Preview",
        html: "<div style='text-align:left; font-size:13px;'>" + html + "</div>",
        width: 700,
        showConfirmButton: false
      });
    };
  }
}


  function setupAttemptButtons(){
    var btns=document.getElementsByClassName('btn-start');
    for(var i=0;i<btns.length;i++){
      btns[i].onclick=function(){
        var theme=this.getAttribute('data-type-slug');
        if(theme) window.location.href="pbl_submit.php?theme="+encodeURIComponent(theme);
      };
    }
  }

  function setupProgressAndGrades(){
    var xhr=new XMLHttpRequest();
    xhr.open('GET', window.location.pathname+'?action=check_has_attempts_all&rand='+Math.random(), true);
    xhr.onreadystatechange=function(){
      if(xhr.readyState===4 && xhr.status===200){
        try{
          var data=JSON.parse(xhr.responseText);
          if(!data.success)return;
          var status=data.status;
          for(var slug in status){
            var st=status[slug];
            var completed=st.completed||0;
            var inprog=st.inprogress||0;
            var gradeBtn=document.querySelector('.btn-mygrades[data-type-slug="'+slug+'"]');
            var inProgBtn=document.querySelector('.btn-inprogress[data-type-slug="'+slug+'"]');

            if(completed>0){
              gradeBtn.style.display='';
              gradeBtn.querySelector('.badge-count').innerHTML=completed;
              gradeBtn.onclick=function(){
                var s=this.getAttribute('data-type-slug');
                window.location.href="pbl_grades_view.php?theme="+encodeURIComponent(s);
              };
            }
            if(inprog>0){
              inProgBtn.style.display='';
              inProgBtn.querySelector('.badge-count').innerHTML=inprog;
              inProgBtn.onclick=function(){
                var s=this.getAttribute('data-type-slug');
                window.location.href="pbl_submit.php?theme="+encodeURIComponent(s);
              };
            }
          }
        }catch(e){console.log(e);}
      }
    };
    xhr.send();
  }




  function setupViewButtons(){
    var btns=document.getElementsByClassName('btn-view');
    for(var i=0;i<btns.length;i++){
      btns[i].onclick=function(){
        Swal.fire({
          title:this.getAttribute('data-title'),
          html:"<div style='text-align:left;font-size:13px;'><strong>Why it matters:</strong> "+
                this.getAttribute('data-why')+"<br><strong>Skills Developed:</strong> "+
                this.getAttribute('data-skills')+"</div>",
          icon:'info'
        });
      };
    }
  }
  
  
  document.addEventListener('DOMContentLoaded',function(){
    loadJSON(function(){
      setupViewButtons();
      setupPreviewButtons();
      setupAttemptButtons();
      setupProgressAndGrades();
    });
  });
})();
</script>

<style>
.badge-count{background:#eef;padding:2px 6px;border-radius:999px;font-size:12px;}
.btn-inprogress{background:#ffc107;color:#000;border:none;}
.swal2-html-container{text-align:left!important;font-size:13px!important;color:#333;}
</style>

<?php require_once('../platformFooter.php'); ?>
