<?php
/**
 * Astraal – Learner Learning Intent
 * Read-only + Revise Flow | Sneat UI
 * PHP 5.x Compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$learner_id = (int) $_SESSION['phx_user_id'];
$user_role  = isset($_SESSION['phx_user_role']) ? $_SESSION['phx_user_role'] : 'learner';

$page = "learningPath";
require_once('learnerHead_Nav2.php');

/* ---------------- FETCH ACTIVE INTENT ---------------- */
$intent_sql = "
SELECT *
FROM learner_learning_intent
WHERE learner_id = {$learner_id}
  AND intent_status = 'active'
LIMIT 1
";
$intent_res = mysqli_query($coni, $intent_sql);
$active_intent = mysqli_fetch_assoc($intent_res);

$has_intent = ($active_intent && isset($active_intent['intent_id']));

function explode_safe($val) {
    return $val ? explode('|', $val) : array();
}
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="d-flex justify-content-end">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="learning-path.php">Learning Path</a></li>
    <li class="breadcrumb-item active">Learning Intent</li>
  </ol>
</nav>



<?php
/* ================= READ-ONLY MODE ================= */
if ($has_intent && !isset($_GET['revise'])):
?>

<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <h4 class="fw-bold">
      <i class="fa fa-compass fs-4 text-primary me-2"></i>
      Learning Intent Declared
    </h4>
    <p class="text-muted mb-0">
      This declaration helps us guide learning. It does not assess skills or lock paths.
    </p>
  </div>
</div>


<div class="card mb-4 border border-success">
  <div class="card-body">

    <h5 class="mb-2">
      <i class="fa fa-check-circle text-success me-2"></i>
      Active Learning Intent
    </h5>

    <p class="text-muted mb-3">
      <i class="fa fa-calendar-alt me-1"></i>
      Declared on:
      <?php if (!empty($active_intent['created_at'])): ?>
        <strong><?= date('d M Y', strtotime($active_intent['created_at'])) ?></strong>
      <?php else: ?>
        <em class="text-muted">Date not available</em>
      <?php endif; ?>
    </p>

    <div class="row">

      <div class="col-md-4 mb-3">
        <strong>
          <i class="fa fa-bullseye me-1 text-primary"></i>
          Focus Area
        </strong>
        <p class="text-muted mb-1">
          <?php echo htmlspecialchars($active_intent['reference_axis']); ?>
        </p>
        <?php if ($active_intent['reference_label']): ?>
          <small class="text-muted">
            "<?php echo htmlspecialchars($active_intent['reference_label']); ?>"
          </small>
        <?php endif; ?>
      </div>

      <div class="col-md-4 mb-3">
        <strong>
          <i class="fa fa-signal me-1 text-secondary"></i>
          Current State
        </strong>
        <ul class="mb-0">
          <?php foreach (explode_safe($active_intent['context_stage']) as $v): ?>
            <li><?php echo htmlspecialchars($v); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="col-md-4 mb-3">
        <strong>
          <i class="fa fa-fire me-1 text-danger"></i>
          Motivation
        </strong>
        <ul class="mb-0">
          <?php foreach (explode_safe($active_intent['motivation_reason']) as $v): ?>
            <li><?php echo htmlspecialchars($v); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="col-md-4 mb-3">
        <strong>
          <i class="fa fa-compass me-1 text-success"></i>
          Learning Direction
        </strong>
        <ul class="mb-0">
          <?php foreach (explode_safe($active_intent['intent_direction']) as $v): ?>
            <li><?php echo htmlspecialchars($v); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="col-md-4 mb-3">
        <strong>
          <i class="fa fa-ban me-1 text-warning"></i>
          Constraints
        </strong>
        <ul class="mb-0">
          <?php foreach (explode_safe($active_intent['constraints']) as $v): ?>
            <li><?php echo htmlspecialchars($v); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="col-md-4 mb-3">
        <strong>
          <i class="fa fa-robot me-1 text-info"></i>
          AI Preference
        </strong>
        <ul class="mb-0">
          <?php foreach (explode_safe($active_intent['ai_comfort']) as $v): ?>
            <li><?php echo htmlspecialchars($v); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>

    <div class="alert alert-info mt-3">
      <i class="fa fa-info-circle me-2"></i>
      This intent is used by the system and instructors for guidance only.
    </div>

    <?php if ($user_role === 'learner'): ?>
      <div class="d-flex justify-content-end">
        <a href="?revise=1" class="btn btn-warning">
          <i class="fa fa-pen me-1"></i>
          Revise Learning Intent
        </a>
      </div>
    <?php endif; ?>

  </div>
</div>


<?php
/* STOP rendering editable form */
require_once('../platformFooter.php');
exit;
endif;
?>



<!-- ================= EDIT MODE ================= -->
<form method="post" action="save-learning-intent.php">

<input type="hidden" name="revise_flag" value="<?php echo $has_intent ? '1' : '0'; ?>">

<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <h4 class="fw-bold">
      <i class="fa fa-compass fs-4 text-primary me-2"></i>
      Declare Your Learning Intent
    </h4>
    <p class="text-muted mb-0">
      Share your current context so we can guide you better.
      This does <strong>not</strong> assess skills or lock your learning path.
    </p>
  </div>
</div>

<!-- Explanation Accordion -->
<div class="accordion mb-4" id="learningIntentHelp">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button bg-label-info collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#intentHelpBody">
        <i class="fa fa-cogs fs-4 text-primary me-2"></i>
        What is Learning Intent and why are we asking this?
      </button>
    </h2>
    <div id="intentHelpBody" class="accordion-collapse collapse">
      <div class="accordion-body">
        <p>
          Learning Intent helps us understand <strong>what you are focusing on</strong>,
          <strong>why it matters</strong>, and <strong>how to guide you effectively</strong>.
        </p>
        <ul>
          <li>This does <strong>not</strong> evaluate your skill level</li>
          <li>This does <strong>not</strong> lock your learning path</li>
          <li>You can revise this anytime as your direction evolves</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<form method="post" action="save-learning-intent.php">


<!-- ================= REFERENCE AXIS ================= -->
<div class="row mb-4">

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="fa fa-crosshairs fs-4 text-primary me-2"></i>
          What are you currently thinking about learning?
        </h5>
      </div>

      <div class="card-body">
        <p class="text-muted mb-4">
          This helps us understand what your answers below are referring to.
          You can refine this later.
        </p>

        <!-- ROW 1 -->
        <div class="row mb-3">

          <!-- LEFT COLUMN : Topic / Domain + Skill -->
          <div class="col-md-6">

            <div class="form-check mb-3">
              <input class="form-check-input" type="radio"
                     name="reference_axis" value="topic" required>
              <label class="form-check-label">
                <strong>A topic or domain</strong><br>
                <small class="text-muted">
                  e.g., AI, Cybersecurity, Finance, Leadership
                </small>
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="radio"
                     name="reference_axis" value="skill">
              <label class="form-check-label">
                <strong>A specific skill</strong><br>
                <small class="text-muted">
                  e.g., Python, data analysis, cloud security
                </small>
              </label>
            </div>

          </div>

          <!-- RIGHT COLUMN : Job Role + Exploration -->
          <div class="col-md-6">

            <div class="form-check mb-3">
              <input class="form-check-input" type="radio"
                     name="reference_axis" value="role">
              <label class="form-check-label">
                <strong>A job role or responsibility</strong><br>
                <small class="text-muted">
                  e.g., Data Analyst, Product Manager, SOC Engineer
                </small>
              </label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="radio"
                     name="reference_axis" value="exploration">
              <label class="form-check-label">
                <strong>I am still exploring</strong><br>
                <small class="text-muted">
                  I don’t have a clear focus yet
                </small>
              </label>
            </div>

          </div>

        </div>

        <!-- ROW 2 : FULL WIDTH LABEL -->
        <div class="row">
          <div class="col-12">

            <label class="form-label fw-semibold">
              <i class="fa fa-tag me-1 text-secondary"></i>
              If you wish, name it (optional)
            </label>

            <input type="text"
                   class="form-control mb-2"
                   name="reference_label"
                   placeholder="e.g., AI fundamentals, Python for data analysis, SOC analyst role">

            <small class="text-muted">
              This is only for clarity. You can leave it blank or change it later.
            </small>

          </div>
        </div>

      </div>
    </div>
  </div>

</div>
<!-- =============== END REFERENCE AXIS ================= -->

<div class="row">

<!-- LEFT COLUMN -->
<div class="col-md-6">

<!-- Current State -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">
      <i class="fa fa-user-check fs-4 text-success me-2"></i>
      Where are you right now with respect to this focus?
    </h5>
  </div>
  <div class="card-body">

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="context_stage[]" value="starting_fresh">
      <label class="form-check-label">
        <strong>Starting fresh</strong><br>
        <small class="text-muted">Little or no prior exposure</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="context_stage[]" value="basic_knowledge">
      <label class="form-check-label">
        <strong>Basic familiarity</strong><br>
        <small class="text-muted">Know fundamentals but lack confidence</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="context_stage[]" value="application_gap">
      <label class="form-check-label">
        <strong>Application gap</strong><br>
        <small class="text-muted">Know concepts but struggle to apply</small>
      </label>
    </div>

    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="context_stage[]" value="need_structure">
      <label class="form-check-label">
        <strong>Need structure</strong><br>
        <small class="text-muted">Require clearer guidance</small>
      </label>
    </div>

  </div>
</div>

<!-- Motivation -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">
      <i class="fa fa-heart fs-4 text-danger me-2"></i>
      Why does this matter to you now?
    </h5>
  </div>
  <div class="card-body">

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="motivation_reason[]" value="career_growth">
      <label class="form-check-label">
        <strong>Career growth</strong><br>
        <small class="text-muted">Important for advancement or opportunities</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="motivation_reason[]" value="confidence">
      <label class="form-check-label">
        <strong>Build confidence</strong><br>
        <small class="text-muted">Want to feel capable and self-assured</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="motivation_reason[]" value="urgency">
      <label class="form-check-label">
        <strong>Immediate pressure</strong><br>
        <small class="text-muted">Deadline or external expectation exists</small>
      </label>
    </div>

    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="motivation_reason[]" value="long_term">
      <label class="form-check-label">
        <strong>Long-term planning</strong><br>
        <small class="text-muted">Preparing for future responsibilities</small>
      </label>
    </div>

  </div>
</div>


<!-- Cognitive Learning Approach -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">
      <i class="fa fa-brain fs-4 text-info me-2"></i>
      How do you prefer to approach learning?
    </h5>
  </div>
  <div class="card-body">

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox"
             name="cognitive_preference[]" value="guided_first">
      <label class="form-check-label">
        <strong>Guided first</strong><br>
        <small class="text-muted">
          I prefer clear guidance before exploring on my own
        </small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox"
             name="cognitive_preference[]" value="explore_first">
      <label class="form-check-label">
        <strong>Explore first</strong><br>
        <small class="text-muted">
          I like experimenting before structured instruction
        </small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox"
             name="cognitive_preference[]" value="examples_first">
      <label class="form-check-label">
        <strong>Examples & cases</strong><br>
        <small class="text-muted">
          Real-world examples help me understand faster
        </small>
      </label>
    </div>

    <div class="form-check">
      <input class="form-check-input" type="checkbox"
             name="cognitive_preference[]" value="concepts_first">
      <label class="form-check-label">
        <strong>Concepts first</strong><br>
        <small class="text-muted">
          I like understanding theory before practice
        </small>
      </label>
    </div>

  </div>
</div>

</div>

<!-- RIGHT COLUMN -->
<div class="col-md-6">

<!-- Direction -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">
      <i class="fa fa-anchor fs-4 text-primary me-2"></i>
      What are you moving towards?
    </h5>
  </div>
  <div class="card-body">

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="intent_direction[]" value="skill_building">
      <label class="form-check-label">
        <strong>Skill building</strong><br>
        <small class="text-muted">Developing new or deeper capabilities</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="intent_direction[]" value="career_growth">
      <label class="form-check-label">
        <strong>Career advancement</strong><br>
        <small class="text-muted">Preparing for higher responsibility</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="intent_direction[]" value="career_switch">
      <label class="form-check-label">
        <strong>Career transition</strong><br>
        <small class="text-muted">Moving into a different role or domain</small>
      </label>
    </div>

    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="intent_direction[]" value="exploration">
      <label class="form-check-label">
        <strong>Exploration</strong><br>
        <small class="text-muted">Understanding options before committing</small>
      </label>
    </div>

  </div>
</div>

<!-- Constraints -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">
      <i class="fa fa-clock fs-4 text-warning me-2"></i>
      What constraints should we respect?
    </h5>
  </div>
  <div class="card-body">

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="constraints[]" value="limited_time">
      <label class="form-check-label">
        <strong>Limited time</strong><br>
        <small class="text-muted">Availability is restricted</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="constraints[]" value="slow_pace">
      <label class="form-check-label">
        <strong>Slow & steady</strong><br>
        <small class="text-muted">Prefer manageable progress</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="constraints[]" value="intensive">
      <label class="form-check-label">
        <strong>Intensive bursts</strong><br>
        <small class="text-muted">Can commit deeply for short periods</small>
      </label>
    </div>

    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="constraints[]" value="self_guided">
      <label class="form-check-label">
        <strong>Self-guided only</strong><br>
        <small class="text-muted">Prefer minimal external intervention</small>
      </label>
    </div>

  </div>
</div>

<!-- AI Comfort -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="card-title mb-0">
      <i class="fa fa-robot fs-4 text-secondary me-2"></i>
      Comfort with AI-assisted learning
    </h5>
  </div>
  <div class="card-body">

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="ai_comfort[]" value="ai_ok">
      <label class="form-check-label">
        <strong>Comfortable with AI</strong><br>
        <small class="text-muted">AI guidance is helpful</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="ai_comfort[]" value="ai_support">
      <label class="form-check-label">
        <strong>AI as support</strong><br>
        <small class="text-muted">AI assists, not decides</small>
      </label>
    </div>

    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" name="ai_comfort[]" value="human_first">
      <label class="form-check-label">
        <strong>Human-first</strong><br>
        <small class="text-muted">Prefer human guidance</small>
      </label>
    </div>

    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="ai_comfort[]" value="minimal_ai">
      <label class="form-check-label">
        <strong>Minimal AI</strong><br>
        <small class="text-muted">Very limited AI involvement</small>
      </label>
    </div>

  </div>
</div>

</div>
</div>

<!-- Optional Context -->
<div class="card mb-4">
  <div class="card-body">
    <label class="form-label fw-semibold">
      <i class="fa fa-comment-dots fs-4 me-2"></i>
      Anything else we should know?
    </label>
    <textarea class="form-control" name="context_notes" rows="3"
      placeholder="Optional context that may help us guide you better"></textarea>
  </div>
</div>

<!-- END NOTE -->
<div class="alert alert-info mb-4">
  <i class="fa fa-info-circle-0 fs-4  me-2"></i>
  Your responses are used to personalize guidance across learning, skills, readiness,
  and long-term growth. You can update this anytime as your intent evolves.
</div>

<!-- Submit -->
<div class="d-flex justify-content-end">
  <button type="submit" class="btn btn-primary">
    <i class="fa fa-circle-check me-1"></i>
    Save Learning Intent
  </button>
</div>

</form>

</div>


<?php require_once('../platformFooter.php'); ?>


































































