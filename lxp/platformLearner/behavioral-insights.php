<?php
/**
 * Astraal LXP - Learner Behavioral Insights (Card UI + CTAs)
 * PHP 5.4 Compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

$page = "gamification";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">
<div class="row">

<?php
// SweetAlert Messages
if (isset($_REQUEST['msg'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            swal.fire("Success", "'.base64_decode($_GET['msg']).'", "success");
            history.replaceState({}, document.title, window.location.pathname);
        });
    </script>';
}
if (isset($_REQUEST['error'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            swal.fire("Error", "'.base64_decode($_GET['error']).'", "error");
            history.replaceState({}, document.title, window.location.pathname);
        });
    </script>';
}
?>

<div class="col-lg-12 mb-4">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="d-flex justify-content-end">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item"><a href="#">Gamification</a></li>
        <li class="breadcrumb-item active">Behavioral Insights</li>
    </ol>
</nav>

<!-- Accordion -->
<div class="accordion mt-3">
<div class="accordion-item">

<h4 class="accordion-header">
<button class="accordion-button bg-label-primary" data-bs-toggle="collapse"
        data-bs-target="#accordionInsights" aria-expanded="true">
    <i class="bx bx-analyse"></i>&nbsp;&nbsp; Your Personality & Behavioral Insights
</button>
</h4>

<div id="accordionInsights" class="accordion-collapse collapse show">
<div class="accordion-body">

<div class="row g-4 mt-2">

<?php
$insights = array(
    array("📚","Learning Engagement","Avg. 8 hrs/week","2024-04-10","Increasing","success","learning_engagement","Add short review sessions"),
    array("📝","Assessment Performance","Avg. 85% score","2024-04-12","Stable","secondary","assessment_performance","Revise weak topics"),
    array("⏳","Study Habits","Peak: 8–10 PM","2024-04-11","Stable","secondary","study_habits","Try earlier sessions"),
    array("💡","Problem Solving","75 challenges","2024-04-13","Increasing","success","problem_solving","Attempt harder levels"),
    array("🎯","Goal Tracking","3-week streak","2024-04-14","Strong","success","goal_tracking","Aim for 50 days"),
    array("🤝","Community Engagement","5 discussions","2024-04-09","Decreasing","danger","community","Engage more"),
    array("🧠","Cognitive Load","3 hrs/day","2024-04-12","Stable","secondary","cognitive_load","Take breaks"),
    array("📅","Learning Consistency","2 modules/week","2024-04-10","Slowing","warning","consistency","Set weekly goals"),
    array("🏆","Achievements","5 badges","2024-04-13","Increasing","success","achievements","Target certification"),
    array("🖥️","Platform Interaction","5 logins/week","2024-04-11","Decreasing","danger","platform_usage","Daily check-ins"),
    array("📊","Content Preference","70% video","2024-04-12","Stable","secondary","content_preference","Add hands-on work"),
    array("📈","Improvement Rate","+15% growth","2024-04-10","Improving","success","improvement_rate","Maintain revision")
);

foreach ($insights as $i) {
?>
<div class="col-xl-4 col-lg-6 col-md-6">
<div class="card h-100 shadow-sm border-start border-<?php echo $i[5]; ?>">

<div class="card-body">

<!-- Header -->
<div class="d-flex align-items-center mb-2">
    <div class="avatar avatar-sm bg-<?php echo $i[5]; ?> text-white me-2">
        <?php echo $i[0]; ?>
    </div>
    <h5 class="mb-0"><?php echo $i[1]; ?></h5>
</div>

<!-- Insight -->
<p class="mb-1">
    <strong>Insight:</strong><br>
    <?php echo $i[2]; ?>
</p>

<!-- Meta -->
<div class="d-flex justify-content-between align-items-center mt-2">
    <small class="text-muted">
        <i class="bx bx-calendar"></i> <?php echo $i[3]; ?>
    </small>
    <span class="badge bg-<?php echo $i[5]; ?>">
        <?php echo $i[4]; ?>
    </span>
</div>

<!-- Recommendation -->
<div class="alert alert-light mt-3 mb-2 p-2">
    <i class="bx bx-message-square-check text-primary"></i>
    <?php echo $i[7]; ?>
</div>

<!-- CTAs -->
<hr class="my-2">

<div class="d-flex justify-content-between">

    <a href="behavioral-insight-details.php?type=<?php echo $i[6]; ?>"
       class="btn btn-sm btn-outline-primary">
        <i class="bx bx-search"></i> Details
    </a>

    <a href="recommended-actions.php?type=<?php echo $i[6]; ?>"
       class="btn btn-sm btn-outline-success">
        <i class="bx bx-trending-up"></i> Improve
    </a>

    <button class="btn btn-sm btn-outline-warning"
            data-bs-toggle="modal"
            data-bs-target="#goalModal"
            data-insight="<?php echo $i[1]; ?>">
        <i class="bx bx-target-lock"></i> Goal
    </button>

</div>

</div>
</div>
</div>
<?php } ?>

</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>

<!-- Goal Modal -->
<div class="modal fade" id="goalModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-target-lock"></i> Set Learning Goal
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <label class="form-label">Weekly Target</label>
        <input type="number" class="form-control" placeholder="e.g. 10 hours / 3 modules">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save Goal</button>
      </div>

    </div>
  </div>
</div>

<?php require_once('../platformFooter.php'); ?>

<style>
.card:hover {
    transform: translateY(-4px);
    transition: all 0.2s ease-in-out;
}
</style>
