<?php
/**
 * Astraal LXP critical_thinking.php
 * Instructor Enhanced Version
 * Adds dynamic statement & submission counts for each assignment.
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

/* --- mysqli connection --- */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

if (empty($mysqli) || !($mysqli instanceof mysqli)) {
    die("Database connection not found. Ensure config.php defines \$coni (mysqli).");
}

/* ----------------- FRONT-END DATA ----------------- */

/* image mapping */
$ct_images = array(
    'fact_opinion' => 'ct1.png',
    'coffee_chat'     => 'ct2.png',
    'worldly_words'   => 'ct3.png',
    'alien_guide'     => 'ct4.png',
    'talk_it_out'     => 'ct5.png',
    'elevator_pitch'  => 'ct6.png'
);

/* catalogue */
$ctAssignments = array(
    array('id'=>'fact_opinion','title'=>'Fact vs Opinion','brief'=>'Differentiate objective evidence from subjective viewpoints; detect emotional persuasion.','why'=>'<strong>Why it matters:</strong> Helps you spot misinformation, weigh claims, and make evidence-based decisions.','skills'=>'<ul class="text-left"><li><strong>Analytical reasoning:</strong> Evidence evaluation</li><li><strong>Bias detection:</strong> Emotional trigger awareness</li></ul>'),
    array('id'=>'coffee_chat','title'=>'Coffee House Chat','brief'=>'Role-play conversations to practice empathy, listening, and perspective-taking.','why'=>'<strong>Why it matters:</strong> Builds social reasoning & conflict diffusion skills needed in teams and life.','skills'=>'<ul class="text-left"><li><strong>Perspective-taking</strong></li><li><strong>Active listening & emotional labelling</strong></li></ul>'),
    array('id'=>'worldly_words','title'=>'Worldly Words','brief'=>'Convey complex ideas using only 10 words to practice concision and precision.','why'=>'<strong>Why it matters:</strong> Sharpens clarity for emails, pitches, and time-limited communications.','skills'=>'<ul class="text-left"><li><strong>Concise expression</strong></li><li><strong>Cognitive flexibility</strong></li></ul>'),
    array('id'=>'alien_guide','title'=>'Alien Travel Guide','brief'=>'Explain everyday concepts to an “alien” — surface assumptions and restructure ideas.','why'=>'<strong>Why it matters:</strong> Teaches metacognition, decentering, and bias reduction.','skills'=>'<ul class="text-left"><li><strong>Metacognition & schema restructuring</strong></li><li><strong>Inclusive explanation</strong></li></ul>'),
    array('id'=>'talk_it_out','title'=>'Talk It Out','brief'=>'Construct claim → evidence → counterargument → conclusion structures.','why'=>'<strong>Why it matters:</strong> Builds argumentation skills and resilience to counterarguments.','skills'=>'<ul class="text-left"><li><strong>Argument construction</strong></li><li><strong>Logical fallacy detection</strong></li></ul>'),
    array('id'=>'elevator_pitch','title'=>'Elevator Pitch','brief'=>'Deliver short, persuasive messages under time pressure (30 seconds written/voice).','why'=>'<strong>Why it matters:</strong> Essential for interviews, stakeholder conversations, and leadership.','skills'=>'<ul class="text-left"><li><strong>Strategic framing</strong></li><li><strong>Processing speed & persuasion</strong></li></ul>')
);

/* ----------------- ENHANCEMENT: Counts ----------------- */
$ctCounts = [];
$sql = "
SELECT sa.assignment_id,
       COUNT(DISTINCT st.id) AS total_statements,
       COUNT(DISTINCT sub.id) AS total_submissions
FROM ct_subassignments sa
LEFT JOIN ct_statements st ON st.subassignment_id = sa.id
LEFT JOIN ct_submissions sub ON sub.subassignment_id = sa.id
GROUP BY sa.assignment_id;
";

if ($res = $mysqli->query($sql)) {
    while ($row = $res->fetch_assoc()) {
        $ctCounts[strtolower($row['assignment_id'])] = [
            'statements'  => (int)$row['total_statements'],
            'submissions' => (int)$row['total_submissions']
        ];
    }
    $res->free();
}

$page = "criticalThinking";
require_once('instructorHead_Nav2.php');
?>

<div class="layout-page">
  <?php require_once('instructorNav.php'); ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-center">
              <div>
                <h1 class="mb-1 h4">Critical Thinking Catalogue - (Instructor)</h1>
                <p class="mb-0 text-muted">
                  Use the buttons on each card to view details or open the CT admin pages (manage statements / view submissions).
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
      <div class="row g-3" id="cardsGrid">
        <?php foreach ($ctAssignments as $pt):
            $aid = strtolower($pt['id']);
            $counts = isset($ctCounts[$aid]) ? $ctCounts[$aid] : ['statements'=>0, 'submissions'=>0];
            $title_attr  = htmlspecialchars($pt['title'], ENT_QUOTES, 'UTF-8');
            $brief_text  = htmlspecialchars($pt['brief'], ENT_QUOTES, 'UTF-8');
            $why_attr    = $pt['why'];
            $skills_attr = $pt['skills'];
            $imgfile     = isset($ct_images[$pt['id']]) ? $ct_images[$pt['id']] : '';
            $imgpath     = $imgfile ? ('../assets/img/' . $imgfile) : '';
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
			  
			  <div class="mt-auto d-flex justify-content-center">
			  <div>
                  <button class="btn btn-outline-primary btn-sm btn-view"
                          data-title="<?php echo $title_attr; ?>"
                          data-why="<?php echo htmlspecialchars($why_attr, ENT_QUOTES, 'UTF-8'); ?>"
                          data-skills="<?php echo htmlspecialchars($skills_attr, ENT_QUOTES, 'UTF-8'); ?>">
                    Quick View
                  </button>
                </div>
				</div>
				

              <div class="mt-2 d-flex justify-content-between">
                

                <div>
                  <a class="btn btn-sm btn-outline-secondary ms-1" href="ct_admin.php?action=list&type=<?php echo urlencode($pt['id']); ?>">
                    Manage Statements (<?php echo $counts['statements']; ?>)
                  </a>
                  <a class="btn btn-sm btn-outline-info ms-1" href="ct_admin.php?action=attempts&type=<?php echo urlencode($pt['id']); ?>">
                    View Submissions (<?php echo $counts['submissions']; ?>)
                  </a>
                </div>
              </div>
			  
			  
				
				

            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <!-- /Grid -->

    </div>
  

<style>
.small-muted { font-size: .9rem; color: #6c757d; }
.level-block { display:none; padding:6px 2px; }
.statement-row { margin-bottom:8px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
(function(){
  function esc(s){ if(s===null||s===undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

  var viewButtons = document.getElementsByClassName('btn-view');
  for (var i=0;i<viewButtons.length;i++){
    (function(btn){
      btn.addEventListener('click', function(ev){
        ev.preventDefault();
        var title = btn.getAttribute('data-title') || '';
        var why   = btn.getAttribute('data-why') || '';
        var skills= btn.getAttribute('data-skills') || '';
        var html = '<div style="text-align:left;">' + '<div class="mb-2">' + why + '</div>' + '<div class="mb-1"><strong>Core skills</strong></div>' + '<div>' + skills + '</div></div>';
        Swal.fire({ title: esc(title), html: html, icon: 'info', focusConfirm: false, confirmButtonText: 'OK', customClass:{ popup:'swal2-border-radius' } });
      }, false);
    })(viewButtons[i]);
  }
})();
</script>

<?php require_once('../platformFooter.php'); ?>
