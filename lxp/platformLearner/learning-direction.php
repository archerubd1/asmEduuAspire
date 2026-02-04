<?php
/**
 * Astraal – Learning Direction
 * Intent → Skill Gap → Direction
 * PHP 5.4 Compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');
require_once('learning_direction_engine.php');

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$learner_id = (int) $_SESSION['phx_user_id'];
$page = "learningPath";
require_once('learnerHead_Nav2.php');

/* Fetch active intent */
$intent = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT * FROM learner_learning_intent
  WHERE learner_id = $learner_id
    AND intent_status='active'
  ORDER BY intent_version DESC
  LIMIT 1
"));

/* Fetch active skill gap */
$skill_gap = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT * FROM learner_skill_gap
  WHERE learner_id = $learner_id
    AND skill_gap_status='active'
  ORDER BY skill_gap_version DESC
  LIMIT 1
"));

if (!$intent || !$skill_gap) {
    die('Learning Intent and Skill Gap are required.');
}

/* Determine direction 
list($direction, $reasons) = determineLearningDirection($intent, $skill_gap);  */


$result = determineLearningDirection($intent, $skill_gap);

if (
    !is_array($result) ||
    empty($result['direction_code']) ||
    !isset($result['reasons'])
) {
    die('Learning Direction Engine returned invalid result');
}

$direction = $result['direction_code'];
$reasons   = $result['reasons'];
$trace     = isset($result['trace']) ? $result['trace'] : array();


?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="d-flex justify-content-end">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="learning-path.php">Learning Path</a></li>
    <li class="breadcrumb-item active">Learning Direction</li>
  </ol>
</nav>




<!-- Header -->
<div class="row mb-4">
  <div class="col-12">
    <h4 class="fw-bold">
      <i class="fa fa-compass fs-4 text-primary me-2"></i>
      Your Learning Direction
    </h4>
    <p class="text-muted mb-0">
      This defines <strong>how your learning will progress next</strong>.
      You can revise this anytime by updating intent or skill gap.
    </p>
  </div>
</div>

<!-- ================= LEARNING INTENT CONTEXT ================= -->
<!-- ================= LEARNING INTENT CONTEXT ================= -->
<div class="accordion mb-4" id="intentAccordion">
  <div class="accordion-item">

    <h2 class="accordion-header">
      <button class="accordion-button collapsed bg-label-primary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#intentBody">

        <i class="fa fa-compass me-2"></i>
        Your Learning Intent

        <!-- CREATED DATE -->
        <?php if (!empty($intent['created_at'])) { ?>
          <span class="ms-3 text-muted small">
            <i class="fa fa-calendar-alt me-1"></i>
            Declared on <?php echo date('d M Y', strtotime($intent['created_at'])); ?>
          </span>
        <?php } ?>

        <!-- SUPERSEDED BADGE -->
        <?php if ((int)$intent['intent_version'] > 1) { ?>
          <span class="badge bg-label-warning ms-3">
            <i class="fa fa-history me-1"></i>
            Revised
          </span>
        <?php } ?>

      </button>
    </h2>

    <div id="intentBody" class="accordion-collapse collapse">
      <div class="accordion-body">

        <!-- EXPLANATION -->
        <div class="alert alert-light mb-4">
          <i class="fa fa-circle-info me-1"></i>
          This section captures the context in which you want to learn.
          Skill gaps, learning direction, milestones, and pathways are
          derived relative to this intent.
        </div>

        <!-- ROW 1 : FOCUS / STATE / DIRECTION -->
        <div class="row mb-3">

          <div class="col-md-4">
            <strong>
              <i class="fa fa-bullseye text-primary me-1"></i>
              Focus
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['reference_axis']); ?>
              <?php if (!empty($intent['reference_label'])) { ?>
                <div class="small text-muted">
                  <i class="fa fa-tag me-1"></i>
                  <?php echo htmlspecialchars($intent['reference_label']); ?>
                </div>
              <?php } ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-user-check text-success me-1"></i>
              Current State
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['context_stage']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-compass text-info me-1"></i>
              Learning Direction
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['intent_direction']); ?>
            </div>
          </div>

        </div>

        <!-- ROW 2 : MOTIVATION / PREFERENCE / CONSTRAINTS -->
        <div class="row mb-3">

          <div class="col-md-4">
            <strong>
              <i class="fa fa-heart text-danger me-1"></i>
              Motivation
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['motivation_reason']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-brain text-secondary me-1"></i>
              Learning Preference
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['cognitive_preference']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-clock text-warning me-1"></i>
              Constraints
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['constraints']); ?>
            </div>
          </div>

        </div>

        <!-- ROW 3 : CONFIDENCE + AI COMFORT -->
        <div class="row mb-3">

          <div class="col-md-4">
            <strong>
              <i class="fa fa-signal text-primary me-1"></i>
              Intent Confidence
            </strong>
            <div class="text-muted">
              <?php
                if ($intent['reference_confidence'] === 'high') {
                  echo '<span class="badge bg-label-success">High confidence</span>';
                } elseif ($intent['reference_confidence'] === 'medium') {
                  echo '<span class="badge bg-label-warning">Moderate confidence</span>';
                } else {
                  echo '<span class="badge bg-label-secondary">Exploratory / Low confidence</span>';
                }
              ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-robot text-secondary me-1"></i>
              AI Comfort
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($intent['ai_comfort']); ?>
            </div>
          </div>

        </div>

        <!-- OPTIONAL NOTES -->
        <?php if (!empty($intent['context_notes'])) { ?>
        <div class="row mb-3">
          <div class="col-12">
            <strong>
              <i class="fa fa-comment-dots text-muted me-1"></i>
              Additional Context
            </strong>
            <div class="text-muted">
              <?php echo nl2br(htmlspecialchars($intent['context_notes'])); ?>
            </div>
          </div>
        </div>
        <?php } ?>

        <!-- ACTION -->
        <div class="d-flex justify-content-end mt-3">
          <a href="learning-intent.php?revise=1"
             class="btn btn-sm btn-outline-primary">
            <i class="fa fa-pen me-1"></i>
            Revise Learning Intent
          </a>
        </div>

      </div>
    </div>

  </div>
</div>
<!-- ================= END LEARNING INTENT CONTEXT ================= -->
<!-- ================= SKILL GAP CONTEXT ================= -->
<div class="accordion mb-4" id="skillGapAccordion">
  <div class="accordion-item">

    <h2 class="accordion-header">
      <button class="accordion-button collapsed bg-label-secondary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#skillGapBody"
              aria-expanded="false">

        <i class="fa fa-chart-line me-2"></i>
        Your Skill Gap Challenge 

        <span class="ms-3 text-muted small">
          <i class="fa fa-calendar-alt me-1"></i> Declared On: 
          <?php echo date('d M Y', strtotime($skill_gap['created_at'])); ?>
          &nbsp;|&nbsp;</span>
		  <span class="badge bg-label-warning ms-3">
          <i class="fa fa-history me-1"></i>
          <?php echo ($skill_gap['skill_gap_status'] === 'active')
            ? 'Active'
            : 'Revised'; ?>
        </span>

      </button>
    </h2>

    <div id="skillGapBody" class="accordion-collapse collapse">
      <div class="accordion-body">

<?php if ($skill_gap) { ?>

        <!-- ROW 1 -->
        <div class="row mb-3">
          <div class="col-md-4">
            <strong>
              <i class="fa fa-eye text-primary me-1"></i>
              Skill Exposure
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($skill_gap['exposure']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-brain text-info me-1"></i>
              Conceptual Clarity
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($skill_gap['conceptual_clarity']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-wrench text-success me-1"></i>
              Application Ability
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($skill_gap['application_ability']); ?>
            </div>
          </div>
        </div>

        <!-- ROW 2 -->
        <div class="row mb-3">
          <div class="col-md-4">
            <strong>
              <i class="fa fa-toolbox text-secondary me-1"></i>
              Tool Readiness
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($skill_gap['tool_readiness']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-signal text-primary me-1"></i>
              Confidence Level
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($skill_gap['confidence_level']); ?>
            </div>
          </div>

          <div class="col-md-4">
            <strong>
              <i class="fa fa-arrows-spin text-warning me-1"></i>
              Learning Friction
            </strong>
            <div class="text-muted">
              <?php echo htmlspecialchars($skill_gap['learning_friction']); ?>
            </div>
          </div>
        </div>

        <!-- BLOCKERS -->
        <?php if (!empty($skill_gap['blockers'])) { ?>
        <div class="row mb-3">
          <div class="col-12">
            <strong>
              <i class="fa fa-comment-dots text-muted me-1"></i>
              Blockers
            </strong>
            <div class="text-muted">
              <?php echo nl2br(htmlspecialchars($skill_gap['blockers'])); ?>
            </div>
          </div>
        </div>
        <?php } ?>

        <!-- ACTION -->
        <div class="d-flex justify-content-end mt-3">
          <a href="skills-gap.php?action=revise"
             class="btn btn-sm btn-outline-primary">
            <i class="fa fa-pen me-1"></i>
            Revise Skill Gap
          </a>
        </div>

<?php } ?>

      </div>
    </div>
  </div>
</div>
<!-- ================= END SKILL GAP CONTEXT ================= -->




<!-- ================= LEARNING DIRECTION EXPLANATION (ACCORDION) ================= -->
<div class="accordion mb-4" id="learningDirectionExplanation">

  <div class="accordion-item">

    <h2 class="accordion-header" id="learningDirectionExplainHeading">
      <button class="accordion-button collapsed bg-label-primary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#learningDirectionExplainBody"
              aria-expanded="false"
              aria-controls="learningDirectionExplainBody">

        <i class="fa fa-code text-primary me-2"></i>
        How Your Learning Direction Is Determined
      </button>
    </h2>

    <div id="learningDirectionExplainBody"
         class="accordion-collapse collapse"
         aria-labelledby="learningDirectionExplainHeading"
         data-bs-parent="#learningDirectionExplanation">

      <div class="accordion-body">

        <p class="text-muted mb-3">
          Your learning direction is not chosen arbitrarily.
          It is derived by combining <strong>your intent</strong> with
          <strong>how learning currently feels</strong>.
        </p>

        <div class="row">

          <!-- Learning Intent -->
          <div class="col-md-4 mb-3">
            <strong>
              <i class="fa fa-compass text-primary me-1"></i>
              Learning Intent
            </strong>
            <p class="text-muted small mb-0">
              Defines <em>what you want to learn</em>, your motivation,
              preferred approach, and constraints.
            </p>
          </div>

          <!-- Skill Gap -->
          <div class="col-md-4 mb-3">
            <strong>
              <i class="fa fa-chart-line text-warning me-1"></i>
              Skill Gap Signals
            </strong>
            <p class="text-muted small mb-0">
              Indicates where learning feels unclear, difficult,
              blocked, or confidence is low.
            </p>
          </div>

          <!-- Learning Direction -->
          <div class="col-md-4 mb-3">
            <strong>
              <i class="fa fa-route text-success me-1"></i>
              Learning Direction
            </strong>
            <p class="text-muted small mb-0">
              Determines <em>pace, structure, guidance depth</em>,
              and how your next learning steps are shaped.
            </p>
          </div>

        </div>

        <hr class="my-3">

        <p class="mb-2 fw-semibold">
          In simple terms:
        </p>

        <ul class="mb-0">
          <li><strong>Your intent</strong> sets the destination</li>
          <li><strong>Your skill gap</strong> reveals the terrain</li>
          <li><strong>Your learning direction</strong> chooses the best route</li>
        </ul>

      </div>
    </div>

  </div>
</div>
<!-- ================= END LEARNING DIRECTION EXPLANATION ================= -->

<?php if (!empty($trace)) { ?>
<div class="accordion mb-4" id="traceAccordion">

  <div class="accordion-item">

    <h2 class="accordion-header">
      <button class="accordion-button collapsed bg-label-secondary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#traceBody">
        <i class="fa fa-code-branch me-2"></i>
        Why this direction was chosen (Decision Trace)
      </button>
    </h2>

    <div id="traceBody" class="accordion-collapse collapse">
      <div class="accordion-body">

        <p class="text-muted mb-3">
          The system evaluated your intent and skill gap using the following rules:
        </p>

        <ul class="mb-3">
          <?php foreach ($trace['rules_hit'] as $rule) { ?>
            <li>
              <span class="badge bg-label-primary me-2"><?php echo $rule; ?></span>
            </li>
          <?php } ?>
        </ul>

        <p class="mb-1 fw-semibold">Inputs considered:</p>
        <ul class="small text-muted mb-0">
          <li><strong>Intent Focus:</strong> <?php echo htmlspecialchars($intent['reference_axis']); ?></li>
          <li><strong>Learning Preference:</strong> <?php echo htmlspecialchars($intent['cognitive_preference']); ?></li>
          <li><strong>Exposure:</strong> <?php echo htmlspecialchars($skill_gap['exposure']); ?></li>
          <li><strong>Conceptual Clarity:</strong> <?php echo htmlspecialchars($skill_gap['conceptual_clarity']); ?></li>
          <li><strong>Application Ability:</strong> <?php echo htmlspecialchars($skill_gap['application_ability']); ?></li>
          <li><strong>Confidence:</strong> <?php echo htmlspecialchars($skill_gap['confidence_level']); ?></li>
        </ul>

      </div>
    </div>

  </div>
</div>
<?php } ?>




<?php
$acceptedDir = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT * FROM learner_learning_direction
  WHERE learner_id = $learner_id
    AND direction_status = 'accepted'
  ORDER BY direction_version DESC
  LIMIT 1
"));
?>

<?php if ($acceptedDir) { ?>
  <div class="alert alert-success mb-4">
    <i class="fa fa-check-circle me-2"></i>
    <strong>Learning Direction Accepted</strong><br>
    This direction was accepted on
    <?php echo date('d M Y', strtotime($acceptedDir['accepted_at'])); ?>.
    It is currently guiding milestones and learning pathways.
  </div>
<?php } else { ?>
  <div class="alert alert-warning mb-4">
    <i class="fa fa-circle-exclamation me-2"></i>
    <strong>Learning Direction Not Yet Accepted</strong><br>
    Please review and accept the recommended direction to proceed.
  </div>
<?php } ?>




<!-- Direction Card -->
<div class="card mb-4 border border-primary">
  <div class="card-body">

    <h5 class="mb-2">
      <i class="fa fa-route text-primary me-2"></i>
      System-Recommended Direction
    </h5>

    <h4 class="fw-bold text-primary mb-3">
      <?php echo ucwords(str_replace('_',' ', $direction)); ?>
    </h4>

    <p class="mb-2 fw-semibold">Why this direction was selected:</p>
    <ul class="mb-3">
      <?php foreach ($reasons as $r) { ?>
        <li><?php echo htmlspecialchars($r); ?></li>
      <?php } ?>
    </ul>

    <p class="text-muted mb-0">
      This direction adapts milestones, learning mode, and guidance depth.
    </p>

  </div>
</div>

<!-- Actions -->
<div class="d-flex justify-content-between">
  <div>
    <a href="learning-intent.php?revise=1" class="btn btn-outline-secondary me-2">
      <i class="fa fa-pen me-1"></i> Revise Intent
    </a>
    <a href="skills-gap.php?action=revise" class="btn btn-outline-secondary">
      <i class="fa fa-chart-line me-1"></i> Revise Skill Gap
    </a>
  </div>

  <?php if (!$acceptedDir) { ?>
  <form method="post" action="save-learning-direction.php">
    <button type="submit" class="btn btn-primary">
      <i class="fa fa-check me-1"></i> Accept & Continue
    </button>
  </form>
<?php } else { ?>
  <button class="btn btn-secondary" disabled>
    <i class="fa fa-lock me-1"></i> Direction Locked
  </button>
<?php } ?>

</div>

</div>


<?php require_once('../platformFooter.php'); ?>
