<?php
/**
 * Astraal LXP - edu5Learning.php
 * PHP 5.4 Compatible — Edu 5.0 Lifelong Learning Activities Catalogue (6×6×6)
 * ✅ View CTA (Why + Core Skills)
 * ✅ JSON-based Preview (K–12 / Higher Ed / Corporate L&D)
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

  // Edu 5.0 Activities – 6 Core + 6 Advanced + 6 Inner Eye = 18
  $frameworks = array(
    // Core Personalized Activities (Layer A)
    'core_genius_hours',
    'core_passion_projects',
    'core_peripheral',
    'core_minds_i',
    'core_micro_adventures_lite',
    'core_skill_sprints',

    // Advanced Pathways (Layer B)
    'adv_future_labs',
    'adv_sandbox',
    'adv_micro_adventures_advanced',
    'adv_human_renaissance_projects',
    'adv_innovation_expeditions',
    'adv_colab_studios',

    // Inner Eye Series (Layer C)
    'inner_mind_as_program',
    'inner_the_observer',
    'inner_inner_eye',
    'inner_identity_beyond_identity',
    'inner_meta_self_integration',
    'inner_wise_mind'
  );

  $result = array();
  foreach ($frameworks as $slug) {
    $result[$slug] = array('completed' => 0, 'inprogress' => 0);
  }

  if ($mysqli instanceof mysqli) {
    foreach ($frameworks as $slug) {
      // Adjust table name if needed (edu5learning_submissions assumed here)
      $sql = "SELECT problem_id, COUNT(DISTINCT milestone_no) AS milestone_count
              FROM edu5learning_submissions
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
$page = "edu5.0";
require_once('learnerHead_Nav2.php');

/* ---------- Edu 5.0 Activities List (18) ---------- */
/*
 * Reference Summary Table: Edu 5.0 Model Across Segments
 *
 * Layer A – Core Personalized Activities (6)
 *   Focus: Curiosity, identity, autonomy
 *   Activities:
 *     - Genius Hours
 *     - Passion Projects
 *     - The Peripheral
 *     - The Minds’ I
 *     - Micro-Adventures (Lite)
 *     - Skill Sprints
 *
 * Layer B – Advanced Pathways (6)
 *   Focus: Applied skills, industry readiness
 *   Activities:
 *     - Future Labs
 *     - Sandbox
 *     - Micro-Adventures (Advanced)
 *     - Human Renaissance Projects
 *     - Innovation Expeditions
 *     - CoLab Studios
 *
 * Layer C – Inner Eye Series (6)
 *   Focus: Self-awareness, ethics, leadership
 *   Activities:
 *     - Mind as Program
 *     - The Observer
 *     - Inner Eye
 *     - Identity Beyond Identity
 *     - Meta-Self Integration
 *     - The Wise Mind
 */

$edu5Frameworks = array(
  /* ---------- Core Personalized Activities (Layer A) ---------- */
  array(
    'id'    => 'core_genius_hours',
    'title' => 'Genius Hours (Core – A1)',
    'impact'=> 'Creates a safe space for self-chosen, curiosity-driven learning.',
    'why'   => 'Learners pick their own topics, content, and methods, building autonomy, intrinsic motivation, and early portfolios aligned with Edu 5.0.',
    'skills'=> '<ul>
                  <li>Curiosity & Inquiry</li>
                  <li>Independent Learning</li>
                  <li>Early Portfolio Creation</li>
                </ul>',
    'icon'  => 'fa-lightbulb'
  ),
  array(
    'id'    => 'core_passion_projects',
    'title' => 'Passion Projects (Core – A2)',
    'impact'=> 'Transforms personal interests into structured, outcome-driven projects.',
    'why'   => 'Helps learners convert passion into demonstrable academic, work, or life outcomes—bridging identity with employability.',
    'skills'=> '<ul>
                  <li>Project Planning & Execution</li>
                  <li>Resilience & Ownership</li>
                  <li>Communication of Ideas</li>
                </ul>',
    'icon'  => 'fa-fire'
  ),
  array(
    'id'    => 'core_peripheral',
    'title' => 'The Peripheral (Core – A3)',
    'impact'=> 'Introduces frontier tech and beyond-the-syllabus exploration.',
    'why'   => 'Expands thinking beyond the mundane into AI/ML, AR/VR/MR, and future-of-work concepts, aligning learners with emerging landscapes.',
    'skills'=> '<ul>
                  <li>Future-Tech Awareness</li>
                  <li>Systems Thinking</li>
                  <li>Innovation Mindset</li>
                </ul>',
    'icon'  => 'fa-vr-cardboard'
  ),
  array(
    'id'    => 'core_minds_i',
    'title' => 'The Minds’ I (Core – A4)',
    'impact'=> 'Builds introspection, identity awareness, and emotional depth.',
    'why'   => 'Uses reflection and narrative work to strengthen self-awareness, emotional intelligence, and inner clarity—human core of Edu 5.0.',
    'skills'=> '<ul>
                  <li>Self-Awareness</li>
                  <li>Emotional Intelligence</li>
                  <li>Reflective Thinking</li>
                </ul>',
    'icon'  => 'fa-brain'
  ),
  array(
    'id'    => 'core_micro_adventures_lite',
    'title' => 'Micro-Adventures (Lite) (Core – A5)',
    'impact'=> 'Short, playful quests to learn or create something in 24–72 hours.',
    'why'   => 'Builds agility and experimentation by letting learners try micro-challenges that feel fun, low-risk, and high-feedback.',
    'skills'=> '<ul>
                  <li>Learning Agility</li>
                  <li>Rapid Experimentation</li>
                  <li>Adaptability</li>
                </ul>',
    'icon'  => 'fa-shoe-prints'
  ),
  array(
    'id'    => 'core_skill_sprints',
    'title' => 'Skill Sprints (Core – A6)',
    'impact'=> 'Focused 1–2 week cycles to build one clear skill end-to-end.',
    'why'   => 'Aligns with lifelong learning by helping learners deliberately pick, practise, and showcase discrete skills on a sprint basis.',
    'skills'=> '<ul>
                  <li>Goal-Oriented Practice</li>
                  <li>Discipline & Consistency</li>
                  <li>Skill Demonstration</li>
                </ul>',
    'icon'  => 'fa-running'
  ),

  /* ---------- Advanced Pathways (Layer B) ---------- */
  array(
    'id'    => 'adv_future_labs',
    'title' => 'Future Labs (Advanced – B1)',
    'impact'=> 'Hands-on immersion in AI, FinTech, Industry 5.0, ESG, and emerging domains.',
    'why'   => 'Connects Edu 5.0 learning with real future-facing tools and scenarios, improving employability and industry readiness.',
    'skills'=> '<ul>
                  <li>Applied Analytics & AI Fluency</li>
                  <li>Domain & Industry Awareness</li>
                  <li>Experimentation with Emerging Tech</li>
                </ul>',
    'icon'  => 'fa-flask'
  ),
  array(
    'id'    => 'adv_sandbox',
    'title' => 'The Sandbox (Advanced – B2)',
    'impact'=> 'A protected environment to experiment with ideas without fear of failure.',
    'why'   => 'Learners can prototype business models, code, designs, or processes in a low-stakes sandbox before live deployment.',
    'skills'=> '<ul>
                  <li>Problem-Solving</li>
                  <li>Rapid Prototyping</li>
                  <li>Resilience & Iteration</li>
                </ul>',
    'icon'  => 'fa-cubes'
  ),
  array(
    'id'    => 'adv_micro_adventures_advanced',
    'title' => 'Micro-Adventures (Advanced) (Advanced – B3)',
    'impact'=> 'Short, intense domain challenges linked to real-world use cases.',
    'why'   => 'Ideal for cracking BFSI cases, building quick models, or tackling realistic scenarios in compressed time windows.',
    'skills'=> '<ul>
                  <li>Domain Application</li>
                  <li>Time-Boxed Execution</li>
                  <li>Decision-Making Under Pressure</li>
                </ul>',
    'icon'  => 'fa-stopwatch'
  ),
  array(
    'id'    => 'adv_human_renaissance_projects',
    'title' => 'Human Renaissance Projects (Advanced – B4)',
    'impact'=> 'Blends arts, culture, philosophy, and technology for whole-brain learning.',
    'why'   => 'Reintroduces aesthetics, creativity, and human heritage into an AI-heavy world, strengthening observation and synthesis.',
    'skills'=> '<ul>
                  <li>Creative Synthesis</li>
                  <li>Aesthetic & Cultural Literacy</li>
                  <li>Interdisciplinary Thinking</li>
                </ul>',
    'icon'  => 'fa-palette'
  ),
  array(
    'id'    => 'adv_innovation_expeditions',
    'title' => 'Innovation Expeditions (Advanced – B5)',
    'impact'=> 'Structured journeys to solve complex problems using Design & Systems Thinking.',
    'why'   => 'Learners traverse real problem spaces, gather insights, and design solutions using Edu 5.0-aligned innovation methods.',
    'skills'=> '<ul>
                  <li>Design Thinking</li>
                  <li>Systems & Strategic Thinking</li>
                  <li>Stakeholder Empathy</li>
                </ul>',
    'icon'  => 'fa-route'
  ),
  array(
    'id'    => 'adv_colab_studios',
    'title' => 'CoLab Studios (Advanced – B6)',
    'impact'=> 'Collaborative studios where peers, mentors, industry, and AI co-create.',
    'why'   => 'Builds teamwork and cross-functional skills in realistic, facilitated studio environments.',
    'skills'=> '<ul>
                  <li>Collaboration & Co-Creation</li>
                  <li>Communication & Negotiation</li>
                  <li>Multi-Stakeholder Problem-Solving</li>
                </ul>',
    'icon'  => 'fa-people-arrows'
  ),

  /* ---------- Inner Eye Series (Layer C) ---------- */
  array(
    'id'    => 'inner_mind_as_program',
    'title' => 'Mind as Program (Inner Eye – C1)',
    'impact'=> 'Helps learners understand and rewire their cognitive patterns.',
    'why'   => 'Treats the mind like a programmable system—mapping biases, habits, and mental models for deliberate upgrade.',
    'skills'=> '<ul>
                  <li>Metacognition</li>
                  <li>Bias Awareness</li>
                  <li>Cognitive Reframing</li>
                </ul>',
    'icon'  => 'fa-microchip'
  ),
  array(
    'id'    => 'inner_the_observer',
    'title' => 'The Observer (Inner Eye – C2)',
    'impact'=> 'Develops the ability to watch thoughts and emotions without impulsive reaction.',
    'why'   => 'Strengthens emotional regulation, perspective-taking, and psychological distance in high-pressure environments.',
    'skills'=> '<ul>
                  <li>Emotional Regulation</li>
                  <li>Perspective-Taking</li>
                  <li>Non-Reactive Awareness</li>
                </ul>',
    'icon'  => 'fa-eye-low-vision'
  ),
  array(
    'id'    => 'inner_inner_eye',
    'title' => 'The Inner Eye (Inner Eye – C3)',
    'impact'=> 'Deepens awareness through mindfulness, reflection, and focus practices.',
    'why'   => 'Improves clarity, presence, and the capacity to focus in distracted, always-on digital contexts.',
    'skills'=> '<ul>
                  <li>Mindfulness & Presence</li>
                  <li>Focus & Attention Control</li>
                  <li>Reflective Depth</li>
                </ul>',
    'icon'  => 'fa-eye'
  ),
  array(
    'id'    => 'inner_identity_beyond_identity',
    'title' => 'Identity Beyond Identity (Inner Eye – C4)',
    'impact'=> 'Explores self beyond roles, labels, and narratives.',
    'why'   => 'Supports learners in discovering authentic identity beyond external expectations, crucial for resilient leadership.',
    'skills'=> '<ul>
                  <li>Authenticity & Integrity</li>
                  <li>Narrative Awareness</li>
                  <li>Values Clarification</li>
                </ul>',
    'icon'  => 'fa-user-circle'
  ),
  array(
    'id'    => 'inner_meta_self_integration',
    'title' => 'Meta-Self Integration (Inner Eye – C5)',
    'impact'=> 'Integrates intellect, emotion, intuition, and values into one coherent self.',
    'why'   => 'Evolves learners from fragmented roles to integrated self-leadership, a core Edu 5.0 outcome.',
    'skills'=> '<ul>
                  <li>Self-Leadership</li>
                  <li>Integration of Head–Heart–Gut</li>
                  <li>Coherent Decision-Making</li>
                </ul>',
    'icon'  => 'fa-layer-group'
  ),
  array(
    'id'    => 'inner_wise_mind',
    'title' => 'The Wise Mind (Inner Eye – C6)',
    'impact'=> 'Aligns purpose, ethics, and long-term vision into wise action.',
    'why'   => 'Supports purpose-driven leadership, strategic clarity, and ethical choices in complex, AI-shaped worlds.',
    'skills'=> '<ul>
                  <li>Ethical Reasoning</li>
                  <li>Purpose & Visioning</li>
                  <li>Strategic Judgment</li>
                </ul>',
    'icon'  => 'fa-scale-balanced'
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
        <h1 class="h4 mb-1">Edu 5.0 Lifelong Learning Activities Catalogue (6×6×6)</h1>
        <p class="text-muted mb-0">
          Explore 18 Edu 5.0 activities across <strong>Core Personalized</strong>, <strong>Advanced Pathways</strong>, 
          and the <strong>Inner Eye Series</strong>. Use <strong>View</strong> to understand the intent and core skills,
          <strong>Preview</strong> to see how each activity adapts for <strong>K–12, Higher Education, and Corporate L&amp;D</strong>, 
          and <strong>Attempt</strong> to begin your personalised Edu 5.0 journey.
        </p>
      </div>
      <i class="fa-solid fa-graduation-cap fa-3x text-primary"></i>
    </div>
  </div>

  <div class="row g-3">
  <?php foreach($edu5Frameworks as $fw): ?>
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
    xhr.open('GET', 'edu5_learning.json?rand=' + Math.random(), true);
    xhr.onreadystatechange = function(){
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            THEMES_JSON = JSON.parse(xhr.responseText);
          } catch(e){
            console.log('Error parsing edu5_learning.json:', e);
          }
        } else {
          console.log('edu5_learning.json not found or error. Status:', xhr.status);
        }
        if (callback) callback();
      }
    };
    xhr.send();
  }

  // Build HTML preview per activity, combining K–12 / Higher Ed / Corporate L&D entries
  // JSON structure expected:
  // [
  //   {"framework_id":"core_genius_hours","segment":"K–12","statement":"...","why":"...","outcomes":["..",".."]},
  //   {"framework_id":"core_genius_hours","segment":"Higher Ed", ...},
  //   {"framework_id":"core_genius_hours","segment":"Corporate L&D", ...},
  //   ...
  // ]
  function buildPreviewHtml(type){
    var html = "";
    var found = 0;
    for (var i = 0; i < THEMES_JSON.length; i++){
      if (THEMES_JSON[i].framework_id === type){
        found++;
        var seg = (THEMES_JSON[i].segment || '').toString();
        var outcomes = THEMES_JSON[i].outcomes || [];
        html +=
          "<div class='mb-3 p-2 border rounded'>" +
            "<p><i class='fa fa-users text-primary me-2'></i><strong>" + seg + ":</strong> " +
              (THEMES_JSON[i].statement || '') + "</p>" +
            "<p><i class='fa fa-heart text-danger me-2'></i><strong>Why It Matters:</strong> " +
              (THEMES_JSON[i].why || '') + "</p>" +
            "<p><i class='fa fa-graduation-cap text-success me-2'></i><strong>Key Outcomes:</strong> " +
              outcomes.join(', ') + "</p>" +
          "</div>";
      }
    }
    if (!found){
      html = "<em><i class='fa fa-info-circle text-muted me-2'></i>No preview data available yet for this Edu 5.0 activity. Please check back soon.</em>";
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
          html: "<div style='margin-left:20px; text-align:left; font-size:13px;'>" +
                  "<p class='mb-1'><strong>How this Edu 5.0 activity adapts across segments</strong><br>" +
                    "See how this activity is positioned for <strong>K–12</strong>, <strong>Higher Ed</strong>, and <strong>Corporate L&amp;D</strong>.</p>" +
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
              "<p><strong>Why this Edu 5.0 Activity?</strong><br>" + why + "</p>" +
              "<p><strong>Core Capabilities Developed:</strong><br>" + skills + "</p>" +
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
          window.location.href = "edu5learning_submit.php?framework=" + encodeURIComponent(framework);
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
                  window.location.href = "edu5learning_grades_view.php?framework=" + encodeURIComponent(slugInner);
                };
              }
              if (inProgBtn && inprogInner > 0){
                inProgBtn.style.display = '';
                inProgBtn.querySelector('.badge-count').innerHTML = inprogInner;
                inProgBtn.onclick = function(){
                  window.location.href = "edu5learning_submit.php?framework=" + encodeURIComponent(slugInner);
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
