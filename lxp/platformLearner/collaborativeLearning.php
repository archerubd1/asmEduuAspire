<?php
/**
 * Astraal LXP - learner_collab_themes.php
 * PHP 5.4 Compatible — Collaborative Learning Catalogue
 * ✅ View CTA (Why + Core Skills)
 * ✅ JSON-based Preview (Kids/Teens/Adults)
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

  // Collaborative learning submodules (theme slugs)
  $themes = array(
    'collab_intel_labs',
    'rotational_panels',
    'socratic_debate',
    'scenario_rewriting',
    'micro_challenges',
    'meta_learning_pods',
    'insight_swarm',
    'failure_labs',
    'fusion_labs',
    'story_building',
    'micro_communities',
    'roleplay_ecosystem',
    'ethics_lab',
    'avatar_collab',
    'ai_team_matching'
  );

  $result = array();
  foreach ($themes as $slug) $result[$slug] = array('completed'=>0,'inprogress'=>0);

  if ($mysqli instanceof mysqli) {
    foreach ($themes as $slug) {
      // Adjust table name if needed (collab_submissions assumed here)
      $sql = "SELECT problem_id, COUNT(DISTINCT milestone_no) AS milestone_count
              FROM collab_submissions
              WHERE user_id=? AND theme_slug=?
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

  echo json_encode(array('success'=>true,'status'=>$result));
  exit;
}

/* ---------- Page setup ---------- */
$page = "collaborativeLearning";
require_once('learnerHead_Nav2.php');

/* ---------- Collaborative Learning Submodules List ---------- */
$collabThemes = array(
  array(
    'id'    => 'collab_intel_labs',
    'title' => 'Collaborative Intelligence Labs',
    'impact'=> 'AI-augmented group creativity and problem-solving experiences.',
    'why'   => 'Blends human creativity with AI, preparing learners for AI-powered workplaces.',
    'skills'=> '<ul><li>AI Literacy</li><li>Creative Problem-Solving</li><li>Collaboration</li></ul>',
    'icon'  => 'fa-brain'
  ),
  array(
    'id'    => 'rotational_panels',
    'title' => 'Rotational Expert Panels',
    'impact'=> 'Learners become topic experts and teach each other.',
    'why'   => 'Reinforces mastery through teaching while building confidence and leadership.',
    'skills'=> '<ul><li>Research</li><li>Communication</li><li>Knowledge Sharing</li></ul>',
    'icon'  => 'fa-users-gear'
  ),
  array(
    'id'    => 'socratic_debate',
    'title' => 'Socratic Debate Circles',
    'impact'=> 'Structured debates on ethics, technology, and real-world dilemmas.',
    'why'   => 'Builds critical thinking, reasoning, and respectful disagreement skills.',
    'skills'=> '<ul><li>Critical Thinking</li><li>Argumentation</li><li>Decision-Making</li></ul>',
    'icon'  => 'fa-scale-balanced'
  ),
  array(
    'id'    => 'scenario_rewriting',
    'title' => 'Scenario Rewriting Engine',
    'impact'=> 'Reimagine story, science, or business outcomes by changing decisions.',
    'why'   => 'Develops counterfactual reasoning, creativity, and strategic thinking.',
    'skills'=> '<ul><li>Creativity</li><li>Perspective-Taking</li><li>Strategic Reasoning</li></ul>',
    'icon'  => 'fa-rotate'
  ),
  array(
    'id'    => 'micro_challenges',
    'title' => 'Crowd-Sourced Micro-Challenges',
    'impact'=> 'Fast, bite-sized challenges solved collaboratively.',
    'why'   => 'Keeps energy high while building agility and rapid problem-solving.',
    'skills'=> '<ul><li>Quick Thinking</li><li>Collaboration</li><li>Analytical Skills</li></ul>',
    'icon'  => 'fa-bolt'
  ),
  array(
    'id'    => 'meta_learning_pods',
    'title' => 'Meta-Learning Pods',
    'impact'=> 'Pods focused on “learning how to learn” together.',
    'why'   => 'Builds lifelong learning habits, self-awareness, and team effectiveness.',
    'skills'=> '<ul><li>Self-Awareness</li><li>Study Strategies</li><li>Team Processes</li></ul>',
    'icon'  => 'fa-infinity'
  ),
  array(
    'id'    => 'insight_swarm',
    'title' => 'Insight Swarm Rooms',
    'impact'=> 'Large-scale group collaboration on shared challenges.',
    'why'   => 'Generates multi-perspective insights and prepares learners for hackathons and innovation sprints.',
    'skills'=> '<ul><li>Collaboration at Scale</li><li>Systems Thinking</li><li>Innovation</li></ul>',
    'icon'  => 'fa-diagram-project'
  ),
  array(
    'id'    => 'failure_labs',
    'title' => 'Failure Labs',
    'impact'=> 'Guided analysis of “what went wrong” in safe environments.',
    'why'   => 'Normalises learning from failure and builds resilience and risk literacy.',
    'skills'=> '<ul><li>Resilience</li><li>Root-Cause Analysis</li><li>Risk Awareness</li></ul>',
    'icon'  => 'fa-triangle-exclamation'
  ),
  array(
    'id'    => 'fusion_labs',
    'title' => 'Cross-Domain Fusion Labs',
    'impact'=> 'Combine multiple disciplines to create hybrid solutions.',
    'why'   => 'Mirrors real-world innovation where technology, design, and domain knowledge blend.',
    'skills'=> '<ul><li>Integrative Thinking</li><li>Creativity</li><li>Cross-Functional Collaboration</li></ul>',
    'icon'  => 'fa-shapes'
  ),
  array(
    'id'    => 'story_building',
    'title' => 'Experiential Story-Building Projects',
    'impact'=> 'Turn learning journeys into stories, comics, or case narratives.',
    'why'   => 'Builds powerful communication and synthesis skills across age groups.',
    'skills'=> '<ul><li>Narrative Skills</li><li>Presentation</li><li>Strategic Communication</li></ul>',
    'icon'  => 'fa-book-open'
  ),
  array(
    'id'    => 'micro_communities',
    'title' => 'Mentored Micro-Communities',
    'impact'=> 'Small, mentor-guided peer groups with weekly tasks.',
    'why'   => 'Enables scalable mentoring, portfolio building, and career growth.',
    'skills'=> '<ul><li>Discipline</li><li>Peer Learning</li><li>Career Readiness</li></ul>',
    'icon'  => 'fa-people-group'
  ),
  array(
    'id'    => 'roleplay_ecosystem',
    'title' => 'Distributed Role-Play Ecosystem',
    'impact'=> 'Simulated roles in scenarios: explorers, leaders, professionals.',
    'why'   => 'Makes abstract concepts real and prepares learners for complex workplace roles.',
    'skills'=> '<ul><li>Empathy</li><li>Leadership</li><li>Communication</li></ul>',
    'icon'  => 'fa-masks-theater'
  ),
  array(
    'id'    => 'ethics_lab',
    'title' => 'Collaborative Ethics Lab',
    'impact'=> 'Teams explore dilemmas in tech, AI, and society.',
    'why'   => 'Develops moral reasoning and ethical intelligence for the digital age.',
    'skills'=> '<ul><li>Ethical Thinking</li><li>Value-Based Reasoning</li><li>Governance Mindset</li></ul>',
    'icon'  => 'fa-scale-unbalanced-flip'
  ),
  array(
    'id'    => 'avatar_collab',
    'title' => 'Avatar-Based Collaboration Rooms',
    'impact'=> 'Lightweight metaverse-style rooms for gamified collaboration.',
    'why'   => 'Boosts engagement and digital fluency in immersive spaces.',
    'skills'=> '<ul><li>Digital Fluency</li><li>Spatial Collaboration</li><li>Teamwork</li></ul>',
    'icon'  => 'fa-vr-cardboard'
  ),
  array(
    'id'    => 'ai_team_matching',
    'title' => 'AI Collaboration Matching Engine',
    'impact'=> 'AI forms balanced teams based on styles, skills, and goals.',
    'why'   => 'Improves group dynamics, inclusivity, and project outcomes.',
    'skills'=> '<ul><li>Collaboration</li><li>Self & Peer Awareness</li><li>Productivity</li></ul>',
    'icon'  => 'fa-network-wired'
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
        <h1 class="h4 mb-1">Collaborative Learning Experiences Catalogue</h1>
        <p class="text-muted mb-0">
          Explore future-ready collaborative experiences. Use <strong>View</strong> to understand the intent and core skills, <strong>Preview</strong> to see age-wise formats, and <strong>Attempt</strong> to start building your portfolio.
        </p>
      </div>
      <i class="fa-solid fa-people-arrows-left-right fa-3x text-primary"></i>
    </div>
  </div>

  <div class="row g-3">
  <?php foreach($collabThemes as $th): ?>
    <div class="col-sm-6 col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="fa-solid <?php echo htmlspecialchars($th['icon']); ?> fa-2x text-primary me-3"></i>
            <div>
              <h5 class="card-title mb-1"><?php echo htmlspecialchars($th['title']); ?></h5>
              <small class="text-muted d-block"><?php echo htmlspecialchars($th['impact']); ?></small>
            </div>
          </div>

          <!-- Compact card: no long text; details via View / Preview -->
          <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mt-3">
              <div class="btn-group" role="group">
                <button class="btn btn-outline-primary btn-sm btn-view"
                  data-title="<?php echo htmlspecialchars($th['title']); ?>"
                  data-why="<?php echo htmlspecialchars($th['why']); ?>"
                  data-skills="<?php echo htmlspecialchars($th['skills']); ?>">
                  View
                </button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-primary btn-sm btn-preview"
                  data-type-slug="<?php echo htmlspecialchars($th['id']); ?>"
                  data-title="<?php echo htmlspecialchars($th['title']); ?>">
                  Preview
                </button>
              </div>
              <div class="text-end">
                <button class="btn btn-sm btn-success btn-inprogress"
                        data-type-slug="<?php echo htmlspecialchars($th['id']); ?>"
                        style="display:none;">
                  In&nbsp;Progress <span class="badge badge-count">0</span>
                </button>
                <button class="btn btn-sm btn-info btn-mygrades"
                        data-type-slug="<?php echo htmlspecialchars($th['id']); ?>"
                        style="display:none;">
                  Grades <span class="badge badge-count">0</span>
                </button>
                <button class="btn btn-sm btn-success btn-start"
                        data-type-slug="<?php echo htmlspecialchars($th['id']); ?>">
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
    xhr.open('GET', 'collab_themes.json?rand=' + Math.random(), true);
    xhr.onreadystatechange = function(){
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            THEMES_JSON = JSON.parse(xhr.responseText);
          } catch(e){
            console.log('Error parsing collab_themes.json:', e);
          }
        } else {
          console.log('collab_themes.json not found or error. Status:', xhr.status);
        }
        // Always call callback so buttons are wired even if JSON fails
        if (callback) callback();
      }
    };
    xhr.send();
  }

  // Build HTML preview per theme, combining Kids / Teens / Adults entries
  // JSON structure expected:
  // [
  //   {"theme_id":"collab_intel_labs","level":"Kids","statement":"...","why":"...","skills":["..",".."]},
  //   {"theme_id":"collab_intel_labs","level":"Teens", ...},
  //   {"theme_id":"collab_intel_labs","level":"Adults", ...},
  //   ...
  // ]
  function buildPreviewHtml(type){
    var html = "";
    var found = 0;
    for (var i = 0; i < THEMES_JSON.length; i++){
      if (THEMES_JSON[i].theme_id === type){
        found++;
        var lvl = (THEMES_JSON[i].level || '').toString().toUpperCase();
        var skills = THEMES_JSON[i].skills || [];
        html +=
          "<div class='mb-3 p-2 border rounded'>" +
            "<p><i class='fa fa-user-graduate text-primary me-2'></i><strong>" + lvl + ":</strong> " +
              (THEMES_JSON[i].statement || '') + "</p>" +
            "<p><i class='fa fa-heart text-danger me-2'></i><strong>Why It Matters:</strong> " +
              (THEMES_JSON[i].why || '') + "</p>" +
            "<p><i class='fa fa-graduation-cap text-success me-2'></i><strong>Skills Focus:</strong> " +
              skills.join(', ') + "</p>" +
          "</div>";
      }
    }
    if (!found){
      html = "<em><i class='fa fa-info-circle text-muted me-2'></i>No preview data available yet for this collaborative experience.</em>";
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
          title: "<i class='" + iconClass + " fa-lg text-primary me-2'></i>" + title + " — Preview",
          html: "<div style='text-align:left; font-size:13px;'>" +
                  "<p class='mb-2'><strong>What will you experience?</strong> " +
                    "See how this collaborative experience is tailored differently for Kids, Teens, and Adults.</p>" +
                  html +
                "</div>",
          width: 750,
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
              "<p><strong>Why this Collaborative Experience?</strong><br>" + why + "</p>" +
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
        var theme = this.getAttribute('data-type-slug');
        if (theme) {
          window.location.href = "collab_submit.php?theme=" + encodeURIComponent(theme);
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
                  window.location.href = "collab_grades_view.php?theme=" + encodeURIComponent(slugInner);
                };
              }
              if (inProgBtn && inprogInner > 0){
                inProgBtn.style.display = '';
                inProgBtn.querySelector('.badge-count').innerHTML = inprogInner;
                inProgBtn.onclick = function(){
                  window.location.href = "collab_submit.php?theme=" + encodeURIComponent(slugInner);
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
