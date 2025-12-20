<?php
/**
 * Astraal LXP - Learner Learning Paths
 * Refactored for new session guard architecture
 * PHP 5.4 compatible (UwAmp / GoDaddy)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified session management

$page = "learningPath";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

// -----------------------------------------------------------------------------
// Fetch learner autologin token dynamically
// -----------------------------------------------------------------------------
$autoLoginToken = '';
$query = mysqli_query($coni, "SELECT autologin FROM users WHERE login = '" . mysqli_real_escape_string($coni, $phx_user_login) . "' LIMIT 1");
if ($query && mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $autoLoginToken = isset($data['autologin']) ? trim($data['autologin']) : '';
}

// -----------------------------------------------------------------------------
// Fetch user's enrolled courses
// -----------------------------------------------------------------------------
$sql_query = "
SELECT 
    c.id AS course_id,
    c.name AS course_name,
    c.directions_ID,
    d.name AS direction_name,
    MIN(ltc.lessons_ID) AS first_lesson_id
FROM 
    courses c
JOIN 
    users_to_courses utc ON c.id = utc.courses_id
JOIN 
    directions d ON c.directions_ID = d.id
LEFT JOIN 
    lessons_to_courses ltc ON c.id = ltc.courses_ID
WHERE 
    utc.users_login = '" . mysqli_real_escape_string($coni, $phx_user_login) . "'
GROUP BY 
    c.id, c.name, c.directions_ID, d.name
";

$courses = mysqli_query($coni, $sql_query);

// -----------------------------------------------------------------------------
// Classification mapping
// -----------------------------------------------------------------------------
function mapClassification($classification) {
    $valid = array('K-12','Active Learning','Curated Paths','Skills Booster','Level Up Courses','Crowd Favourites');
    $classification = ucwords(trim(strtolower($classification)));
    return in_array($classification, $valid) ? $classification : '';
}

// -----------------------------------------------------------------------------
// Prepare rows
// -----------------------------------------------------------------------------
$rows = array();
if ($courses && mysqli_num_rows($courses) > 0) {
    while ($r = mysqli_fetch_assoc($courses)) {
        $rows[] = $r;
    }
}

// -----------------------------------------------------------------------------
// Display courses by category
// -----------------------------------------------------------------------------
function echoCoursesByCategory($rows, $category) {
    $found = false;

    $html = '<div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th style="text-align:center;">Code</th>
          <th style="text-align:center;">Name</th>
          <th style="text-align:center;">Direction</th>
          <th colspan="2" style="text-align:center;">Action</th>
        </tr>
      </thead>
      <tbody>';

    foreach ($rows as $row) {
        $classification = isset($row['direction_name']) ? $row['direction_name'] : '';
        $mapped = mapClassification($classification);
        if ($mapped === $category) {
            $found = true;
            $html .= '
            <tr>
              <td class="text-center">' . htmlspecialchars($row['course_id']) . '</td>
              <td>' . htmlspecialchars($row['course_name']) . '</td>
              <td>' . htmlspecialchars($classification) . '</td>
              <td class="text-center">
                <a href="course-description.php?cid=' . urlencode($row['course_id']) . '" title="View Description">
                  <i class="bx bx-book-open text-success" style="font-size:22px;"></i>
                </a>
              </td>
              <td class="text-center">
                <a href="#" onclick="autoLoginAndRedirect(\'start_learning\', ' . (int)$row['course_id'] . ', ' . (int)$row['first_lesson_id'] . ')" title="Start Learning">
                  <i class="bx bx-play-circle text-primary" style="font-size:22px;"></i>
                </a>
              </td>
            </tr>';
        }
    }

    $html .= '</tbody></table></div>';
    echo $found ? $html : '<h6 class="text-muted">No courses available in this category yet.</h6>';
}
?>

<!-- Auto Login Redirect Handler -->
<script>
function autoLoginAndRedirect(action, courseId, lessonId) {
    let isLocal = (window.location.hostname === "localhost");
    let base = isLocal 
        ? "http://localhost/evidya/www/" 
        : "https://raunakeducares.com/lxp/lxpre/www/";

    let autoLoginKey = "<?php echo $autoLoginToken; ?>";

    if (!autoLoginKey) {
        alert("Auto-login key not found for this user.");
        return;
    }

    if (action === "start_learning" && (!lessonId || lessonId == 0)) {
        alert("No lessons found for this course.");
        return;
    }

    let autoLoginUrl = base + "index.php?autologin=" + encodeURIComponent(autoLoginKey);
    let redirectUrl  = base + "student.php?lessons_ID=" + lessonId + "&from_course=" + courseId;

    fetch(autoLoginUrl, { credentials: 'include' })
        .then(response => {
            if (response.ok) {
                window.open(redirectUrl, '_blank');
            } else {
                alert("Auto-login failed. Please try again.");
            }
        })
        .catch(err => console.error("Autologin Error:", err));
}
</script>

<!-- Layout -->
<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <div class="accordion mt-3 card accordion-item">
        <h2 class="accordion-header" id="heading3">
          <button type="button" class="accordion-button bg-label-info collapsed"
                  data-bs-toggle="collapse" data-bs-target="#accordion3"
                  aria-expanded="false" aria-controls="accordion3">
            <i class="bx bx-brain"></i>&nbsp; Manage & Configure Your Learning Paths
          </button>
        </h2>
        <div id="accordion3" class="accordion-collapse collapse" aria-labelledby="heading3">
          <div class="accordion-body">
            <div class="row g-3 text-center">

              <div class="col-md-2">
                <div class="card shadow-sm p-3">
                  <i class="bx bx-search-alt text-danger" style="font-size:40px;"></i>
                  <h6 class="mt-2">Training Needs</h6>
                  <a href="learners-training-needs.php" class="btn btn-danger btn-sm mt-2">Set & Define</a>
                </div>
              </div>

              <div class="col-md-2">
                <div class="card shadow-sm p-3">
                  <i class="bx bx-bar-chart-alt-2 text-info" style="font-size:40px;"></i>
                  <h6 class="mt-2">Skill Gaps</h6>
                  <a href="learners-skills-gap.php" class="btn btn-info btn-sm mt-2">Evaluate Skills</a>
                </div>
              </div>

              <div class="col-md-2">
                <div class="card shadow-sm p-3">
                  <i class="bx bx-bullseye text-warning" style="font-size:40px;"></i>
                  <h6 class="mt-2">Learning Goals</h6>
                  <a href="learners-learning-goal.php" class="btn btn-warning btn-sm mt-2">Set Goals</a>
                </div>
              </div>

              <div class="col-md-2">
                <div class="card shadow-sm p-3">
                  <i class="bx bx-flag text-primary" style="font-size:40px;"></i>
                  <h6 class="mt-2">Milestones</h6>
                  <a href="learners-steps-milestones.php" class="btn btn-primary btn-sm mt-2">Define Steps</a>
                </div>
              </div>

              <div class="col-md-2">
                <div class="card shadow-sm p-3">
                  <i class="bx bx-package text-dark" style="font-size:40px;"></i>
                  <h6 class="mt-2">Learning Paths</h6>
                  <a href="#" class="btn btn-dark btn-sm mt-2">Walk Them</a>
                </div>
              </div>

              <div class="col-md-2">
                <div class="card shadow-sm p-3">
                  <i class="bx bx-pulse text-secondary" style="font-size:40px;"></i>
                  <h6 class="mt-2">Monitor Status</h6>
                  <a href="learners-monitor-status.php" class="btn btn-secondary btn-sm mt-2">Track Progress</a>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-header">
          <ul class="nav nav-pills gap-3" role="tablist">
            <li class="nav-item"><a href="#tab1" class="nav-link active" data-bs-toggle="pill"><i class="bx bx-book-open text-primary"></i> Active Learning</a></li>
            <li class="nav-item"><a href="#tab2" class="nav-link" data-bs-toggle="pill"><i class="bx bx-brain text-danger"></i> Curated Paths</a></li>
            <li class="nav-item"><a href="#tab3" class="nav-link" data-bs-toggle="pill"><i class="bx bx-code-alt text-success"></i> Skills Booster</a></li>
            <li class="nav-item"><a href="#tab4" class="nav-link" data-bs-toggle="pill"><i class="bx bx-bulb text-warning"></i> Level-Up Learning</a></li>
            <li class="nav-item"><a href="#tab5" class="nav-link" data-bs-toggle="pill"><i class="bx bx-line-chart text-info"></i> Crowd Favourites</a></li>
          </ul>
        </div>

        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tab1"><?php echoCoursesByCategory($rows, 'Active Learning'); ?></div>
            <div class="tab-pane fade" id="tab2"><?php echoCoursesByCategory($rows, 'Curated Paths'); ?></div>
            <div class="tab-pane fade" id="tab3"><?php echoCoursesByCategory($rows, 'Skills Booster'); ?></div>
            <div class="tab-pane fade" id="tab4"><?php echoCoursesByCategory($rows, 'Level Up Courses'); ?></div>
            <div class="tab-pane fade" id="tab5"><?php echoCoursesByCategory($rows, 'Crowd Favourites'); ?></div>
          </div>
        </div>
      </div>

    </div>
 

<?php require_once('../platformFooter.php'); ?>
