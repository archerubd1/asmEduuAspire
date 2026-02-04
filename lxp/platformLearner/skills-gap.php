<?php
/**
 * Astraal – Learner Skill Gap Declaration
 * Context-Aware | Learning Intent → Skill Gap
 * PHP 5.4 Compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id = (int) $_SESSION['phx_user_id'];

$page = "learningPath";
require_once('learnerHead_Nav2.php');

/* ================= FETCH ACTIVE LEARNING INTENT ================= */
$intentSql = "
  SELECT *
  FROM learner_learning_intent
  WHERE learner_id = $phx_user_id
    AND intent_status = 'active'
  ORDER BY intent_version DESC
  LIMIT 1
";
$intentRes = mysqli_query($coni, $intentSql);
$intent = mysqli_fetch_assoc($intentRes);

/* ================= FETCH ACTIVE SKILL GAP ================= */
$skillGapSql = "
  SELECT *
  FROM learner_skill_gap
  WHERE learner_id = $phx_user_id
    AND skill_gap_status = 'active'
  ORDER BY skill_gap_version DESC
  LIMIT 1
";
$skillGapRes = mysqli_query($coni, $skillGapSql);
$skill_gap = mysqli_fetch_assoc($skillGapRes);

/* ================= CONTROL FORM VISIBILITY ================= */
$showForm = true;
if ($skill_gap && !isset($_GET['action'])) {
    $showForm = false;
}
if (isset($_GET['action']) && $_GET['action'] === 'revise') {
    $showForm = true;
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
    <li class="breadcrumb-item active">Skill Gap</li>
  </ol>
</nav>

<!-- ================= SKILL GAP (READ-ONLY SUMMARY) ================= -->
<?php if ($skill_gap && !$showForm) { ?>

<!-- ================= SKILL GAP (READ-ONLY SUMMARY) ================= -->
<div class="accordion mb-4" id="skillGapSummary">
  <div class="accordion-item">

    <h2 class="accordion-header">
      <button class="accordion-button bg-label-secondary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#skillGapBody"
              aria-expanded="true">
        <i class="fa fa-chart-line fs-4 text-primary me-2"></i>
        Your Current Skill Gap (How learning feels right now)
      </button>
    </h2>

    <!-- NOTE: show + collapse + no "collapsed" class = OPEN by default -->
   <div id="skillGapBody" class="accordion-collapse collapse show">
  <div class="accordion-body"><br>

<?php
// Decode JSON safely (PHP 5.x compatible)
$exposure           = json_decode($skill_gap['exposure'], true);
$conceptual         = json_decode($skill_gap['conceptual_clarity'], true);
$application        = json_decode($skill_gap['application_ability'], true);
$tools              = json_decode($skill_gap['tool_readiness'], true);
$confidence         = json_decode($skill_gap['confidence_level'], true);
$friction           = json_decode($skill_gap['learning_friction'], true);
?>

    <!-- ROW 1 -->
    <div class="row mb-3">

      <!-- Skill Exposure -->
      <div class="col-md-4">
        <strong><i class="fa fa-eye text-primary me-1"></i> Skill Exposure</strong>
        <div class="text-muted">
          <?php
            if (is_array($exposure)) {
              if (in_array('heard_only', $exposure)) {
                echo 'You have only heard about this so far';
              } elseif (in_array('watched_read', $exposure)) {
                echo 'You have watched or read introductory material';
              } elseif (in_array('guided_practice', $exposure)) {
                echo 'You have tried this with guidance';
              } elseif (in_array('hands_on', $exposure)) {
                echo 'You have hands-on experience';
              } else {
                echo 'Exposure level not clearly indicated';
              }
            } else {
              echo 'Exposure information not available';
            }
          ?>
        </div>
      </div>

      <!-- Conceptual Clarity -->
      <div class="col-md-4">
        <strong><i class="fa fa-brain text-info me-1"></i> Conceptual Clarity</strong>
        <div class="text-muted">
          <?php
            if (is_array($conceptual)) {
              if (in_array('unclear', $conceptual)) {
                echo 'Concepts feel unclear or confusing';
              } elseif (in_array('partial', $conceptual)) {
                echo 'You understand parts, but not the full picture';
              } elseif (in_array('fragile', $conceptual)) {
                echo 'You understand but struggle to explain or apply';
              } elseif (in_array('clear', $conceptual)) {
                echo 'You feel conceptually clear';
              } else {
                echo 'Conceptual clarity not clearly indicated';
              }
            } else {
              echo 'Conceptual clarity not available';
            }
          ?>
        </div>
      </div>

      <!-- Application Ability -->
      <div class="col-md-4">
        <strong><i class="fa fa-wrench text-success me-1"></i> Application Ability</strong>
        <div class="text-muted">
          <?php
            if (is_array($application)) {
              if (in_array('dont_start', $application)) {
                echo 'You are unsure how to start applying this';
              } elseif (in_array('follow_only', $application)) {
                echo 'You can follow examples but not create independently';
              } elseif (in_array('need_help', $application)) {
                echo 'You need guidance to apply this';
              } elseif (in_array('independent', $application)) {
                echo 'You can apply this independently';
              } else {
                echo 'Application ability not clearly indicated';
              }
            } else {
              echo 'Application ability not available';
            }
          ?>
        </div>
      </div>

    </div>

    <!-- ROW 2 -->
    <div class="row mb-3">

      <!-- Tool Readiness -->
      <div class="col-md-4">
        <strong><i class="fa fa-toolbox text-secondary me-1"></i> Tool Readiness</strong>
        <div class="text-muted">
          <?php
            if (is_array($tools)) {
              if (in_array('unfamiliar', $tools)) {
                echo 'The tools and environment feel unfamiliar';
              } elseif (in_array('setup_issues', $tools)) {
                echo 'You struggle with setup and configuration';
              } elseif (in_array('basic', $tools)) {
                echo 'You can use tools at a basic level';
              } elseif (in_array('confident', $tools)) {
                echo 'You feel confident with the tools';
              } else {
                echo 'Tool readiness not clearly indicated';
              }
            } else {
              echo 'Tool readiness not available';
            }
          ?>
        </div>
      </div>

      <!-- Confidence -->
      <div class="col-md-4">
        <strong><i class="fa fa-user-shield text-primary me-1"></i> Confidence Level</strong>
        <div class="text-muted">
          <?php
            if (is_array($confidence)) {
              if (in_array('low', $confidence)) {
                echo 'Your confidence is currently low';
              } elseif (in_array('situational', $confidence)) {
                echo 'You feel confident only in familiar situations';
              } elseif (in_array('inconsistent', $confidence)) {
                echo 'Your confidence fluctuates';
              } elseif (in_array('high', $confidence)) {
                echo 'You feel confident and capable';
              } else {
                echo 'Confidence level not clearly indicated';
              }
            } else {
              echo 'Confidence information not available';
            }
          ?>
        </div>
      </div>

      <!-- Learning Friction -->
      <div class="col-md-4">
        <strong><i class="fa fa-times text-warning me-1"></i> Learning Friction</strong>
        <div class="text-muted">
          <?php
            if (is_array($friction)) {
              if (in_array('frequent_blocks', $friction)) {
                echo 'You encounter frequent blockers';
              } elseif (in_array('inconsistent', $friction)) {
                echo 'Your learning rhythm is inconsistent';
              } elseif (in_array('manageable', $friction)) {
                echo 'Some friction exists but is manageable';
              } elseif (in_array('steady', $friction)) {
                echo 'Your learning progress feels steady';
              } else {
                echo 'Learning friction not clearly indicated';
              }
            } else {
              echo 'Learning friction information not available';
            }
          ?>
        </div>
      </div>

    </div>

    <!-- BLOCKERS -->
    <?php if (!empty($skill_gap['blockers'])) { ?>
    <div class="row mb-3">
      <div class="col-12">
        <strong><i class="fa fa-comment-dots text-muted me-1"></i> Reported Blockers</strong>
        <div class="text-muted">
          <?php echo nl2br(htmlspecialchars($skill_gap['blockers'])); ?>
        </div>
      </div>
    </div>
    <?php } ?>

    <!-- ACTION -->
    <div class="d-flex justify-content-end mt-3">
      <a href="skills-gap.php?action=revise" class="btn btn-sm btn-outline-primary">
        <i class="fa fa-pen me-1"></i> Revise Skill Gap
      </a>
    </div>

  </div>
</div>

	
	
  </div>
</div>
<!-- ================= END SKILL GAP SUMMARY ================= -->


<?php } ?>
<!-- ================= END READ-ONLY SKILL GAP ================= -->

<!-- ================= LEARNING INTENT CONTEXT (UNCHANGED – YOUR VERSION IS CORRECT) ================= -->
<!-- (Intentionally not modified) -->

<?php if ($showForm) { ?>

<form method="post" action="save-skill-gap.php">

<?php if ($skill_gap) { ?>
<div class="alert alert-warning">
  <i class="fa fa-info-circle me-1"></i>
  Updating your skill gap will replace the current one.
  Your previous responses will be preserved.
</div>
<?php } ?>



<!-- ================= LEARNING INTENT (READ-ONLY CONTEXT – ACCORDION) ================= -->
<div class="accordion mb-4" id="learningIntentContext">

  <div class="accordion-item">
    
    <h2 class="accordion-header" id="learningIntentHeading">
      <button class="accordion-button collapsed bg-label-primary"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#learningIntentBody"
              aria-expanded="false"
              aria-controls="learningIntentBody">

        <i class="fa fa-compass fs-4 me-2 text-primary"></i>
        Your Learning Intent (Context)
        <span class="ms-auto text-muted small text-right">
          View your declared intent
        </span>

      </button>
    </h2>

    <div id="learningIntentBody"
         class="accordion-collapse collapse"
         aria-labelledby="learningIntentHeading"
         data-bs-parent="#learningIntentContext">

   <div class="accordion-body">
<br>

<?php if ($intent) { ?>

  <!-- ROW 1 : FOCUS + STATE -->
  <div class="row mb-3">

    <div class="col-md-4">
      <strong>
        <i class="fa fa-bullseye text-primary me-1"></i>
        Focus Type
      </strong>
      <div class="text-muted">
        <?php echo htmlspecialchars($intent['reference_axis']); ?>
      </div>
    </div>

<?php if (!empty($intent['reference_label'])) { ?>
    <div class="col-md-4">
      <strong>
        <i class="fa fa-tag text-secondary me-1"></i>
        Focus Name
      </strong>
      <div class="text-muted">
        <?php echo htmlspecialchars($intent['reference_label']); ?>
      </div>
    </div>
<?php } ?>

    <div class="col-md-4">
      <strong>
        <i class="fa fa-user-check text-success me-1"></i>
        Current State
      </strong>
      <div class="text-muted">
        <?php echo htmlspecialchars($intent['context_stage']); ?>
      </div>
    </div>

  </div>

  <!-- ROW 2 : MOTIVATION + DIRECTION + AI -->
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
        <i class="fa fa-compass text-primary me-1"></i>
        Direction
      </strong>
      <div class="text-muted">
        <?php echo htmlspecialchars($intent['intent_direction']); ?>
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

  <!-- ROW 3 : PREFERENCES + CONSTRAINTS + NOTES -->
  <div class="row mb-3">

    <div class="col-md-4">
      <strong>
        <i class="fa fa-brain text-info me-1"></i>
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

<?php if (!empty($intent['context_notes'])) { ?>
    <div class="col-md-4">
      <strong>
        <i class="fa fa-comment-dots text-muted me-1"></i>
        Additional Notes
      </strong>
      <div class="text-muted">
        <?php echo nl2br(htmlspecialchars($intent['context_notes'])); ?>
      </div>
    </div>
<?php } ?>

  </div>

  <!-- ACTION -->
  <div class="d-flex justify-content-end mt-3">
    <a href="learning-intent.php"
       class="btn btn-sm btn-outline-primary">
      <i class="fa fa-pen me-1"></i>
      Revise Learning Intent
    </a>
  </div>

<?php } else { ?>

  <div class="alert alert-danger mb-0">
    No active learning intent found.
    Please declare your learning intent first.
  </div>

<?php } ?>

</div>


    </div>
  </div>

</div>
<!-- ================= END LEARNING INTENT CONTEXT ACCORDION ================= -->



<!-- ================= SKILL GAP EXPLANATION ACCORDION ================= -->
<div class="accordion mb-4" id="skillGapHelp">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button bg-label-info collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#skillGapHelpBody">
        <i class="fa fa-bullhorn fs-4 text-primary me-2"></i>
        What are Skill Gaps and how are they used?
      </button>
    </h2>

    <div id="skillGapHelpBody" class="accordion-collapse collapse">
      <div class="accordion-body"> <br>
        <p>
          Skill gaps describe where learning feels difficult, unclear, or unsupported
          relative to your stated learning intent.
        </p>
        <ul>
          <li>Multiple selections are expected</li>
          <li>This is <strong>not</strong> an evaluation</li>
          <li>Your responses guide learning recommendations</li>
        </ul>
        <p>
          These signals help decide whether you are guided towards:
        </p>
        <ul>
          <li>Active learning</li>
          <li>Curated learning</li>
          <li>Skill boosters</li>
          <li>Level-up pathways</li>
        </ul>
      </div>
    </div>
  </div>
</div>




<form method="post" action="save-skill-gap.php">

<div class="row">

<!-- ================= LEFT COLUMN ================= -->
<div class="col-md-6">

<!-- 1. Skill Exposure -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-1">
      <i class="fa fa-eye me-2 text-primary"></i>
      Skill Exposure
    </h5>
    <small class="text-muted">
      This helps us understand how familiar you already are with this skill or topic.
      
    </small>
  </div>
  <div class="card-body">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="exposure[]" value="heard_only">
      <label class="form-check-label">I’ve only heard about it</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="exposure[]" value="watched_read">
      <label class="form-check-label">I’ve watched videos or read material</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="exposure[]" value="guided_practice">
      <label class="form-check-label">I’ve tried it with guidance</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="exposure[]" value="hands_on">
      <label class="form-check-label">I’ve done hands-on work independently</label>
    </div>
  </div>
</div>

<!-- 2. Conceptual Clarity -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-1">
      <i class="fa fa-brain me-2 text-info"></i>
      Conceptual Clarity
    </h5>
    <small class="text-muted">
      This tells us how clearly you understand the underlying concepts,
      so we can balance theory and examples effectively.
    </small>
  </div>
  <div class="card-body">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="conceptual[]" value="unclear">
      <label class="form-check-label">Concepts feel unclear or confusing</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="conceptual[]" value="partial">
      <label class="form-check-label">I understand parts, but not the full picture</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="conceptual[]" value="fragile">
      <label class="form-check-label">I understand, but struggle to explain it</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="conceptual[]" value="clear">
      <label class="form-check-label">I am conceptually clear</label>
    </div>
  </div>
</div>

<!-- 3. Application Ability -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-1">
      <i class="fa fa-wrench me-2 text-success"></i>
      Application Ability
    </h5>
    <small class="text-muted">
      This helps identify whether your gap is in knowing *what to do* or *how to do it*,
      guiding practice depth and scaffolding.
    </small>
  </div>
  <div class="card-body">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="application[]" value="dont_start">
      <label class="form-check-label">I don’t know where to start</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="application[]" value="follow_only">
      <label class="form-check-label">I can follow examples, not create on my own</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="application[]" value="need_help">
      <label class="form-check-label">I need guidance for most applications</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="application[]" value="independent">
      <label class="form-check-label">I can apply independently</label>
    </div>
  </div>
</div>

</div>

<!-- ================= RIGHT COLUMN ================= -->
<div class="col-md-6">

<!-- 4. Tool & Environment Readiness -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-1">
      <i class="fa fa-toolbox me-2 text-secondary"></i>
      Tool & Environment Readiness
    </h5>
    <small class="text-muted">
      This ensures your learning path accounts for tooling setup,
      environments, and platform familiarity.
    </small>
  </div>
  <div class="card-body">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="tools[]" value="unfamiliar">
      <label class="form-check-label">Tools feel unfamiliar</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="tools[]" value="setup_issues">
      <label class="form-check-label">I struggle with setup and configuration</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="tools[]" value="basic">
      <label class="form-check-label">I can use tools at a basic level</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="tools[]" value="confident">
      <label class="form-check-label">I am confident using the tools</label>
    </div>
  </div>
</div>

<!-- 5. Confidence & Self-Efficacy -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-1">
      <i class="fa fa-user me-2 text-primary"></i>
      Confidence & Self-Efficacy
    </h5>
    <small class="text-muted">
      Confidence affects momentum. This helps us decide pacing,
      reinforcement, and encouragement strategies.
    </small>
  </div>
  <div class="card-body">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="confidence[]" value="low">
      <label class="form-check-label">Low confidence</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="confidence[]" value="situational">
      <label class="form-check-label">Confident only in familiar situations</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="confidence[]" value="inconsistent">
      <label class="form-check-label">Confidence fluctuates</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="confidence[]" value="high">
      <label class="form-check-label">High confidence</label>
    </div>
  </div>
</div>

<!-- 6. Learning Friction & Consistency -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-1">
      <i class="fa fa-sitemap me-2 text-warning"></i>
      Learning Friction & Consistency
    </h5>
    <small class="text-muted">
      This helps us understand how smoothly learning fits into your life,
      shaping cadence and milestone design.
    </small>
  </div>
  <div class="card-body">
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="friction[]" value="frequent_blocks">
      <label class="form-check-label">I get blocked frequently</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="friction[]" value="inconsistent">
      <label class="form-check-label">My learning rhythm is inconsistent</label>
    </div>
    <div class="form-check mb-2">
      <input class="form-check-input" type="checkbox" name="friction[]" value="manageable">
      <label class="form-check-label">Some friction, but manageable</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="friction[]" value="steady">
      <label class="form-check-label">I make steady progress</label>
    </div>
  </div>
</div>

</div>
</div>

<!-- Blockers -->
<div class="card mb-4">
  <div class="card-body">
    <label class="form-label fw-semibold">
      <i class="fa fa-comment-dots fs-4 me-2"></i>
      Anything currently blocking your progress?
    </label>
    <textarea class="form-control" name="blockers" rows="3"
      placeholder="Optional — time, resources, clarity, confidence, or anything else"></textarea>
  </div>
</div>

<!-- Submit -->
<div class="d-flex justify-content-end">
  <button type="submit" class="btn btn-primary">
    <i class="fa fa-circle-check me-1"></i>
    Save Skills Intent
  </button>
</div>

</form>


<?php } ?>

</div>





</div>

<?php require_once('../platformFooter.php'); ?>
