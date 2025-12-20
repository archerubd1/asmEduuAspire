<?php
/**
 * Astraal LXP - worklifeExperience.php
 * PHP 5.4 Compatible — Work–Life Experience Frameworks Catalogue
 * ✅ View CTA (Why + Core Skills)
 * ✅ JSON-based Preview (Gen Z / Millennials / Gen X)
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

  // Work–Life Experience frameworks (theme slugs)
  $frameworks = array(
    'foundational_workplace_readiness',      // FWRF
    'technical_domain_experience',           // TDEF
    'problem_solving_decision_making',       // PSDM
    'collab_leadership_development',         // CLDF
    'performance_capability_acceleration',   // PCAF
    'worklife_integration_wellbeing',        // WLIWF
    'industry_immersion_exposure',           // IIREF
    'continuous_learning_future_skills',     // CLFSF
    'behavioural_intel_prof_maturity'        // BIPMF
  );

  $result = array();
  foreach ($frameworks as $slug) {
    $result[$slug] = array('completed' => 0, 'inprogress' => 0);
  }

  if ($mysqli instanceof mysqli) {
    foreach ($frameworks as $slug) {
      // Adjust table name if needed (worklife_submissions assumed here)
      $sql = "SELECT problem_id, COUNT(DISTINCT milestone_no) AS milestone_count
              FROM worklife_submissions
              WHERE user_id=? AND framework_slug=?
              GROUP BY problem_id";
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

  echo json_encode(array('success' => true, 'status' => $result));
  exit;
}

/* ---------- Page setup ---------- */
$page = "workLifeExperience";
require_once('learnerHead_Nav2.php');

/* ---------- Work–Life Experience Frameworks List (9 Frameworks) ---------- */
$worklifeFrameworks = array(
  array(
    'id'    => 'foundational_workplace_readiness',
    'title' => 'Foundational Workplace Readiness',
    'impact'=> 'Builds core professional behaviour, etiquette, and compliance-first thinking.',
    'why'   => 'Ensures learners enter any workplace with discipline, clarity, and a compliance-aligned mindset.',
    'skills'=> '<ul><li>Professional Etiquette</li><li>Documentation Hygiene</li><li>Compliance Awareness</li></ul>',
    'icon'  => 'fa-briefcase'
  ),
  array(
    'id'    => 'technical_domain_experience',
    'title' => 'Technical & Domain Experience ',
    'impact'=> 'Moves learners from theory to hands-on, production-oriented skills.',
    'why'   => 'Connects classroom learning with real projects, tools, and domain workflows across industries.',
    'skills'=> '<ul><li>Applied Technical Skills</li><li>Domain Understanding</li><li>Tool Proficiency</li></ul>',
    'icon'  => 'fa-microchip'
  ),
  array(
    'id'    => 'problem_solving_decision_making',
    'title' => 'Problem-Solving & Decision-Making ',
    'impact'=> 'Builds structured thinking and audit-ready decision trails.',
    'why'   => 'Helps learners justify decisions, reduce rework, and operate in regulated or high-stakes environments.',
    'skills'=> '<ul><li>Critical Thinking</li><li>Analytical Reasoning</li><li>Judgment & Justification</li></ul>',
    'icon'  => 'fa-lightbulb'
  ),
  array(
    'id'    => 'collab_leadership_development',
    'title' => 'Collaboration & Leadership Development ',
    'impact'=> 'Develops collaboration, ownership, and people leadership capabilities.',
    'why'   => 'Prepares learners to work in teams, handle conflicts, and grow into credible leaders.',
    'skills'=> '<ul><li>Team Collaboration</li><li>Influence & Leadership</li><li>Stakeholder Communication</li></ul>',
    'icon'  => 'fa-people-arrows'
  ),
  array(
    'id'    => 'performance_capability_acceleration',
    'title' => 'Performance & Capability Acceleration ',
    'impact'=> 'Makes growth visible through skills analytics and feedback loops.',
    'why'   => 'Transforms learning into measurable progress and supports role readiness and promotions.',
    'skills'=> '<ul><li>Self-Assessment</li><li>Goal Setting</li><li>Performance Tracking</li></ul>',
    'icon'  => 'fa-chart-line'
  ),
  array(
    'id'    => 'worklife_integration_wellbeing',
    'title' => 'Work–Life Integration & Wellbeing ',
    'impact'=> 'Builds sustainable routines and resilience for long-term careers.',
    'why'   => 'Helps learners manage stress, energy, and responsibilities without burnout.',
    'skills'=> '<ul><li>Time & Energy Management</li><li>Resilience</li><li>Self-Care Practices</li></ul>',
    'icon'  => 'fa-heart-pulse'
  ),
  array(
    'id'    => 'industry_immersion_exposure',
    'title' => 'Industry Immersion & Real-World Exposure ',
    'impact'=> 'Exposes learners to real scenarios, casework, and industry expectations.',
    'why'   => 'Bridges academic learning with employer realities through projects and simulations.',
    'skills'=> '<ul><li>Industry Awareness</li><li>Project Execution</li><li>Professional Communication</li></ul>',
    'icon'  => 'fa-building'
  ),
  array(
    'id'    => 'continuous_learning_future_skills',
    'title' => 'Continuous Learning & Future Skills ',
    'impact'=> 'Keeps learners aligned with emerging technologies and evolving roles.',
    'why'   => 'Ensures long-term employability by building a habit of ongoing, future-focused upskilling.',
    'skills'=> '<ul><li>Learning Agility</li><li>Emerging Tech Literacy</li><li>Adaptability</li></ul>',
    'icon'  => 'fa-infinity'
  ),
  array(
    'id'    => 'behavioural_intel_prof_maturity',
    'title' => 'Behavioural Intelligence & Professional Maturity ',
    'impact'=> 'Shapes behaviour, ethics, and professional presence across generations.',
    'why'   => 'Builds trust, credibility, and ethical decision-making in digital and physical workplaces.',
    'skills'=> '<ul><li>Self & Social Awareness</li><li>Ethical Reasoning</li><li>Executive Presence</li></ul>',
    'icon'  => 'fa-user-tie'
  )
);
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <h1 class="h4 mb-1">Work–Life Experience Frameworks Catalogue</h1>
        <p class="text-muted mb-0">
          Explore structured work–life experience journeys. Use <strong>View</strong> to understand the intent and core skills, 
          <strong>Preview</strong> to see how each framework is tailored for <strong>Gen Z, Millennials, and Gen X</strong>, 
          and <strong>Attempt</strong> to begin your personalised growth track.
        </p>
      </div>
      <i class="fa-solid fa-briefcase-clock fa-3x text-primary"></i>
    </div>
  </div>

  <div class="row g-3">
  <?php foreach($worklifeFrameworks as $fw): ?>
    <div class="col-sm-6 col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="fa-solid <?php echo htmlspecialchars($fw['icon']); ?> fa-2x text-primary me-3"></i>
            <div>
              <h5 class="card-title mb-1"><?php echo htmlspecialchars($fw['title']); ?></h5>
              <small class="text-muted d-block"><?php echo htmlspecialchars($fw['impact']); ?></small>
            </div>
          </div>

          <!-- Compact card: no long text; details via View / Preview -->
          <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mt-3">
              <div class="btn-group" role="group">
                <button class="btn btn-outline-primary btn-sm btn-view"
                  data-title="<?php echo htmlspecialchars($fw['title']); ?>"
                  data-why="<?php echo htmlspecialchars($fw['why']); ?>"
                  data-skills="<?php echo htmlspecialchars($fw['skills']); ?>">
                  View
                </button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-primary btn-sm btn-preview"
                  data-type-slug="<?php echo htmlspecialchars($fw['id']); ?>"
                  data-title="<?php echo htmlspecialchars($fw['title']); ?>">
                  Preview
                </button>
              </div>
              <div class="text-end">
                <button class="btn btn-sm btn-success btn-inprogress"
                        data-type-slug="<?php echo htmlspecialchars($fw['id']); ?>"
                        style="display:none;">
                  In&nbsp;Progress <span class="badge badge-count">0</span>
                </button>
                <button class="btn btn-sm btn-info btn-mygrades"
                        data-type-slug="<?php echo htmlspecialchars($fw['id']); ?>"
                        style="display:none;">
                  Grades <span class="badge badge-count">0</span>
                </button>
                <button class="btn btn-sm btn-success btn-start"
                        data-type-slug="<?php echo htmlspecialchars($fw['id']); ?>">
                  Attempt
                </button>
              </div>
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
    xhr.open('GET', 'worklife_experience.json?rand=' + Math.random(), true);
    xhr.onreadystatechange = function(){
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            THEMES_JSON = JSON.parse(xhr.responseText);
          } catch(e){
            console.log('Error parsing worklife_experience.json:', e);
          }
        } else {
          console.log('worklife_experience.json not found or error. Status:', xhr.status);
        }
        // Always call callback so buttons are wired even if JSON fails
        if (callback) callback();
      }
    };
    xhr.send();
  }

  // Build HTML preview per framework, combining Gen Z / Millennials / Gen X entries
  // JSON structure expected:
  // [
  //   {"framework_id":"foundational_workplace_readiness","level":"Gen Z","statement":"...","why":"...","skills":["..",".."]},
  //   {"framework_id":"foundational_workplace_readiness","level":"Millennials", ...},
  //   {"framework_id":"foundational_workplace_readiness","level":"Gen X", ...},
  //   ...
  // ]
  function buildPreviewHtml(type){
    var html = "";
    var found = 0;
    for (var i = 0; i < THEMES_JSON.length; i++){
      if (THEMES_JSON[i].framework_id === type){
        found++;
        var lvl = (THEMES_JSON[i].level || '').toString().toUpperCase();
        var skills = THEMES_JSON[i].skills || [];
        html +=
          "<div class='mb-3 p-2 border rounded'>" +
            "<p><i class='fa fa-user-tie text-primary me-2'></i><strong>" + lvl + ":</strong> " +
              (THEMES_JSON[i].statement || '') + "</p>" +
            "<p><i class='fa fa-heart text-danger me-2'></i><strong>Why It Matters:</strong> " +
              (THEMES_JSON[i].why || '') + "</p>" +
            "<p><i class='fa fa-graduation-cap text-success me-2'></i><strong>Skills Focus:</strong> " +
              skills.join(', ') + "</p>" +
          "</div>";
      }
    }
    if (!found){
      html = "<em><i class='fa fa-info-circle text-muted me-2'></i>No preview data available yet for this work–life experience framework.</em>";
    }
    return html;
  }

  function setupPreviewButtons(){
    var btns = document.getElementsByClassName('btn-preview');
    for (var i = 0; i < btns.length; i++){
      btns[i].onclick = function(){
        var slug  = this.getAttribute('data-type-slug');
        var title = this.getAttribute('data-title');
        var card  = this.closest('.card');
        var iconEl = card ? card.querySelector('i.fa-solid') : null;
        var iconClass = iconEl ? iconEl.className : 'fa-solid fa-layer-group';

        var html = buildPreviewHtml(slug);
        Swal.fire({
          title: "<i class='" + iconClass + " fa-lg text-primary'></i>" + title,
          html: "<div  style='margin-left:20px; text-align:left; font-size:13px;'>" +
                  "<p class='mb-1'><strong>What will you experience?</strong> " +
                    "See how this framework guides Gen Z, Millennials, and Gen X differently along their work–life journey.</p>" +
                  html +
                "</div>",
          width: 900,
          showConfirmButton: false
        });
      };
    }
  }

  function setupViewButtons(){
    var btns = document.getElementsByClassName('btn-view');
    for (var i = 0; i < btns.length; i++){
      btns[i].onclick = function(){
        var title  = this.getAttribute('data-title');
        var why    = this.getAttribute('data-why') || '';
        var skills = this.getAttribute('data-skills') || '';
        Swal.fire({
          title: title,
          html:
            "<div style='text-align:left;font-size:13px;'>" +
              "<p><strong>Why this Work–Life Experience Framework?</strong><br>" + why + "</p>" +
              "<p><strong>Core Skills Developed:</strong><br>" + skills + "</p>" +
            "</div>",
          icon: 'info'
        });
      };
    }
  }

  function setupAttemptButtons(){
    var btns = document.getElementsByClassName('btn-start');
    for (var i = 0; i < btns.length; i++){
      btns[i].onclick = function(){
        var framework = this.getAttribute('data-type-slug');
        if (framework) {
          window.location.href = "worklife_submit.php?framework=" + encodeURIComponent(framework);
        }
      };
    }
  }

  function setupProgressAndGrades(){
    var xhr = new XMLHttpRequest();
    xhr.open('GET', window.location.pathname + '?action=check_has_attempts_all&rand=' + Math.random(), true);
    xhr.onreadystatechange = function(){
      if (xhr.readyState === 4 && xhr.status === 200){
        try {
          var data = JSON.parse(xhr.responseText);
          if (!data.success) return;
          var status = data.status;
          for (var slug in status){
            if (!status.hasOwnProperty(slug)) continue;
            var st = status[slug];
            var completed = st.completed || 0;
            var inprog    = st.inprogress || 0;

            (function(slugInner, completedInner, inprogInner){
              var gradeBtn = document.querySelector('.btn-mygrades[data-type-slug="'+ slugInner +'"]');
              var inProgBtn = document.querySelector('.btn-inprogress[data-type-slug="'+ slugInner +'"]');

              if (gradeBtn && completedInner > 0){
                gradeBtn.style.display = '';
                gradeBtn.querySelector('.badge-count').innerHTML = completedInner;
                gradeBtn.onclick = function(){
                  window.location.href = "worklife_grades_view.php?framework=" + encodeURIComponent(slugInner);
                };
              }
              if (inProgBtn && inprogInner > 0){
                inProgBtn.style.display = '';
                inProgBtn.querySelector('.badge-count').innerHTML = inprogInner;
                inProgBtn.onclick = function(){
                  window.location.href = "worklife_submit.php?framework=" + encodeURIComponent(slugInner);
                };
              }
            })(slug, completed, inprog);
          }
        } catch(e){
          console.log(e);
        }
      }
    };
    xhr.send();
  }

  document.addEventListener('DOMContentLoaded', function(){
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
.badge-count {
  background:#eef;
  padding:2px 6px;
  border-radius:999px;
  font-size:12px;
}
.btn-inprogress {
  background:#ffc107;
  color:#000;
  border:none;
}
.swal2-html-container {
  text-align:left!important;
  font-size:13px!important;
  color:#333;
}
.card .btn-group .btn {
  font-size: 0.75rem;
}
</style>

<?php require_once('../platformFooter.php'); ?>
