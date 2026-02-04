<?php
/**
 * Astraal LXP – Learner Learning Path
 * Orchestration + Buckets (CONTROL PRESERVED)
 * PHP 5.4 compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

$page = "learningPath";
require_once('learnerHead_Nav2.php');

/* ---------------- SESSION ---------------- */
if (!isset($_SESSION['phx_user_id'], $_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$learner_id    = (int) $_SESSION['phx_user_id'];
$learner_login = $_SESSION['phx_user_login'];

/* ---------------- FETCH STATES ---------------- */

/* Intent */
$intent = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT * FROM learner_learning_intent
  WHERE learner_id=$learner_id
  ORDER BY intent_version DESC LIMIT 1
"));

/* Skill Gap */
$skill = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT * FROM learner_skill_gap
  WHERE learner_id=$learner_id
  ORDER BY skill_gap_version DESC LIMIT 1
"));

/* Direction */
$direction = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT * FROM learner_learning_direction
  WHERE learner_id=$learner_id AND direction_status='accepted'
  ORDER BY direction_version DESC LIMIT 1
"));

$currentDirection = $direction ? $direction['direction_code'] : 'not_set';

/* ---------------- AUTOLOGIN ---------------- */
$autoLoginToken = '';
$r = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT autologin FROM users
  WHERE login='".mysqli_real_escape_string($coni,$learner_login)."'
  LIMIT 1
"));
if ($r) $autoLoginToken = trim($r['autologin']);

/* ---------------- COURSES ---------------- 
$coursesRes = mysqli_query($coni,"
SELECT 
  c.id AS course_id,
  c.name AS course_name,
  d.name AS bucket_name,
  MIN(ltc.lessons_ID) AS first_lesson_id
FROM courses c
JOIN users_to_courses utc ON utc.courses_id=c.id
JOIN directions d ON d.id=c.directions_ID
LEFT JOIN lessons_to_courses ltc ON ltc.courses_ID=c.id
WHERE utc.users_login='".mysqli_real_escape_string($coni,$learner_login)."'
GROUP BY c.id, c.name, d.name
");  

$coursesRes = mysqli_query($coni,"
SELECT
  course_id,
  course_name,
  bucket_name,
  first_lesson_id
FROM v_lxp_learning_buckets
");



$rows = array();
while ($row = mysqli_fetch_assoc($coursesRes)) {
    $rows[] = $row;
}
 */

/* ---------------- COURSES (LXP VIEW) ---------------- */



$coursesRes = mysqli_query($coni,"
SELECT
  course_id,
  course_name,
  bucket_name,
  first_lesson_id
FROM v_lxp_learning_buckets
WHERE learner_login = '".mysqli_real_escape_string($coni,$learner_login)."'
");


$rows = array();
while ($row = mysqli_fetch_assoc($coursesRes)) {
    $rows[] = $row;
}


/* ---------------- HELPERS ---------------- */

function stateBadge($text, $color, $icon) {
    return '<span class="badge bg-label-'.$color.' ms-2">
      <i class="'.$icon.' me-1"></i>'.$text.'
    </span>';
}

function renderCourseBadges() {
    return '
      <div class="mt-1">
        <span class="badge bg-label-info me-1"
              data-bs-toggle="tooltip"
              title="Recommended based on your accepted learning direction">
          <i class="bx bx-compass me-1"></i>Recommended
        </span>

        <span class="badge bg-label-warning me-1"
              data-bs-toggle="tooltip"
              title="This course supports one or more of your learning milestones">
          <i class="bx bx-flag me-1"></i>Boosts Milestone
        </span>

        <span class="badge bg-label-secondary"
              data-bs-toggle="tooltip"
              title="Popular among learners with similar roles or goals">
          <i class="bx bx-group me-1"></i>Popular
        </span>
      </div>';
}




function echoCoursesByBucket($rows, $bucket) {
    $found = false;

    echo '<div class="table-responsive">
      <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th style="width:80px;text-align:center;">Code</th>
          <th>Name</th>
          <th style="width:120px;text-align:center;">Action</th>
        </tr>
      </thead><tbody>';

    foreach ($rows as $r) {
        if ($r['bucket_name'] !== $bucket) continue;
        $found = true;
		
        echo '<tr>
          <td class="text-center">'.$r['course_id'].'</td>
          <td>
            <div class="fw-semibold">'.htmlspecialchars($r['course_name']).'</div>
            '.renderCourseBadges().'
			<span class="text-muted small d-block mt-1"
      data-bs-toggle="tooltip"
      title="Recommended based on your learning direction, milestones, and skill gaps.">
  <i class="bx bx-info-circle me-1"></i>Why am I seeing this?
</span></td>

          <td class="text-center">
            <a href="#" onclick="launchCourse('.$r['course_id'].','.(int)$r['first_lesson_id'].')">
              <i class="bx bx-play-circle text-primary fs-3"></i>
            </a>
          </td>
        </tr>';
    }

    echo '</tbody></table></div>';

    if (!$found) {
        echo '<div class="text-muted">No courses available yet.</div>';
    }
}
?>

<script>
function launchCourse(courseId, lessonId) {
  let base = (location.hostname === 'localhost')
    ? 'http://localhost/evidya/www/'
    : 'https://eduuaspire.online/lxp/lxpre/www/';

  let key = "<?php echo $autoLoginToken; ?>";
  if (!key || !lessonId) return alert('Unable to launch course');

  fetch(base + 'index.php?autologin=' + encodeURIComponent(key), {credentials:'include'})
    .then(() => window.open(base + 'student.php?lessons_ID=' + lessonId, '_blank'));
}
</script>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl container-p-y">

<!-- ================= ORCHESTRATION ACCORDION ================= -->
<div class="accordion mb-4">

<div class="accordion-item">
<h2 class="accordion-header">
<button class="accordion-button bg-label-info" data-bs-toggle="collapse" data-bs-target="#orchestration">
  <i class="bx bx-brain me-2"></i> Learning Journey Orchestration
</button>
</h2>

<div id="orchestration" class="accordion-collapse collapse show">
<div class="accordion-body"> <br>
<div class="row g-3 text-center">

<!-- Intent -->
<div class="col-md-2">
<div class="card p-3 shadow-sm">
<i class="bx bx-search-alt fs-1 text-danger"></i>
<h6 class="mt-2">Learning Intent</h6>
<?php
echo $intent
 ? stateBadge('Declared','success','bx bx-check')
 : stateBadge('Not Set','secondary','bx bx-time');
?>
<a href="learning-intent.php" class="btn btn-danger btn-sm mt-2">Revise</a>
</div>
</div>

<!-- Skill Gap -->
<div class="col-md-2">
<div class="card p-3 shadow-sm">
<i class="bx bx-bar-chart-alt-2 fs-1 text-info"></i>
<h6 class="mt-2">Skill Gap</h6>
<?php
echo ($skill && $skill['skill_gap_status']=='active')
 ? stateBadge('Active','success','bx bx-check')
 : stateBadge('Outdated','warning','bx bx-history');
?>
<a href="skills-gap.php" class="btn btn-info btn-sm mt-2">Revise</a>
</div>
</div>

<!-- Direction -->
<div class="col-md-2">
<div class="card p-3 shadow-sm">
<i class="bx bx-bullseye fs-1 text-warning"></i>
<h6 class="mt-2">Learning Direction</h6>
<?php
echo $direction
 ? stateBadge('Accepted','success','bx bx-check-circle')
 : stateBadge('Pending','secondary','bx bx-time');
?>
<a href="learning-direction.php" class="btn btn-warning btn-sm mt-2">View</a>
</div>
</div>

<!-- Milestones -->
<div class="col-md-2">
<div class="card p-3 shadow-sm">
<i class="bx bx-flag fs-1 text-primary"></i>
<h6 class="mt-2">Milestones</h6>
<?php
echo $direction
 ? stateBadge('Generated','success','bx bx-check')
 : stateBadge('Locked','secondary','bx bx-lock');
?>
<a href="learning-milestones.php" class="btn btn-primary btn-sm mt-2">View</a>
</div>
</div>


<!-- ================= GUIDED PATHWAYS ================= -->
<div class="col-md-2">
  <div class="card shadow-sm p-3">
    <i class="bx bx-package text-dark" style="font-size:40px;"></i>
    <h6 class="mt-2">Guided Pathways</h6>

    <span class="badge bg-label-info mb-1">
      <i class="bx bx-map me-1"></i>
      Adaptive
    </span>

    <a href="#"
       class="btn btn-dark btn-sm mt-2 js-cta-tooltip"
       data-tooltip="Explore adaptive learning pathways generated from your intent, skill gaps, direction, and milestones. These evolve as you progress.">
       Explore Pathways
    </a>
  </div>
</div>

<!-- ================= LEARNING STATUS ================= -->
<div class="col-md-2">
  <div class="card shadow-sm p-3">
    <i class="bx bx-pulse text-secondary" style="font-size:40px;"></i>
    <h6 class="mt-2">Learning Status</h6>

    <span class="badge bg-label-success mb-1">
      <i class="bx bx-activity me-1"></i>
      Tracking On
    </span>

    <a href="learners-monitor-status.php"
       class="btn btn-secondary btn-sm mt-2 js-cta-tooltip"
       data-tooltip="View your current learning state, momentum, consistency, and readiness indicators based on recent activity.">
       View Progress
    </a>
  </div>
</div>


</div>
</div>
</div>
</div>

<!-- ================= LEARNING BUCKETS ================= -->
<div class="card mt-4">
<div class="card-header">
<button class="btn btn-sm btn-outline-secondary float-end"
        data-bs-toggle="tooltip"
        title="You can hide this bucket temporarily.">
  <i class="bx bx-hide me-1"></i>Hide Bucket
</button>

<h5 class="mb-1"><i class="bx bx-layer me-2"></i>Learning Buckets</h5>
<small class="text-muted">
Aligned to your accepted learning direction:
<b><?php echo strtoupper($currentDirection); ?></b>
</small>
</div>

<div class="card-body">
<ul class="nav nav-pills mb-3">
<?php
$buckets = array(
 'Active Learning',
 'Curated Paths',
 'Skills Booster',
 'Level Up Courses',
 'Crowd Favourites'
);
foreach ($buckets as $i=>$b) {
 echo '<li class="nav-item">
   <a class="nav-link '.($i==0?'active':'').'" data-bs-toggle="pill" href="#b'.$i.'">'.$b.'</a>
 </li>';
}
?>
</ul>

<div class="tab-content">
<!-- Bucket Helper Text -->
<div class="alert alert-light border mb-1" id="bucketHelpBox">
  <i class="bx bx-info-circle me-2 text-primary"></i>
  <span id="bucketHelpText">
    Courses you are currently enrolled in and actively progressing through.
  </span>
</div>
<?php foreach ($buckets as $i=>$b) { ?>

<div class="tab-pane fade <?php echo $i==0?'show active':''; ?>" id="b<?php echo $i; ?>">
<?php echoCoursesByBucket($rows, $b); ?>
</div>
<?php } ?>
</div>
</div>
</div>
<script>
const bucketHelp = {
  'Active Learning':
    'Courses you are currently enrolled in and actively progressing through.',
  'Curated Paths':
    'System-recommended courses aligned to your learning direction and milestones.',
  'Skills Booster':
    'Focused courses that strengthen skills blocking your next milestone.',
  'Level Up Courses':
    'Advanced courses unlocked to prepare you for higher responsibility or mastery.',
  'Crowd Favourites':
    'Popular courses chosen by learners with similar roles and goals.'
};

document.querySelectorAll('.nav-pills .nav-link').forEach(tab => {
  tab.addEventListener('shown.bs.tab', function () {
    const label = this.innerText.trim();
    document.getElementById('bucketHelpText').innerText =
      bucketHelp[label] || '';
  });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.forEach(function (el) {
    new bootstrap.Tooltip(el);
  });
});
</script>



</div>
</div>

<?php require_once('../platformFooter.php'); ?>
