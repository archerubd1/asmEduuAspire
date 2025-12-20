<?php
/**
 * Astraal LXP learner_critical_thinking.php (Final - JSON Externalized)
 * Critical Thinking Catalogue — JSON preview driven
 * Updated by UBD
 *
 * - Dynamic load of ct_problems.json (no inline JSON)
 * - Preserves View / Preview / TTS / Grades / AJAX behavior
 * - Uses ct_submissions for attempt counts
 * - Grades CTA redirects to ct_view_grades.php
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

/* find mysqli connection */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

/* AJAX endpoint: check_has_attempts_all */
if (isset($_GET['action']) && $_GET['action'] === 'check_has_attempts_all') {
    header('Content-Type: application/json; charset=utf-8');

    // map between slug IDs and DB ct_assignments IDs
    $types = array(
        'fact_vs_opinion' => 'FACT_OPINION',
        'coffee_chat'     => 'COFFEE_CHAT',
        'worldly_words'   => 'WORLDLY_WORDS',
        'alien_guide'     => 'ALIEN_GUIDE',
        'talk_it_out'     => 'TALK_IT_OUT',
        'elevator_pitch'  => 'ELEVATOR_PITCH'
    );

    $result = array();
    foreach ($types as $slug => $dbid) $result[$slug] = 0;

    if ($mysqli instanceof mysqli) {
        foreach ($types as $slug => $assignment_id) {
            $sql = "
                SELECT COUNT(*) AS cnt
                FROM ct_submissions s
                JOIN ct_subassignments sa ON s.subassignment_id = sa.id
                JOIN ct_assignments a ON sa.assignment_id = a.id
                WHERE s.user_id = ? AND a.id = ?
            ";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('is', $phx_user_id, $assignment_id);
                if ($stmt->execute()) {
                    $res = $stmt->get_result();
                    if ($r = $res->fetch_assoc()) {
                        $result[$slug] = (int)$r['cnt'];
                    }
                }
                $stmt->close();
            }
        }
    }

    echo json_encode(['success' => true, 'counts' => $result]);
    exit;
}

/* page includes */
$page = "criticalThinking";
require_once('learnerHead_Nav2.php');

/* image mapping */
$ct_images = array(
    'fact_vs_opinion' => 'ct1.png',
    'coffee_chat'     => 'ct2.png',
    'worldly_words'   => 'ct3.png',
    'alien_guide'     => 'ct4.png',
    'talk_it_out'     => 'ct5.png',
    'elevator_pitch'  => 'ct6.png'
);

/* static catalogue */
$ctAssignments = array(
    array('id'=>'fact_vs_opinion','dbid'=>'FACT_OPINION','title'=>'Fact vs Opinion','brief'=>'Differentiate objective evidence from subjective viewpoints; detect emotional persuasion.','why'=>'<strong>Why it matters:</strong> Helps you spot misinformation, weigh claims, and make evidence-based decisions.','skills'=>'<ul class="text-left"><li><strong>Analytical reasoning:</strong> Evidence evaluation</li><li><strong>Bias detection:</strong> Emotional trigger awareness</li></ul>'),
    array('id'=>'coffee_chat','dbid'=>'COFFEE_CHAT','title'=>'Coffee House Chat','brief'=>'Role-play conversations to practice empathy, listening, and perspective-taking.','why'=>'<strong>Why it matters:</strong> Builds social reasoning & conflict diffusion skills needed in teams and life.','skills'=>'<ul class="text-left"><li><strong>Perspective-taking</strong></li><li><strong>Active listening & emotional labelling</strong></li></ul>'),
    array('id'=>'worldly_words','dbid'=>'WORLDLY_WORDS','title'=>'Worldly Words','brief'=>'Convey complex ideas using only 10 words to practice concision and precision.','why'=>'<strong>Why it matters:</strong> Sharpens clarity for emails, pitches, and time-limited communications.','skills'=>'<ul class="text-left"><li><strong>Concise expression</strong></li><li><strong>Cognitive flexibility</strong></li></ul>'),
    array('id'=>'alien_guide','dbid'=>'ALIEN_GUIDE','title'=>'Alien Travel Guide','brief'=>'Explain everyday concepts to an “alien” — surface assumptions and restructure ideas.','why'=>'<strong>Why it matters:</strong> Teaches metacognition, decentering, and bias reduction.','skills'=>'<ul class="text-left"><li><strong>Metacognition & schema restructuring</strong></li><li><strong>Inclusive explanation</strong></li></ul>'),
    array('id'=>'talk_it_out','dbid'=>'TALK_IT_OUT','title'=>'Talk It Out','brief'=>'Construct claim → evidence → counterargument → conclusion structures.','why'=>'<strong>Why it matters:</strong> Builds argumentation skills and resilience to counterarguments.','skills'=>'<ul class="text-left"><li><strong>Argument construction</strong></li><li><strong>Logical fallacy detection</strong></li></ul>'),
    array('id'=>'elevator_pitch','dbid'=>'ELEVATOR_PITCH','title'=>'Elevator Pitch','brief'=>'Deliver short, persuasive messages under time pressure (30 seconds written/voice).','why'=>'<strong>Why it matters:</strong> Essential for interviews, stakeholder conversations, and leadership.','skills'=>'<ul class="text-left"><li><strong>Strategic framing</strong></li><li><strong>Processing speed & persuasion</strong></li></ul>')
);
?>

<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-center">
              <div>
                <h1 class="mb-1 h4">Critical Thinking Catalogue</h1>
                <p class="mb-0 text-muted">
                  Learn each assignment’s purpose, the skills it builds, preview sample tasks, and attempt exercises to build your profile.
                </p>
              </div>
              <div class="ms-auto text-center">
                <img src="../assets/img/ctimage.png" height="90" alt="Critical thinking illustration">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cards Grid -->
      <div class="row g-3">
        <?php foreach ($ctAssignments as $pt):
            $title_attr = htmlspecialchars($pt['title'], ENT_QUOTES, 'UTF-8');
            $brief_text = htmlspecialchars($pt['brief'], ENT_QUOTES, 'UTF-8');
            $why_attr   = $pt['why'];
            $skills_attr = $pt['skills'];
            $imgfile = isset($ct_images[$pt['id']]) ? $ct_images[$pt['id']] : '';
            $imgpath = $imgfile ? ('../assets/img/' . $imgfile) : '';
        ?>
        <div class="col-sm-6 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
              <div class="d-flex align-items-center mb-3">
                <?php if ($imgpath && file_exists(dirname(__FILE__).'/../assets/img/'.$imgfile)): ?>
                  <img src="<?php echo $imgpath; ?>" alt="<?php echo $title_attr; ?> icon" style="height:48px; width:auto; margin-right:12px; box-shadow:0 2px 6px rgba(0,0,0,0.06); border-radius:4px;">
                <?php else: ?>
                  <i class="bx bx-bulb bx-lg text-primary me-3" style="margin-right:12px;"></i>
                <?php endif; ?>
                <div>
                  <h5 class="card-title mb-0"><?php echo $title_attr; ?></h5>
                  <small class="text-muted"><?php echo $brief_text; ?></small>
                </div>
              </div>

              <p class="text-muted small" style="min-height:44px;"><?php echo $brief_text; ?></p>

              <div class="mt-auto d-flex justify-content-between">
                <div>
                  <button class="btn btn-outline-primary btn-sm btn-view"
                          data-title="<?php echo $title_attr; ?>"
                          data-why="<?php echo htmlspecialchars($why_attr, ENT_QUOTES, 'UTF-8'); ?>"
                          data-skills="<?php echo htmlspecialchars($skills_attr, ENT_QUOTES, 'UTF-8'); ?>">
                    View
                  </button>

                  <button class="btn btn-primary btn-sm btn-preview"
                          data-type-slug="<?php echo $pt['id']; ?>"
                          data-title="<?php echo $title_attr; ?>">
                    Preview
                  </button>
                </div>

                <div>
                  <button class="btn btn-sm btn-info btn-mygrades"
                          data-type-slug="<?php echo $pt['id']; ?>"
                          data-assignment-id="<?php echo $pt['dbid']; ?>"
                          style="display:none;">
                    Grades <span class="badge badge-count" style="display:inline-block; margin-left:8px; font-weight:600;">0</span>
                  </button>

                  <button class="btn btn-sm btn-success btn-start"
                          data-type-slug="<?php echo $pt['id']; ?>">
                    Attempt Now
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
(function () {
  let CT_PROBLEMS_JSON = [];

  // Load JSON dynamically
  function loadCTJson() {
    return fetch('ct_problems.json', {cache: 'no-store'})
      .then(res => res.json())
      .then(json => { CT_PROBLEMS_JSON = json; })
      .catch(err => console.error("Could not load ct_problems.json", err));
  }

  // Show view modal
  function setupViewButtons() {
    const viewButtons = document.getElementsByClassName('btn-view');
    for (let btn of viewButtons) {
      btn.addEventListener('click', ev => {
        ev.preventDefault();
        const title = btn.dataset.title || '';
        const why = btn.dataset.why || '';
        const skills = btn.dataset.skills || '';
        const html = `<div style='text-align:left;'>
                        <div class='mb-2'>${why}</div>
                        <div class='mb-1'><strong>Core skills</strong></div>
                        <div>${skills}</div>
                      </div>`;
        Swal.fire({ title, html, icon: 'info', confirmButtonText: 'OK' });
      });
    }
  }

  // Build preview modal
  function buildPreviewHtml(type, title) {
    const items = CT_PROBLEMS_JSON.filter(p => p.type_id === type);
    if (!items.length) return "<em>No preview data found.</em>";

    const grouped = {};
    items.forEach(p => { grouped[p.level] = p; });
    function render(level, p) {
      if (!p) return `<div data-level='${level}'><em>No preview available</em></div>`;
      return `<div class='level-block align-left' data-level='${level}' style='display:none'>
                <strong>Problem Statement (${level})</strong><div>${p.statement}</div>
                <strong>How to approach</strong><div>${p.how_to}</div>
                <strong>Expected outcome</strong><div>${p.expected_outcome}</div>
              </div>`;
    }

    return `<div>
      <div class='mb-2 text-center'>
        <button class='swal-level-tab btn btn-sm btn-outline-primary me-1' data-lvl='kid'>Kid</button>
        <button class='swal-level-tab btn btn-sm btn-outline-primary me-1' data-lvl='teen'>Teen</button>
        <button class='swal-level-tab btn btn-sm btn-outline-primary' data-lvl='adult'>Adult</button>
      </div>
      ${render('kid', grouped.kid)} ${render('teen', grouped.teen)} ${render('adult', grouped.adult)}
      <div class='mt-3 text-end'><button id='swal-close' class='btn btn-sm btn-secondary'>Close</button></div>
    </div>`;
  }

  // Setup preview
  function setupPreviewButtons() {
    const previewButtons = document.getElementsByClassName('btn-preview');
    for (let btn of previewButtons) {
      btn.addEventListener('click', ev => {
        ev.preventDefault();
        const type = btn.dataset.typeSlug;
        const title = btn.dataset.title;
        const html = buildPreviewHtml(type, title);
        Swal.fire({ title: title + ' — Preview', html, width: 800, showConfirmButton: false });
        setTimeout(() => {
          const modal = document.querySelector('.swal2-popup');
          const tabs = modal.querySelectorAll('.swal-level-tab');
          const blocks = modal.querySelectorAll('.level-block');
          function show(lvl) {
            blocks.forEach(b => b.style.display = b.dataset.level === lvl ? 'block' : 'none');
          }
          show('kid');
          tabs.forEach(tab => tab.addEventListener('click', () => show(tab.dataset.lvl)));
          modal.querySelector('#swal-close').addEventListener('click', () => Swal.close());
        }, 150);
      });
    }
  }

  // Setup Grades CTA
  document.addEventListener('click', function(ev){
    let el = ev.target;
    while(el && el !== document){
      if(el.classList && el.classList.contains('btn-mygrades')){
        ev.preventDefault();
        const type = el.dataset.typeSlug;
        const assignment = el.dataset.assignmentId;
        if (!type || !assignment) return;
        window.location.href = `ct_view_grades.php?type=${encodeURIComponent(type)}&assignment=${encodeURIComponent(assignment)}`;
        return;
      }
      el = el.parentNode;
    }
  });

  // Apply counts visibility
  function applyMyGradesVisibility() {
    fetch(window.location.pathname + '?action=check_has_attempts_all')
      .then(r => r.json())
      .then(json => {
        if (!json.success) return;
        const counts = json.counts;
        document.querySelectorAll('.btn-mygrades').forEach(b => {
          const type = b.dataset.typeSlug;
          const cnt = counts[type] || 0;
          const badge = b.querySelector('.badge-count');
          if (cnt > 0) { b.style.display = ''; badge.textContent = cnt; }
          else { b.style.display = 'none'; }
        });
      });
  }

  document.addEventListener('DOMContentLoaded', async function(){
    await loadCTJson();
    setupViewButtons();
    setupPreviewButtons();
    applyMyGradesVisibility();
  });
})();
</script>

<style type="text/css">
.card img { display:inline-block; vertical-align:middle; }
.badge-count { background: rgba(0,0,0,0.08); padding: 2px 6px; border-radius: 999px; font-size: 12px; }
.level-block { display:none; padding:6px 2px; }
</style>

<?php require_once('../platformFooter.php'); ?>
