<?php
/**
 * Astraal – Learner Milestones
 * Learning Direction → Execution Milestones
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

$learner_id = (int) $_SESSION['phx_user_id'];
$page = "learningPath";

require_once('learnerHead_Nav2.php');

/* ================= FETCH ACTIVE LEARNING DIRECTION ================= */
$directionRes = mysqli_query($coni, "
  SELECT *
  FROM learner_learning_direction
  WHERE learner_id = $learner_id
    AND direction_status IN ('proposed','accepted')
  ORDER BY direction_version DESC
  LIMIT 1
");

$direction = mysqli_fetch_assoc($directionRes);

if (!$direction) {
    die('Learning Direction not found.');
}

/* ================= FETCH MILESTONES ================= */
$milestonesRes = mysqli_query($coni, "
  SELECT *
  FROM learner_milestones
  WHERE learner_id = $learner_id
    AND direction_id = {$direction['direction_id']}
  ORDER BY milestone_order ASC
");
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="d-flex justify-content-end">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item">
      <a href="learning-path.php">Learning Path</a>
    </li>
    <li class="breadcrumb-item active">
      Learning Milestones
    </li>
  </ol>
</nav>

<!-- Header -->
<div class="row mb-4">
  <div class="col-12">
    <h4 class="fw-bold">
      <i class="fa fa-flag-checkered fs-4 text-primary me-2"></i>
      Your Learning Milestones
    </h4>
    <p class="text-muted mb-0">
      Milestones guide how your learning unfolds step by step
      based on your learning direction.
    </p>
  </div>
</div>

<!-- Learning Direction Summary -->
<div class="alert alert-info mb-4">
  <i class="fa fa-route me-2"></i>
  <strong>Current Learning Direction:</strong>
  <?php echo ucwords(str_replace('_',' ', $direction['direction_code'])); ?>
</div>

<!-- ================= MILESTONES LIST ================= -->
<div class="card mb-4">
  <div class="card-body">

<?php if (mysqli_num_rows($milestonesRes) > 0) { ?>

  <?php while ($m = mysqli_fetch_assoc($milestonesRes)) { ?>

    <div class="d-flex align-items-start mb-3 p-3
      <?php echo ($m['milestone_status'] === 'active')
        ? 'border border-primary rounded'
        : 'border rounded'; ?>">

      <!-- Status Icon -->
      <div class="me-3 mt-1">

        <?php if ($m['milestone_status'] === 'completed') { ?>
          <i class="fa fa-check-circle text-success fs-4"></i>

        <?php } elseif ($m['milestone_status'] === 'active') { ?>
          <i class="fa fa-play-circle text-primary fs-4"></i>

        <?php } elseif ($m['milestone_status'] === 'blocked') { ?>
          <i class="fa fa-exclamation-circle text-danger fs-4"></i>

        <?php } else { ?>
          <i class="fa fa-circle text-muted fs-5"></i>
        <?php } ?>

      </div>

      <!-- Milestone Content -->
      <div class="flex-grow-1">

        <div class="d-flex justify-content-between">
          <strong>
            <?php echo htmlspecialchars($m['milestone_title']); ?>
          </strong>

          <span class="badge bg-label-secondary">
            <?php echo ucwords(str_replace('_',' ', $m['milestone_status'])); ?>
          </span>
        </div>

        <p class="text-muted mb-1 small">
          <?php echo htmlspecialchars($m['milestone_description']); ?>
        </p>

        <?php if (!empty($m['activated_at'])) { ?>
          <small class="text-muted">
            <i class="fa fa-calendar-alt me-1"></i>
            Activated on <?php echo date('d M Y', strtotime($m['activated_at'])); ?>
          </small>
        <?php } ?>

        <?php if (!empty($m['completed_at'])) { ?>
          <br>
          <small class="text-muted">
            <i class="fa fa-check me-1"></i>
            Completed on <?php echo date('d M Y', strtotime($m['completed_at'])); ?>
          </small>
        <?php } ?>

      </div>

    </div>

  <?php } ?>

<?php } else { ?>

  <div class="alert alert-warning mb-0">
    No milestones have been generated yet.
  </div>

<?php } ?>

  </div>
</div>
<!-- ================= END MILESTONES LIST ================= -->

<!-- Guidance -->
<div class="alert alert-light">
  <i class="fa fa-info-circle me-2"></i>
  Milestones are system-guided checkpoints.
  Your progress updates as learning activities are completed.
</div>

</div>
</div>

<?php require_once('../platformFooter.php'); ?>
