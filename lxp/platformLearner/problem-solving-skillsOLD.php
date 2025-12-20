<?php
/**
 * Astraal LXP - Learners Problem Solving Module (Full) - Preview -> Start Exercise (DOB-based)
 * - Embedded canonical JSON (window.PROBLEMS_JSON)
 * - Preview modal shows Kid/Teen/Adult
 * - Start Exercise redirects to exercise_start.php?type=... (or &level=... if override)
 * - PHP 5.4 compatible
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

$page = "problemSolving";
require_once('learnerHead_Nav2.php');
?>

<!-- Layout container -->
<div class="layout-page">

    <?php require_once('learnersNav.php'); ?>

    <div class="content-wrapper" role="main" aria-labelledby="pageTitle">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card" role="region" aria-label="Problem Solving Introduction">
                        <div class="card-body d-flex flex-column flex-md-row align-items-center">
                            <div class="mr-3">
                                <h1 id="pageTitle" class="mb-1 h4">Problem-Solving Catalogue</h1>
                                <p class="mb-0 text-muted">Click <strong>View</strong> to see why each problem type matters and the cognitive skills you will build. Click <strong>Preview</strong> to see an actual problem statement, a concise 'how-to' approach, and the expected learning outcome for Kid / Teen / Adult tasks. Use audio to listen.</p>
                            </div>
                            <div class="ml-auto text-center">
                                <img src="../assets/img/illustrations/problem-solving-light.jpg" height="90" alt="Problem Solving Illustration"
                                     data-app-dark-img="illustrations/problem-solving-dark.jpg"
                                     data-app-light-img="illustrations/problem-solving-light.jpg" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid -->
            <div class="row g-3" aria-live="polite">
                <?php
                // Keep your static list for UI when DB isn't used yet.
                $problemTypes = array(
                    array('id'=>'authentic','title'=>'Authentic Problem','icon'=>'bx bx-world','brief'=>'Real-life scenarios requiring multi-step action and community impact.','why'=>'<strong>Why this matters:</strong> Anchors learning in context, builds transfer and practical reasoning.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Applied reasoning, causal mapping</li><li><strong>Social:</strong> Community engagement, planning</li></ul>'),
                    array('id'=>'procedural','title'=>'Procedural Problem','icon'=>'bx bx-cog','brief'=>'Step-by-step tasks to build accuracy and repeatability.','why'=>'<strong>Why this matters:</strong> Develops fluency via practice and immediate feedback.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Sequential reasoning, procedure accuracy</li><li><strong>Behavioral:</strong> Attention to detail</li></ul>'),
                    array('id'=>'strategic','title'=>'Strategic Problem','icon'=>'bx bx-target-lock','brief'=>'Planning problems that require trade-offs and justification.','why'=>'<strong>Why this matters:</strong> Trains high-level planning, trade-off evaluation, and metacognition.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Prioritisation, scenario planning</li><li><strong>Social:</strong> Negotiation, fairness</li></ul>'),
                    array('id'=>'diagnosis','title'=>'Diagnosis / Troubleshooting','icon'=>'bx bx-search-alt','brief'=>'Evidence-based identification of root causes and fixes.','why'=>'<strong>Why this matters:</strong> Encourages hypothesis testing and structured inference under uncertainty.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Hypothesis testing, evidence evaluation</li><li><strong>Behavioral:</strong> Methodical inquiry</li></ul>'),
                    array('id'=>'design','title'=>'Design / Synthesis','icon'=>'bx bx-brush','brief'=>'Create a useful object or system by integrating needs and constraints.','why'=>'<strong>Why this matters:</strong> Encourages generative thinking and functional creativity.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Integration, abstraction</li><li><strong>Behavioral:</strong> Prototyping, documentation</li></ul>'),
                    array('id'=>'transfer','title'=>'Transfer / Analogical','icon'=>'bx bx-transfer-alt','brief'=>'Apply principles learned in one situation to a different context.','why'=>'<strong>Why this matters:</strong> Strengthens flexible thinking and problem transfer abilities.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Analogy, abstraction</li><li><strong>Behavioral:</strong> Adaptability</li></ul>'),
                    array('id'=>'exploratory','title'=>'Exploratory / Discovery','icon'=>'bx bx-search','brief'=>'Open-ended inquiry to find patterns and form testable hypotheses.','why'=>'<strong>Why this matters:</strong> Builds curiosity, pattern recognition, and hypothesis-driven inquiry.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Inductive reasoning, pattern discovery</li><li><strong>Behavioral:</strong> Curiosity, experimentation</li></ul>'),
                    array('id'=>'quantum','title'=>'Quantum-Conceptual Reasoning','icon'=>'bx bx-atom','brief'=>'Decisions under overlapping preferences and probabilistic outcomes.','why'=>'<strong>Why this matters:</strong> Trains comfort with ambiguity and probabilistic reasoning — useful for complex decisions.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Probability thinking, scenario evaluation</li><li><strong>Behavioral:</strong> Ambiguity tolerance</li></ul>'),
                    array('id'=>'indic','title'=>'Indic Philosophical Cognitive','icon'=>'bx bx-leaf','brief'=>'Multi-perspective reasoning and reflective argumentation using Indic logic traditions.','why'=>'<strong>Why this matters:</strong> Encourages multi-perspective thinking, reflective clarity, and ethical framing.','skills'=>'<ul class="text-left"><li><strong>Cognitive:</strong> Multi-perspective analysis, structured argument</li><li><strong>Behavioral:</strong> Empathy, balanced judgment</li></ul>')
                );

                foreach ($problemTypes as $pt) :
                    $title_attr = htmlspecialchars($pt['title'], ENT_QUOTES, 'UTF-8');
                    $brief_text = htmlspecialchars($pt['brief'], ENT_QUOTES, 'UTF-8');
                    $why_attr = htmlspecialchars($pt['why'], ENT_QUOTES, 'UTF-8');
                    $skills_attr = htmlspecialchars($pt['skills'], ENT_QUOTES, 'UTF-8');
                ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="card h-100 shadow-sm" role="article" aria-labelledby="card-<?php echo $pt['id']; ?>">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3" aria-hidden="true">
                                        <i class="<?php echo $pt['icon']; ?> bx-lg text-primary"></i>
                                    </div>
                                    <div>
                                        <h2 id="card-<?php echo $pt['id']; ?>" class="card-title h6 mb-0"><?php echo $title_attr; ?></h2>
                                        <small class="text-muted" id="brief-<?php echo $pt['id']; ?>"><?php echo $brief_text; ?></small>
                                    </div>
                                </div>
                                <p class="card-text text-muted small" style="min-height:44px;"><?php echo $brief_text; ?></p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm btn-view"
                                            aria-haspopup="dialog"
                                            aria-controls="modal-<?php echo $pt['id']; ?>"
                                            data-title="<?php echo $title_attr; ?>"
                                            data-why="<?php echo $why_attr; ?>"
                                            data-skills="<?php echo $skills_attr; ?>">
                                        View
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-primary btn-preview"
                                            aria-haspopup="dialog"
                                            aria-controls="modal-preview-<?php echo $pt['id']; ?>"
                                            data-type-slug="<?php echo $pt['id']; ?>"
                                            data-title="<?php echo $title_attr; ?>">
                                        Preview
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <small class="text-muted">Tip: Use <strong>View</strong> to read the rationale. Use <strong>Preview</strong> to try age-scaled practice. Use the audio control inside preview to listen to the content.</small>
                </div>
            </div>

        </div>

        <?php require_once('../platformFooter.php'); ?>

    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Embedded canonical problems JSON -->
<script type="text/javascript">
window.PROBLEMS_JSON = [
  {"problem_id":"authentic-kid","type_id":"authentic","type_title":"Authentic Problem","level":"kid","statement":"The park near your home is messy after playtime. Think of 2 simple things you and friends can do to keep it clean.","how_to":"1) Ask friends what makes it messy. 2) Decide 2 small actions (e.g., put trash in bins). 3) Try the actions for one weekend.","expected_outcome":"A short plan to keep the park tidy that you can try with friends."},
  {"problem_id":"authentic-teen","type_id":"authentic","type_title":"Authentic Problem","level":"teen","statement":"Your community park gets littered after weekends. Design a one-week trial to reduce litter involving neighbours and signs.","how_to":"1) Observe hotspots. 2) Propose 3 interventions (bins, signs, volunteer shifts). 3) Get neighbour feedback and run a trial.","expected_outcome":"A tested mini-plan with observations and measurable results."},
  {"problem_id":"authentic-adult","type_id":"authentic","type_title":"Authentic Problem","level":"adult","statement":"The local park sees recurring littering after weekend events. Draft a staged community-led cleanup & prevention plan with simple metrics.","how_to":"1) Map high-traffic zones and stakeholders. 2) Prioritise interventions by impact/cost. 3) Define KPIs and a 1-week pilot with responsibilities.","expected_outcome":"A pragmatic pilot plan with roles, KPIs, and follow-up actions."},

  {"problem_id":"procedural-kid","type_id":"procedural","type_title":"Procedural Problem","level":"kid","statement":"Make lemonade by following and fixing a set of steps. Some steps are mixed up — put them in the right order.","how_to":"1) Read all steps. 2) Reorder: wash hands → squeeze lemons → add sugar → mix → serve. 3) Make lemonade and taste.","expected_outcome":"A correct recipe sequence you can follow to make lemonade."},
  {"problem_id":"procedural-teen","type_id":"procedural","type_title":"Procedural Problem","level":"teen","statement":"You have instructions to assemble a school project kit; steps may be missing or swapped. Correct them and assemble.","how_to":"1) Scan steps and identify missing safety steps. 2) Reorder logically. 3) Assemble and test the kit.","expected_outcome":"A properly assembled kit and a checklist for repeatable assembly."},
  {"problem_id":"procedural-adult","type_id":"procedural","type_title":"Procedural Problem","level":"adult","statement":"You must reconcile a short checklist of monthly household bills and spot errors in the payment sequence.","how_to":"1) Review line items and dates. 2) Flag duplicates/timing issues and correct entries. 3) Verify totals and create a repeatable payment checklist.","expected_outcome":"An accurate reconciliation and corrected payment sequence."},

  {"problem_id":"strategic-kid","type_id":"strategic","type_title":"Strategic Problem","level":"kid","statement":"There is one shared tablet. Make a fair plan so everyone gets time to play and learn.","how_to":"1) List who needs it. 2) Set short time slots. 3) Create a rotation and test for a day.","expected_outcome":"A simple rotation plan that everyone understands."},
  {"problem_id":"strategic-teen","type_id":"strategic","type_title":"Strategic Problem","level":"teen","statement":"Create a weekly schedule for a shared laptop among siblings with different study and gaming needs.","how_to":"1) Survey needs and prime times. 2) Allocate slots and set backup rules. 3) Communicate the schedule and test it.","expected_outcome":"A transparent weekly schedule with conflict-resolution rules."},
  {"problem_id":"strategic-adult","type_id":"strategic","type_title":"Strategic Problem","level":"adult","statement":"Design a fair usage policy for a shared office resource (e.g., conference room).","how_to":"1) Map stakeholder needs. 2) Define booking rules, time limits and priority criteria. 3) Create escalation and audit flow.","expected_outcome":"A defensible resource policy with clear rules and escalation steps."},

  {"problem_id":"diagnosis-kid","type_id":"diagnosis","type_title":"Diagnosis / Troubleshooting","level":"kid","statement":"A flashlight won’t turn on. Check simple, safe things to find the problem.","how_to":"1) Try new batteries. 2) Check the switch and bulb. 3) Ask an adult if still broken.","expected_outcome":"A safe checklist and likely fix or next-step to ask an adult."},
  {"problem_id":"diagnosis-teen","type_id":"diagnosis","type_title":"Diagnosis / Troubleshooting","level":"teen","statement":"A phone won’t charge. Run a stepwise checklist to identify why (cable, port, charger, battery).","how_to":"1) Try alternate cable/charger. 2) Clean the port and test another device. 3) Check battery health settings.","expected_outcome":"A diagnostic note indicating likely cause and recommended next steps."},
  {"problem_id":"diagnosis-adult","type_id":"diagnosis","type_title":"Diagnosis / Troubleshooting","level":"adult","statement":"A small kitchen appliance stops working. Use a stepwise diagnostic approach to identify electrical, mechanical, or user issues.","how_to":"1) Check power source and cable. 2) Inspect for visible damage. 3) Follow manual troubleshooting and decide repair/replace.","expected_outcome":"A reasoned diagnostic summary and recommended corrective action."},

  {"problem_id":"design-kid","type_id":"design","type_title":"Design / Synthesis","level":"kid","statement":"Design a backpack that makes carrying books easier — draw a simple picture with pockets.","how_to":"1) Think of what must fit. 2) Draw pockets and straps. 3) Explain why each pocket helps.","expected_outcome":"A drawing and quick explanation showing useful features."},
  {"problem_id":"design-teen","type_id":"design","type_title":"Design / Synthesis","level":"teen","statement":"Sketch a study desk layout that helps you organize books, devices, and notes.","how_to":"1) List needs (lighting, cable routing). 2) Sketch compartments and ergonomics. 3) Annotate layout with usage notes.","expected_outcome":"A functional desk sketch with rationale for each element."},
  {"problem_id":"design-adult","type_id":"design","type_title":"Design / Synthesis","level":"adult","statement":"Design a simple weekly meal prep system that reduces daily cooking time and waste.","how_to":"1) Identify constraints (time, storage). 2) Propose batch-cooking and storage plan. 3) Provide shopping list and schedule.","expected_outcome":"A practical meal-prep plan that reduces time and food waste."},

  {"problem_id":"transfer-kid","type_id":"transfer","type_title":"Transfer / Analogical","level":"kid","statement":"You learned to tie shoelaces. Explain how the same steps help tying a ribbon on a gift.","how_to":"1) Identify loop and pull steps. 2) Show how they map to the ribbon task. 3) Demonstrate.","expected_outcome":"A short explanation and demonstration showing principle transfer."},
  {"problem_id":"transfer-teen","type_id":"transfer","type_title":"Transfer / Analogical","level":"teen","statement":"You learned to prepare simple pasta. Describe how those timing and prep rules apply to a stir-fry.","how_to":"1) Map prep-order (chop → heat → cook). 2) Adapt timing and heat. 3) Prepare and taste, note differences.","expected_outcome":"A transfer plan showing how cooking principles apply across dishes."},
  {"problem_id":"transfer-adult","type_id":"transfer","type_title":"Transfer / Analogical","level":"adult","statement":"Having run a successful local fundraiser, design an outreach plan that uses the same principles for a different cause.","how_to":"1) Identify core principles (targeting, simple ask). 2) Adapt messaging and channels. 3) Propose rollout and metrics.","expected_outcome":"A transferrable outreach plan with measurable indicators."},

  {"problem_id":"exploratory-kid","type_id":"exploratory","type_title":"Exploratory / Discovery","level":"kid","statement":"Open a small toy box. Sort toys into two groups and say why they might be together.","how_to":"1) Observe items. 2) Group by color or use. 3) Explain and suggest a test.","expected_outcome":"A simple grouping and a short idea about why those toys are together."},
  {"problem_id":"exploratory-teen","type_id":"exploratory","type_title":"Exploratory / Discovery","level":"teen","statement":"Given a small set of family photos, group them by theme and form a hypothesis about a past event.","how_to":"1) Find patterns (locations/people). 2) Propose a hypothesis. 3) List evidence or questions to validate it.","expected_outcome":"A reasoned hypothesis and suggested tests/questions."},
  {"problem_id":"exploratory-adult","type_id":"exploratory","type_title":"Exploratory / Discovery","level":"adult","statement":"Explore a recent set of monthly bills to find spending patterns and suggest one saving hypothesis to test next month.","how_to":"1) Categorize spending. 2) Identify spikes or trends. 3) Propose an experiment (e.g., reduce dining out).","expected_outcome":"An exploratory finding, a testable hypothesis, and a plan to validate it."},

  {"problem_id":"quantum-kid","type_id":"quantum","type_title":"Quantum-Conceptual Reasoning","level":"kid","statement":"Your family can pick 1 movie. Each person says 2 favs. Find the movie that most people like.","how_to":"1) List everyone’s choices. 2) Count overlaps. 3) Pick the movie with the most votes.","expected_outcome":"A simple voting result that aims to satisfy most people."},
  {"problem_id":"quantum-teen","type_id":"quantum","type_title":"Quantum-Conceptual Reasoning","level":"teen","statement":"Each friend suggests two song playlists for a trip. Choose the playlist that probably satisfies most people using overlaps.","how_to":"1) Map each friend’s preferences. 2) Score overlaps. 3) Pick the top playlist or combine tracks, noting trade-offs.","expected_outcome":"A probabilistic selection with reasoning about overlaps and compromise."},
  {"problem_id":"quantum-adult","type_id":"quantum","type_title":"Quantum-Conceptual Reasoning","level":"adult","statement":"Allocate a small entertainment budget across three weekend activities with overlapping appeal; choose allocation to maximize combined satisfaction under uncertainty.","how_to":"1) Estimate probabilities activity is preferred by subsets. 2) Compute expected satisfaction for allocations. 3) Allocate proportionally with safety buffer and monitoring triggers.","expected_outcome":"A reasoned allocation plan handling overlapping preferences and uncertainty."},

  {"problem_id":"indic-kid","type_id":"indic","type_title":"Indic Philosophical Cognitive","level":"kid","statement":"Two friends argue about whether playtime should be longer. Tell both sides and suggest a fair middle solution.","how_to":"1) Explain each friend’s reason. 2) Show how both can be partly right. 3) Propose a small compromise (extra 10 mins when homework is done).","expected_outcome":"A kind, balanced recommendation."},
  {"problem_id":"indic-teen","type_id":"indic","type_title":"Indic Philosophical Cognitive","level":"teen","statement":"Two classmates disagree about group project deadlines. Present both positions and recommend a balanced plan using multi-view thinking.","how_to":"1) List arguments for each side. 2) Identify valid points. 3) Propose staged deadlines or opt-in flexibility.","expected_outcome":"A reconciled plan that respects both needs and includes clear trade-offs."},
  {"problem_id":"indic-adult","type_id":"indic","type_title":"Indic Philosophical Cognitive","level":"adult","statement":"Using multi-perspective analysis, evaluate a local policy (e.g., closing a street for weekend markets) and recommend a compromise.","how_to":"1) Describe stakeholders and arguments. 2) Present evidence for each side. 3) Propose a pilot compromise with evaluation metrics.","expected_outcome":"A structured recommendation that balances perspectives and proposes evaluation metrics."}
];
</script>

<!-- Fixed Preview/View wiring using window.PROBLEMS_JSON -->
<script type="text/javascript">
(function () {
    function findProblem(typeId, level) {
        var arr = window.PROBLEMS_JSON || [];
        for (var i = 0; i < arr.length; i++) {
            if (arr[i].type_id === typeId && arr[i].level === level) return arr[i];
        }
        return null;
    }

    function buildViewHtml(whyHtml, skillsHtml) {
        return '<div style="text-align:left; margin-top:6px;">' +
            '<div class="mb-2">' + whyHtml + '</div>' +
            '<div><strong>Core cognitive & behavioural skills you will build:</strong></div>' +
            '<div class="mt-1">' + skillsHtml + '</div>' +
            '</div>';
    }

    function buildLevelHtml(label, statement, howTo, outcome) {
        return '<div class="level-block" data-level="' + label.toLowerCase() + '">' +
            '<div><strong>Problem Statement (' + label + ')</strong><div style="margin-top:6px;">' + statement + '</div></div>' +
            '<div class="mt-2"><strong>How to approach (brief)</strong><div style="margin-top:6px;">' + howTo + '</div></div>' +
            '<div class="mt-2"><strong>Expected outcome</strong><div style="margin-top:6px;">' + outcome + '</div></div>' +
            '</div>';
    }

    function buildPreviewHtml(title, kid, teen, adult) {
        // Insert all three blocks so tabs can toggle visibility
        var allLevelsHtml = buildLevelHtml('Kid', kid.statement || '', kid.how_to || kid.how || kid.howTo || '', kid.expected_outcome || kid.outcome || '');
        allLevelsHtml += buildLevelHtml('Teen', teen.statement || '', teen.how_to || teen.how || teen.howTo || '', teen.expected_outcome || teen.outcome || '');
        allLevelsHtml += buildLevelHtml('Adult', adult.statement || '', adult.how_to || adult.how || adult.howTo || '', adult.expected_outcome || adult.outcome || '');

        var html = '<div style="text-align:left; margin-top:6px;">' +
            '<div role="tablist" aria-label="Difficulty levels" class="mb-2">' +
            '  <button class="diff-tab btn btn-sm btn-outline-secondary me-1" data-level="kid" aria-selected="true">Kid</button>' +
            '  <button class="diff-tab btn btn-sm btn-outline-secondary me-1" data-level="teen" aria-selected="false">Teen</button>' +
            '  <button class="diff-tab btn btn-sm btn-outline-secondary" data-level="adult" aria-selected="false">Adult</button>' +
            '</div>' +
            '<div id="preview-content" style="margin-top:8px;">' +
            allLevelsHtml +
            '</div>' +
            '<div class="mt-3 d-flex align-items-center justify-content-between">' +
            '  <div>' +
            '    <button id="tts-play" class="btn btn-sm btn-outline-primary" aria-label="Play preview audio">🔊 Listen</button>' +
            '    <button id="tts-stop" class="btn btn-sm btn-outline-danger" aria-label="Stop preview audio">⏹ Stop</button>' +
            '  </div>' +
            '  <div>' +
			
            '    <button id="start-exercise" class="btn btn-sm btn-primary me-2">Start Exercise</button>' +
            '    <button id="close-preview" class="btn btn-sm btn-outline-secondary">Close</button>' +
            '  </div>' +
            '</div>' +
            '</div>';
        return html;
    }

    // View handlers
    var viewButtons = document.getElementsByClassName('btn-view');
    for (var i = 0; i < viewButtons.length; i++) {
        (function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var title = btn.getAttribute('data-title') || 'Detail';
                var why = btn.getAttribute('data-why') || '';
                var skills = btn.getAttribute('data-skills') || '';
                var html = buildViewHtml(why, skills);
                Swal.fire({
                    title: title,
                    html: html,
                    icon: 'info',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Explore',
                    cancelButtonText: 'OK',
                    customClass: { popup: 'swal2-border-radius' }
                }).then(function (result) {
                    if (result.isConfirmed) window.location.href = '#';
                });
            }, false);
        })(viewButtons[i]);
    }

    // Preview handlers
    var previewButtons = document.getElementsByClassName('btn-preview');
    for (var j = 0; j < previewButtons.length; j++) {
        (function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var typeSlug = btn.getAttribute('data-type-slug');
                var title = btn.getAttribute('data-title') || 'Preview';

                var kid = findProblem(typeSlug, 'kid') || {};
                var teen = findProblem(typeSlug, 'teen') || {};
                var adult = findProblem(typeSlug, 'adult') || {};

                // store current preview context globally so start-exercise can use it
                window.currentPreview = {
                    type_slug: typeSlug,
                    title: title,
                    content: { kid: kid, teen: teen, adult: adult }
                };

                var html = buildPreviewHtml(title, kid, teen, adult);

                Swal.fire({
                    title: title + ' — Preview',
                    html: html,
                    width: 820,
                    icon: 'question',
                    showConfirmButton: false,
                    showCloseButton: false,
                    customClass: { popup: 'swal2-border-radius' }
                });

                setTimeout(function () {
                    var tabs = document.getElementsByClassName('diff-tab');
                    function selectLevel(level) {
                        var content = document.getElementById('preview-content');
                        if (!content) return;
                        var blocks = content.getElementsByClassName('level-block');
                        for (var k = 0; k < blocks.length; k++) {
                            var bl = blocks[k];
                            bl.style.display = (bl.getAttribute('data-level') === level) ? 'block' : 'none';
                        }
                        for (var t = 0; t < tabs.length; t++) {
                            tabs[t].setAttribute('aria-selected', tabs[t].getAttribute('data-level') === level ? 'true' : 'false');
                        }
                    }
                    // default to kid when opening preview
                    selectLevel('kid');

                    for (var t = 0; t < tabs.length; t++) {
                        (function (tab) {
                            tab.addEventListener('click', function (ev) {
                                ev.preventDefault();
                                selectLevel(tab.getAttribute('data-level'));
                            }, false);
                        })(tabs[t]);
                    }

                    // TTS
                    var ttsPlay = document.getElementById('tts-play');
                    var ttsStop = document.getElementById('tts-stop');
                    var synth = window.speechSynthesis;
                    var currentUtterance = null;

                    function speakTextOfCurrentLevel() {
                        if (!synth) return;
                        var content = document.getElementById('preview-content');
                        var blocks = content.getElementsByClassName('level-block');
                        var text = '';
                        for (var b = 0; b < blocks.length; b++) {
                            var bl = blocks[b];
                            if (bl.style.display !== 'none') {
                                text = bl.innerText || bl.textContent;
                                break;
                            }
                        }
                        if (text === '') return;
                        synth.cancel();
                        currentUtterance = new SpeechSynthesisUtterance(text);
                        currentUtterance.rate = 1.0;
                        synth.speak(currentUtterance);
                    }

                    if (ttsPlay) ttsPlay.addEventListener('click', function (ev) { ev.preventDefault(); speakTextOfCurrentLevel(); }, false);
                    if (ttsStop) ttsStop.addEventListener('click', function (ev) { ev.preventDefault(); if (synth) synth.cancel(); }, false);

                    // Start Exercise handler (ALWAYS use DOB-based level selection on server)
var startBtn = document.getElementById('start-exercise');
if (startBtn) {
    startBtn.addEventListener('click', function (ev) {
        ev.preventDefault();

        // Always rely on server-side logic to determine
        // kid/teen/adult based on learner DOB.
        // Do NOT send the selected tab level.
        // Do NOT check any "use-age-level" checkbox.

        var target = 'problem_solving.php?type=' + encodeURIComponent(window.currentPreview.type_slug);

        // Close modal and redirect immediately
        Swal.close();
        window.location.href = target;

    }, false);
}


                    var closeBtn = document.getElementById('close-preview');
                    if (closeBtn) closeBtn.addEventListener('click', function (ev) { ev.preventDefault(); Swal.close(); }, false);

                }, 120);
            }, false);
        })(previewButtons[j]);
    }

    // findProblem helper
    function findProblem(typeId, level) {
        var arr = window.PROBLEMS_JSON || [];
        for (var i = 0; i < arr.length; i++) {
            if (arr[i].type_id === typeId && arr[i].level === level) return arr[i];
        }
        return null;
    }
})();
</script>

<style type="text/css">
    .card .bx-lg { font-size: 28px; }
    .swal2-border-radius { border-radius: 12px; }
    .show-focus :focus { outline: 3px solid #0d6efd; outline-offset: 2px; }
    .diff-tab[aria-selected="true"] { background-color: #0d6efd; color: #fff; border-color: #0d6efd; }
    .diff-tab[aria-selected="false"] { background-color: #fff; color: #000; }
    .level-block { display: none; }
    @media (max-width: 575px) { .card .card-body { padding: 12px; } }
    .card, .card .card-text, .card small { color: #111; }
</style>
