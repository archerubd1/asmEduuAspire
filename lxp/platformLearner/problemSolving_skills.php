<?php
/**
 * Astraal LXP – Learner Problem Solving Skills Catalogue (Merged UX)
 * PHP 5.4 + MySQL 5.x Safe
 * Uses static preview JSON
 * Start Exercise passes: ?type={slug}&level={kid/teen/adult}
 *
 * Changes:
 *  - uses $coni mysqli connection (if available)
 *  - AJAX endpoint check_has_attempts_all now returns attempt counts per type (integer)
 *  - My Grades button shows a small badge with the attempt count (visible only if >0)
 *  - Preview modal: removed "Start Exercise" from the modal (keeps Close & TTS)
 *
 * No other features removed or truncated.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id    = isset($_SESSION['phx_user_id']) ? (int) $_SESSION['phx_user_id'] : 0;
$phx_user_login = $_SESSION['phx_user_login'];

/* ------------------ DB connection: prefer $coni ------------------ */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) {
    $mysqli = $coni;
} elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) {
    $mysqli = $GLOBALS['coni'];
} elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) {
    $mysqli = $GLOBALS['mysqli'];
} elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) {
    $mysqli = $GLOBALS['conn'];
} elseif (isset($conn) && $conn instanceof mysqli) {
    $mysqli = $conn;
}

/* ---------------- helper: get variant ids for a type (uses $mysqli) ---------------- */
function get_variant_ids_for_type_conn($mysqli, $type_slug) {
    $ids = array();
    if (empty($mysqli) || !$type_slug) return $ids;
    $sql = "SELECT id FROM problem_variants WHERE problem_slug LIKE CONCAT(?, '%')";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $type_slug);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) $ids[] = (int)$r['id'];
        }
        $stmt->close();
    }
    return $ids;
}

/* ---------------- AJAX: check_has_attempts_all ----------------
   Returns JSON { success:true, counts: {authentic:2, ...} }
   If DB not available, returns zeros.
------------------------------------------------------------------------- */
if (isset($_GET['action']) && $_GET['action'] === 'check_has_attempts_all') {
    header('Content-Type: application/json; charset=utf-8');

    $types = array('authentic','procedural','strategic','diagnosis','design','transfer','exploratory','quantum','indic');
    $result = array();
    foreach ($types as $t) $result[$t] = 0;

    if ($mysqli instanceof mysqli) {
        foreach ($types as $type) {
            $ids = get_variant_ids_for_type_conn($mysqli, $type);
            if (empty($ids)) { $result[$type] = 0; continue; }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT COUNT(*) AS cnt FROM problem_attempts WHERE user_login = ? AND problem_variant_id IN ($placeholders)";
            if ($stmt = $mysqli->prepare($sql)) {
                // bind types: s + i...
                $types_spec = 's' . str_repeat('i', count($ids));
                $bind_params = array();
                $bind_params[] = & $types_spec;
                $bind_params[] = & $phx_user_login;
                for ($i=0; $i<count($ids); $i++) {
                    $bind_params[] = & $ids[$i];
                }
                call_user_func_array(array($stmt, 'bind_param'), $bind_params);
                if ($stmt->execute()) {
                    $res = $stmt->get_result();
                    if ($r = $res->fetch_assoc()) {
                        $result[$type] = isset($r['cnt']) ? (int)$r['cnt'] : 0;
                    }
                }
                $stmt->close();
            }
        }
    }

    echo json_encode(array('success' => true, 'counts' => $result));
    exit;
}

/* ---------------- END AJAX ---------------- */

$page = "problemSolving";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- Static Problem-Solving Catalogue -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-center">
                <div class="mr-3">
                    <h1 class="mb-1 h4">Problem-Solving Catalogue</h1>
                    <p class="mb-0 text-muted">
                        Click <strong>View</strong> to learn why each problem type matters.
                        Click <strong>Preview</strong> to see Kid / Teen / Adult variations.
                        <br>Click <strong>Start Exercise</strong> to begin.
                    </p>
                </div>
                <div class="ms-auto text-center">
                    <img src="../assets/img/illustrations/problem-solving-light.jpg"
                         height="90"
                         alt="Problem Solving Illustration"
                         data-app-dark-img="illustrations/problem-solving-dark.jpg"
                         data-app-light-img="illustrations/problem-solving-light.jpg">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cards Grid -->
<div class="row g-3">

<?php
// Static list identical to your existing version
$problemTypes = array(
    array('id'=>'authentic','title'=>'Authentic Problem','icon'=>'bx bx-world','brief'=>'Real-life scenarios requiring multi-step action and community impact.','why'=>'<strong>Why this matters:</strong> Anchors learning in context, builds transfer and practical reasoning.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Applied reasoning, causal mapping</li><li><strong>Social:</strong> Community engagement, planning</li></ul>'),
    array('id'=>'procedural','title'=>'Procedural Problem','icon'=>'bx bx-cog','brief'=>'Step-by-step tasks to build accuracy and repeatability.','why'=>'<strong>Why this matters:</strong> Develops fluency via practice and immediate feedback.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Sequential reasoning, procedure accuracy</li><li><strong>Behavioral:</strong> Attention to detail</li></ul>'),
    array('id'=>'strategic','title'=>'Strategic Problem','icon'=>'bx bx-target-lock','brief'=>'Planning problems requiring trade-offs & justification.','why'=>'<strong>Why this matters:</strong> Trains high-level planning, trade-off evaluation, and metacognition.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Prioritisation, scenario planning</li><li><strong>Social:</strong> Negotiation, fairness</li></ul>'),
    array('id'=>'diagnosis','title'=>'Diagnosis / Troubleshooting','icon'=>'bx bx-search-alt','brief'=>'Evidence-based identification of root causes and fixes.','why'=>'<strong>Why this matters:</strong> Encourages hypothesis testing and structured inference under uncertainty.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Hypothesis testing, evidence evaluation</li><li><strong>Behavioral:</strong> Methodical inquiry</li></ul>'),
    array('id'=>'design','title'=>'Design / Synthesis','icon'=>'bx bx-brush','brief'=>'Create a useful object or system by integrating needs and constraints.','why'=>'<strong>Why this matters:</strong> Encourages generative thinking and functional creativity.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Integration, abstraction</li><li><strong>Behavioral:</strong> Prototyping, documentation</li></ul>'),
    array('id'=>'transfer','title'=>'Transfer / Analogical','icon'=>'bx bx-transfer-alt','brief'=>'Apply principles from one situation to another.','why'=>'<strong>Why this matters:</strong> Strengthens flexible thinking and problem transfer abilities.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Analogy, abstraction</li><li><strong>Behavioral:</strong> Adaptability</li></ul>'),
    array('id'=>'exploratory','title'=>'Exploratory / Discovery','icon'=>'bx bx-search','brief'=>'Open-ended inquiry to find patterns & hypotheses.','why'=>'<strong>Why this matters:</strong> Builds curiosity, pattern recognition, and hypothesis-driven inquiry.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Inductive reasoning, pattern discovery</li><li><strong>Behavioral:</strong> Curiosity</li></ul>'),
    array('id'=>'quantum','title'=>'Quantum-Conceptual Reasoning','icon'=>'bx bx-atom','brief'=>'Decisions under overlapping preferences and probabilities.','why'=>'<strong>Why this matters:</strong> Trains comfort with ambiguity & probabilistic reasoning.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Probability thinking</li><li><strong>Behavioral:</strong> Ambiguity tolerance</li></ul>'),
    array('id'=>'indic','title'=>'Indic Philosophical Cognitive','icon'=>'bx bx-leaf','brief'=>'Multi-perspective reasoning using Indic logic.','why'=>'<strong>Why this matters:</strong> Builds reflective thinking and ethical framing.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Multi-perspective analysis</li><li><strong>Behavioral:</strong> Empathy</li></ul>')
);

foreach ($problemTypes as $pt):
    $title_attr = htmlspecialchars($pt['title'], ENT_QUOTES, 'UTF-8');
    $brief_text = htmlspecialchars($pt['brief'], ENT_QUOTES, 'UTF-8');
    $why_attr   = htmlspecialchars($pt['why'], ENT_QUOTES, 'UTF-8');
    $skills_attr = htmlspecialchars($pt['skills'], ENT_QUOTES, 'UTF-8');
?>

    <div class="col-sm-6 col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <i class="<?php echo $pt['icon']; ?> bx-lg text-primary me-3"></i>
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
                                data-why="<?php echo $why_attr; ?>"
                                data-skills="<?php echo $skills_attr; ?>">
                            View
                        </button>

                        <button class="btn btn-primary btn-sm btn-preview"
                                data-type-slug="<?php echo $pt['id']; ?>"
                                data-title="<?php echo $title_attr; ?>">
                            Preview
                        </button>
                    </div>

                    <div>
                        <!-- My Grades button: hidden by default; revealed by JS if attempts exist -->
                        <button class="btn btn-sm btn-info btn-mygrades" data-type-slug="<?php echo $pt['id']; ?>" style="display:none;">
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
<!-- /Grid -->

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Canonical Static Preview JSON (unchanged) -->
<script type="text/javascript">
window.PROBLEMS_JSON = [

  /* ---------------- AUTHENTIC ---------------- */
  {"problem_id":"authentic-kid","type_id":"authentic","type_title":"Authentic Problem","level":"kid",
   "statement":"The park near your home is messy after playtime. Think of 2 simple things you and friends can do to keep it clean.",
   "how_to":"1) Ask friends what makes it messy. 2) Decide 2 small actions (e.g., put trash in bins). 3) Try the actions for one weekend.",
   "expected_outcome":"A short plan to keep the park tidy that you can try with friends."
  },
  {"problem_id":"authentic-teen","type_id":"authentic","type_title":"Authentic Problem","level":"teen",
   "statement":"Your community park gets littered after weekends. Design a one-week trial to reduce litter involving neighbours and signs.",
   "how_to":"1) Observe hotspots. 2) Propose 3 interventions (bins, signs, volunteer shifts). 3) Get neighbour feedback and run a trial.",
   "expected_outcome":"A tested mini-plan with observations and measurable results."
  },
  {"problem_id":"authentic-adult","type_id":"authentic","type_title":"Authentic Problem","level":"adult",
   "statement":"The local park sees recurring littering after weekend events. Draft a staged community-led cleanup & prevention plan with simple metrics.",
   "how_to":"1) Map high-traffic zones and stakeholders. 2) Prioritise interventions by impact/cost. 3) Define KPIs and a 1-week pilot.",
   "expected_outcome":"A pragmatic pilot plan with roles, KPIs, and follow-up actions."
  },

  /* ---------------- PROCEDURAL ---------------- */
  {"problem_id":"procedural-kid","type_id":"procedural","type_title":"Procedural Problem","level":"kid",
   "statement":"Make lemonade by following and fixing a set of steps. Some steps are mixed up — put them in the right order.",
   "how_to":"1) Read all steps. 2) Reorder: wash hands → squeeze lemons → add sugar → mix → serve.",
   "expected_outcome":"A correct recipe sequence you can follow to make lemonade."
  },
  {"problem_id":"procedural-teen","type_id":"procedural","type_title":"Procedural Problem","level":"teen",
   "statement":"You have instructions to assemble a school project kit; steps may be missing or swapped.",
   "how_to":"1) Scan steps and identify missing safety steps. 2) Reorder logically. 3) Assemble and test the kit.",
   "expected_outcome":"A properly assembled kit and a checklist for repeatable assembly."
  },
  {"problem_id":"procedural-adult","type_id":"procedural","type_title":"Procedural Problem","level":"adult",
   "statement":"You must reconcile a short checklist of monthly household bills and spot errors in the payment sequence.",
   "how_to":"1) Review line items and dates. 2) Flag duplicates/timing issues. 3) Verify totals.",
   "expected_outcome":"An accurate reconciliation and corrected payment sequence."
  },

  /* ---------------- STRATEGIC ---------------- */
  {"problem_id":"strategic-kid","type_id":"strategic","type_title":"Strategic Problem","level":"kid",
   "statement":"There is one shared tablet. Make a fair plan so everyone gets time to play and learn.",
   "how_to":"1) List who needs it. 2) Set short time slots. 3) Create a rotation and test for a day.",
   "expected_outcome":"A simple rotation plan that everyone understands."
  },
  {"problem_id":"strategic-teen","type_id":"strategic","type_title":"Strategic Problem","level":"teen",
   "statement":"Create a weekly schedule for a shared laptop among siblings with different study and gaming needs.",
   "how_to":"1) Survey needs. 2) Allocate slots & backup rules. 3) Communicate and test schedule.",
   "expected_outcome":"A transparent weekly schedule with conflict-resolution rules."
  },
  {"problem_id":"strategic-adult","type_id":"strategic","type_title":"Strategic Problem","level":"adult",
   "statement":"Design a fair usage policy for a shared office resource (e.g., conference room).",
   "how_to":"1) Map needs. 2) Define booking & priority rules. 3) Add escalation steps.",
   "expected_outcome":"A defensible resource policy with clear rules and escalation steps."
  },

  /* ---------------- DIAGNOSIS ---------------- */
  {"problem_id":"diagnosis-kid","type_id":"diagnosis","type_title":"Diagnosis / Troubleshooting","level":"kid",
   "statement":"A flashlight won’t turn on. Check simple, safe things to find the problem.",
   "how_to":"1) Try new batteries. 2) Check switch/bulb. 3) Ask adult if still broken.",
   "expected_outcome":"A safe checklist and likely fix."
  },
  {"problem_id":"diagnosis-teen","type_id":"diagnosis","type_title":"Diagnosis / Troubleshooting","level":"teen",
   "statement":"A phone won’t charge. Run a stepwise checklist to identify why (cable, port, charger, battery).",
   "how_to":"1) Try alternate charger. 2) Clean port. 3) Test battery settings.",
   "expected_outcome":"A diagnostic note with likely cause and next steps."
  },
  {"problem_id":"diagnosis-adult","type_id":"diagnosis","type_title":"Diagnosis / Troubleshooting","level":"adult",
   "statement":"A small kitchen appliance stops working. Identify electrical, mechanical, or user issues.",
   "how_to":"1) Check power & cable. 2) Inspect for damage. 3) Follow manual troubleshooting.",
   "expected_outcome":"A diagnostic summary and recommended action."
  },

  /* ---------------- DESIGN ---------------- */
  {"problem_id":"design-kid","type_id":"design","type_title":"Design / Synthesis","level":"kid",
   "statement":"Design a backpack that makes carrying books easier — draw a simple picture.",
   "how_to":"1) Think what must fit. 2) Draw pockets and straps. 3) Explain usefulness.",
   "expected_outcome":"A drawing with explanation."
  },
  {"problem_id":"design-teen","type_id":"design","type_title":"Design / Synthesis","level":"teen",
   "statement":"Sketch a study desk layout that helps organize books, devices, and notes.",
   "how_to":"1) List needs. 2) Draw compartments. 3) Add usage notes.",
   "expected_outcome":"A functional sketch with rationale."
  },
  {"problem_id":"design-adult","type_id":"design","type_title":"Design / Synthesis","level":"adult",
   "statement":"Design a weekly meal prep system that reduces daily cooking time and waste.",
   "how_to":"1) Define constraints. 2) Propose batch-cooking plan. 3) Make schedule.",
   "expected_outcome":"A practical meal-prep plan."
  },

  /* ---------------- TRANSFER ---------------- */
  {"problem_id":"transfer-kid","type_id":"transfer","type_title":"Transfer / Analogical","level":"kid",
   "statement":"You learned to tie shoelaces. Explain how it helps tie a ribbon on a gift.",
   "how_to":"1) Identify loop/pull. 2) Map to ribbon. 3) Demonstrate.",
   "expected_outcome":"Short explanation showing transfer."
  },
  {"problem_id":"transfer-teen","type_id":"transfer","type_title":"Transfer / Analogical","level":"teen",
   "statement":"You learned to prepare simple pasta. Apply those rules to a stir-fry.",
   "how_to":"1) Map prep-order. 2) Adapt timing. 3) Test & compare.",
   "expected_outcome":"A transfer plan across dishes."
  },
  {"problem_id":"transfer-adult","type_id":"transfer","type_title":"Transfer / Analogical","level":"adult",
   "statement":"Having run a fundraiser, reuse the principles for another cause.",
   "how_to":"1) Identify core principles. 2) Adapt messaging. 3) Suggest rollout.",
   "expected_outcome":"A cross-context outreach plan."
  },

  /* ---------------- EXPLORATORY ---------------- */
  {"problem_id":"exploratory-kid","type_id":"exploratory","type_title":"Exploratory / Discovery","level":"kid",
   "statement":"Sort toys into two groups and explain why they belong together.",
   "how_to":"1) Observe. 2) Group. 3) Explain & test idea.",
   "expected_outcome":"Simple grouping and hypothesis."
  },
  {"problem_id":"exploratory-teen","type_id":"exploratory","type_title":"Exploratory / Discovery","level":"teen",
   "statement":"Group a small set of family photos by theme and propose a hypothesis.",
   "how_to":"1) Find patterns. 2) Form hypothesis. 3) Suggest questions.",
   "expected_outcome":"Hypothesis + validation plan."
  },
  {"problem_id":"exploratory-adult","type_id":"exploratory","type_title":"Exploratory / Discovery","level":"adult",
   "statement":"Explore a set of monthly bills to find patterns and propose one saving hypothesis.",
   "how_to":"1) Categorize spending. 2) Find spikes. 3) Propose experiment.",
   "expected_outcome":"Hypothesis + testing plan."
  },

  /* ---------------- QUANTUM ---------------- */
  {"problem_id":"quantum-kid","type_id":"quantum","type_title":"Quantum-Conceptual Reasoning","level":"kid",
   "statement":"Your family picks 1 movie. Everyone suggests 2 favorites.",
   "how_to":"1) List choices. 2) Count overlaps. 3) Pick top-voted.",
   "expected_outcome":"Simple voting result."
  },
  {"problem_id":"quantum-teen","type_id":"quantum","type_title":"Quantum-Conceptual Reasoning","level":"teen",
   "statement":"Friends suggest playlists for a trip. Choose one using overlap scores.",
   "how_to":"1) Map preferences. 2) Score overlaps. 3) Choose or combine.",
   "expected_outcome":"Reasoned probabilistic choice."
  },
  {"problem_id":"quantum-adult","type_id":"quantum","type_title":"Quantum-Conceptual Reasoning","level":"adult",
   "statement":"Allocate a small entertainment budget across 3 activities with overlapping appeal.",
   "how_to":"1) Estimate preference probabilities. 2) Compute expected satisfaction.",
   "expected_outcome":"An optimized allocation plan."
  },

  /* ---------------- INDIC ---------------- */
  {"problem_id":"indic-kid","type_id":"indic","type_title":"Indic Philosophical Cognitive","level":"kid",
   "statement":"Two friends argue about longer playtime. Explain both sides + suggest middle ground.",
   "how_to":"1) Explain each view. 2) Show partial correctness. 3) Suggest compromise.",
   "expected_outcome":"Balanced recommendation."
  },
  {"problem_id":"indic-teen","type_id":"indic","type_title":"Indic Philosophical Cognitive","level":"teen",
   "statement":"Two classmates disagree about project deadlines. Present both views & propose balanced plan.",
   "how_to":"1) List arguments. 2) Identify valid points. 3) Suggest flexible plan.",
   "expected_outcome":"Reconciled plan."
  },
  {"problem_id":"indic-adult","type_id":"indic","type_title":"Indic Philosophical Cognitive","level":"adult",
   "statement":"Evaluate a local policy (e.g., weekend street closure) using multi-view analysis.",
   "how_to":"1) List stakeholders. 2) Present evidence. 3) Propose compromise pilot.",
   "expected_outcome":"Balanced policy recommendation."
  }

];
</script>

<script type="text/javascript">
/* Robust preview + My Grades wiring with attempt-count badge and no "Start Exercise" in modal */
(function () {

    /* ---------------- helper: timestamp ---------------- */
    function fmtTimestamp(ts) {
        if (!ts) return '';
        try { return new Date(ts * 1000).toLocaleString(); } catch (e) { return ts; }
    }

    /* ---------- VIEW modal (unchanged) ---------- */
    var viewButtons = document.getElementsByClassName('btn-view');
    for (var i = 0; i < viewButtons.length; i++) {
        (function(btn){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                var title  = btn.getAttribute('data-title') || '';
                var why    = btn.getAttribute('data-why')   || '';
                var skills = btn.getAttribute('data-skills')|| '';
                var html = ''
                  + '<div style="text-align:left;">'
                  + '  <div class="mb-2">' + why + '</div>'
                  + '  <div class="mb-1"><strong>Core cognitive & behavioural skills:</strong></div>'
                  + '  <div>' + skills + '</div>'
                  + '</div>';
                Swal.fire({ title: title, html: html, icon: "info", focusConfirm: false, confirmButtonText: "OK", customClass: { popup: 'swal2-border-radius' } });
            }, false);
        })(viewButtons[i]);
    }

    /* ---------- Preview: robust implementation (no Start Exercise in modal) ---------- */

    // Build quick lookup map
    var PROBLEMS = window.PROBLEMS_JSON || [];
    var PROBLEM_MAP = {};
    for (var i = 0; i < PROBLEMS.length; i++) {
        var p = PROBLEMS[i];
        if (!p || !p.type_id || !p.level) continue;
        if (!PROBLEM_MAP[p.type_id]) PROBLEM_MAP[p.type_id] = {};
        PROBLEM_MAP[p.type_id][p.level] = p;
    }

    function safeGet(type, level) {
        if (!type || !level) return null;
        if (PROBLEM_MAP[type] && PROBLEM_MAP[type][level]) return PROBLEM_MAP[type][level];
        return null;
    }

    function renderVariantHtml(levelLabel, obj) {
        if (!obj) {
            return '<div class="level-block" data-level="'+ levelLabel +'">'
                 + '<div><strong>Problem Statement (' + levelLabel.charAt(0).toUpperCase() + levelLabel.slice(1) + ')</strong>'
                 + '<div style="margin-top:6px;"><em>Preview not available for this level.</em></div></div></div>';
        }
        var st = obj.statement || '';
        var ht = obj.how_to || obj.how || '';
        var out = obj.expected_outcome || obj.outcome || '';
        return '<div class="level-block" data-level="'+ levelLabel + '">'
             + '<div><strong>Problem Statement (' + levelLabel.charAt(0).toUpperCase() + levelLabel.slice(1) + ')</strong>'
             + '<div style="margin-top:6px;">' + st + '</div></div>'
             + '<div class="mt-2"><strong>How To Approach</strong><div style="margin-top:6px;">' + ht + '</div></div>'
             + '<div class="mt-2"><strong>Expected Outcome</strong><div style="margin-top:6px;">' + out + '</div></div>'
             + '</div>';
    }

    function buildPreviewHtml(title, type) {
        var kid  = safeGet(type, 'kid');
        var teen = safeGet(type, 'teen');
        var adult = safeGet(type, 'adult');
        var html = '<div style="text-align:left;">'
                 + '<div class="mb-2" style="text-align:center;">'
                 + '  <button class="swal-level-tab btn btn-sm btn-outline-primary me-1" data-lvl="kid" aria-selected="true">Kid</button>'
                 + '  <button class="swal-level-tab btn btn-sm btn-outline-primary me-1" data-lvl="teen" aria-selected="false">Teen</button>'
                 + '  <button class="swal-level-tab btn btn-sm btn-outline-primary" data-lvl="adult" aria-selected="false">Adult</button>'
                 + '</div>'
                 + renderVariantHtml('kid', kid)
                 + renderVariantHtml('teen', teen)
                 + renderVariantHtml('adult', adult)
                 + '<div class="mt-3 d-flex justify-content-between">'
                 + '  <div><button id="swal-tts-play" class="btn btn-sm btn-outline-primary">🔊 Listen</button> <button id="swal-tts-stop" class="btn btn-sm btn-outline-danger">⏹ Stop</button></div>'
                 + '  <div><button id="swal-close" class="btn btn-sm btn-secondary">Close</button></div>'
                 + '</div></div>';
        return html;
    }

    var previewButtons = document.getElementsByClassName('btn-preview');
    for (var j = 0; j < previewButtons.length; j++) {
        (function(btn){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                var typeSlug = btn.getAttribute('data-type-slug') || btn.getAttribute('data-type') || btn.dataset.type;
                var title    = btn.getAttribute('data-title') || btn.dataset.title || (typeSlug || "Preview");
                Swal.fire({ title: title + " — Preview", html: buildPreviewHtml(title, typeSlug), width: 820, showConfirmButton: false, showCloseButton: false, customClass: { popup: 'swal2-border-radius' } });

                setTimeout(function(){
                    var modal = document.querySelector('.swal2-popup') || document.body;
                    var modalTabs = modal.querySelectorAll('.swal-level-tab');
                    var modalBlocks = modal.querySelectorAll('.level-block');

                    function showLevel(lvl) {
                        for (var i = 0; i < modalBlocks.length; i++) {
                            var b = modalBlocks[i];
                            b.style.display = (b.getAttribute('data-level') === lvl ? 'block' : 'none');
                        }
                        for (var t = 0; t < modalTabs.length; t++) {
                            modalTabs[t].setAttribute('aria-selected', modalTabs[t].getAttribute('data-lvl') === lvl ? 'true' : 'false');
                        }
                        modal._selectedLevel = lvl;
                    }
                    showLevel('kid');

                    for (var t = 0; t < modalTabs.length; t++) {
                        (function(tab){ tab.addEventListener('click', function(ev){ ev.preventDefault(); showLevel(tab.getAttribute('data-lvl')); }, false); })(modalTabs[t]);
                    }

                    var synth = window.speechSynthesis || null;
                    var ttsPlay = modal.querySelector('#swal-tts-play');
                    var ttsStop = modal.querySelector('#swal-tts-stop');

                    function speakCurrent() {
                        if (!synth) return;
                        try { synth.cancel(); } catch(e){}
                        var active = modal.querySelector('.level-block[style*="display: block"]') || modal.querySelector('.level-block');
                        if (!active) return;
                        var utter = new SpeechSynthesisUtterance(active.innerText || active.textContent || '');
                        utter.rate = 1.0;
                        synth.speak(utter);
                    }
                    if (ttsPlay) ttsPlay.addEventListener('click', speakCurrent, false);
                    if (ttsStop) ttsStop.addEventListener('click', function(){ if (synth) try { synth.cancel(); } catch(e){} }, false);

                    var closeBtn = modal.querySelector('#swal-close');
                    if (closeBtn) closeBtn.addEventListener('click', function(ev){ ev.preventDefault(); Swal.close(); }, false);

                }, 150);
            }, false);
        })(previewButtons[j]);
    }

    /* Start exercise quick button (card-level) */
    var startBtns = document.getElementsByClassName('btn-start');
    for (var s = 0; s < startBtns.length; s++) {
        (function(btn){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                var type = btn.getAttribute('data-type-slug') || btn.getAttribute('data-type') || btn.dataset.type;
                if (!type) return;
                window.location.href = "problem_solving.php?type=" + encodeURIComponent(type);
            }, false);
        })(startBtns[s]);
    }

    /* ---------------- Apply My Grades visibility + attempt-count badge wiring ---------------- */
    function applyMyGradesVisibility() {
        fetch(window.location.pathname + '?action=check_has_attempts_all', {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.json(); })
        .then(function(json){
            if (!json || !json.success || !json.counts) return;
            var counts = json.counts;
            var btns = document.getElementsByClassName('btn-mygrades');
            for (var i = 0; i < btns.length; i++) {
                var b = btns[i];
                var type = b.getAttribute('data-type-slug') || b.getAttribute('data-type') || b.dataset.type;
                if (!type) continue;
                var cnt = (counts.hasOwnProperty(type) ? parseInt(counts[type], 10) : 0);
                var badge = b.querySelector('.badge-count');
                if (cnt > 0) {
                    b.style.display = '';
                    if (badge) {
                        badge.textContent = cnt;
                    } else {
                        // add badge span if missing
                        var s = document.createElement('span');
                        s.className = 'badge badge-count';
                        s.style.cssText = 'display:inline-block; margin-left:8px; font-weight:600;';
                        s.textContent = cnt;
                        b.appendChild(s);
                    }
                } else {
                    b.style.display = 'none';
                }
            }
        })
        .catch(function(err){ console.error('Could not determine attempts presence', err); });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', applyMyGradesVisibility);
    else applyMyGradesVisibility();

    // Delegate click to My Grades buttons -> navigate to my_attempts.php?type=...
    document.addEventListener('click', function(ev){
        var el = ev.target;
        while (el && el !== document) {
            if (el.classList && el.classList.contains('btn-mygrades')) {
                ev.preventDefault();
                var type = el.getAttribute('data-type-slug') || el.getAttribute('data-type') || el.dataset.type;
                if (!type) return;
                window.location.href = 'my_attempts.php?type=' + encodeURIComponent(type);
                return;
            }
            el = el.parentNode;
        }
    }, false);

})();
</script>

<style type="text/css">

/* Icon sizing inside cards */
.card .bx-lg {
    font-size: 28px;
}

/* SweetAlert visual polish */
.swal2-border-radius {
    border-radius: 12px;
}

/* Tab styling for Kid / Teen / Adult */
.level-tab[aria-selected="true"] {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}
.level-tab[aria-selected="false"] {
    background-color: #ffffff;
    color: #000000;
}

/* Level blocks (one visible at a time) */
.level-block {
    display: none;
    padding: 6px 2px;
}

/* Badge small style */
.badge-count {
    background: rgba(0,0,0,0.08);
    padding: 2px 6px;
    border-radius: 999px;
    font-size: 12px;
}

/* Card consistency */
.card, .card .card-text, .card small {
    color: #111;
}

@media (max-width: 575px) {
    .card .card-body {
        padding: 12px;
    }
}

</style>

<?php
// end of file - footer included by your layout template
?>
  <?php require_once('../platformFooter.php'); ?>