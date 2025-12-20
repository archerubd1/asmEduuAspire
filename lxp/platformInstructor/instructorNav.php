<?php
/**
 * Astraal LXP - Instructor Navigation
 * Production-Ready Version (PHP 5.4+ | MySQL 5.x)
 * Compatible with UwAmp Local & GoDaddy Shared Hosting
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified phx_user_* session guard

// ✅ SESSION VALIDATION
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

// ✅ Session Variables
$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];
$sname          = isset($_SESSION['phx_user_sname']) ? $_SESSION['phx_user_sname'] : '';
$user_type      = isset($_SESSION['phx_user_type']) ? $_SESSION['phx_user_type'] : 'Instructor';

// ✅ Page Context
$page = "dashboard";
require_once('instructorHead_Nav2.php');

// ✅ Ensure DB Connection
if (!isset($coni) || !$coni) {
    die("Database connection not established. Please check config.php.");
}

// ✅ Retrieve Instructor Name
$user_name = $phx_user_login;

$stmt = $coni->prepare("SELECT name FROM users WHERE login = ?");
if (!$stmt) {
    die("Database prepare() failed: " . $coni->error);
}

$stmt->bind_param("s", $user_name);
$stmt->execute();
$stmt->bind_result($name);

if ($stmt->fetch()) {
    $fname = $name;
} else {
    $fname = "Instructor";
}

$stmt->close();
?>

<!-- ========================= Astraal LXP Instructor Navigation ========================= -->
<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar"
>

  <!-- Mobile Menu Toggle -->
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <!-- =================== Manage Learning Dropdown =================== -->
  <div class="navbar-nav-left d-flex align-items-center" id="navbar-collapse-learning">
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar">
            <img src="../assets/img/avatars/learning.png" alt="Learning Avatar" class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-right shadow-lg">
          <li>
            <a class="dropdown-item" href="#">
              <img src="../assets/img/avatars/learning1.png" alt="Learning Dropdown" class="w-px-40 h-auto rounded-circle" />
              <span class="align-middle">Manage Learning Journey</span>
            </a>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="learning-path.php" class="dropdown-item"><i class="menu-icon bx bx-flag" style="color: #ff9800;"></i>Learning Paths</a></li>
          <li><a href="problem-solving-skills.php" class="dropdown-item"><i class="menu-icon bx bx-brain" style="color: #4caf50;"></i>Problem Solving Skills</a></li>
          <li><a href="coding-ground.php" class="dropdown-item"><i class="menu-icon bx bx-code-alt" style="color: #03a9f4;"></i>Coding Ground</a></li>
          <li><a href="critical-thinking.php" class="dropdown-item"><i class="menu-icon bx bx-analyse" style="color: #e91e63;"></i>Critical Thinking</a></li>
          <li><a href="project-management.php" class="dropdown-item"><i class="menu-icon bx bx-task" style="color: #673ab7;"></i>Project Management</a></li>
          <li><a href="collaborative-learning.php" class="dropdown-item"><i class="menu-icon bx bx-group" style="color: #ff5722;"></i>Collaborative Learning</a></li>
          <li><a href="work-life-experience.php" class="dropdown-item"><i class="menu-icon bx bx-briefcase-alt" style="color: #795548;"></i>Work Life Experience</a></li>
          <li><a href="edu-5-lifelong-learning.php" class="dropdown-item"><i class="menu-icon bx bx-infinite" style="color: #00bcd4;"></i>Edu 5.0 Lifelong Learning</a></li>
          <li><a href="skills-competencies.php" class="dropdown-item"><i class="menu-icon bx bx-dialpad-alt" style="color: #009688;"></i>Skills & Competencies</a></li>
          <li><a href="mentorship-social-learning.php" class="dropdown-item"><i class="menu-icon bx bx-chat" style="color: #607d8b;"></i>Mentorship & Social Learning</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <!-- =================== Gamification Features Dropdown =================== -->
  <div class="navbar-nav-left d-flex align-items-center" id="navbar-collapse-gamification" style="padding-left: 20px;">
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar">
            <img src="../assets/img/avatars/gamification.png" alt="Gamification Avatar" class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-right shadow-lg">
          <li>
            <a class="dropdown-item" href="#"><img src="../assets/img/success.png" class="w-px-40 h-auto rounded-circle" alt="Gamification" /> Gamification Features</a>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="leaderboards.php" class="dropdown-item"><i class="bx bxs-trophy me-2 text-primary"></i>Leaderboards</a></li>
          <li><a href="points-system.php" class="dropdown-item"><i class="bx bx-star me-2 text-warning"></i>Point Systems</a></li>
          <li><a href="incentives.php" class="dropdown-item"><i class="bx bxs-coupon me-2 text-success"></i>Incentives</a></li>
          <li><a href="my-badges.php" class="dropdown-item"><i class="bx bxs-purchase-tag me-2 text-info"></i>My Badges</a></li>
          <li><a href="behavioral-insights.php" class="dropdown-item"><i class="bx bx-pie-chart-alt me-2 text-secondary"></i>Behavioral Insights</a></li>
          <li><a href="adaptive-learning-paths.php" class="dropdown-item"><i class="bx bxs-layer me-2 text-dark"></i>Adaptive Learning Paths</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <!-- =================== Personalization Features Dropdown =================== -->
  <div class="navbar-nav-left d-flex align-items-center" id="navbar-collapse-personalization" style="padding-left: 20px;">
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar">
            <img src="../assets/img/personalized.png" alt="Personalization Avatar" class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-right shadow-lg">
          <li><a class="dropdown-item" href="#"><img src="../assets/img/adjust.png" class="w-px-40 h-auto rounded-circle" /> Personalization Features</a></li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="discussion-forums.php" class="dropdown-item"><i class="bx bx-chat me-2 text-primary"></i>Discussion Forums</a></li>
          <li><a href="peer-reviews.php" class="dropdown-item"><i class="bx bx-star me-2 text-warning"></i>Peer Reviews</a></li>
          <li><a href="metaverse-spaces.php" class="dropdown-item"><i class="bx bx-globe me-2 text-success"></i>Metaverse Spaces</a></li>
          <li><a href="mentorship-request.php" class="dropdown-item"><i class="bx bx-rocket me-2 text-info"></i>Mentorship Request</a></li>
          <li><a href="ai-recommendations.php" class="dropdown-item"><i class="bx bx-chart me-2 text-secondary"></i>AI Recommendations</a></li>
          <li><a href="earned-certificates.php" class="dropdown-item"><i class="bx bx-award me-2 text-dark"></i>Earned Certificates</a></li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="blockchain-credentials.php" class="dropdown-item"><i class="bx bxs-bank me-2 text-primary"></i>Blockchain Credentials</a></li>
          <li><a href="generative-ai-tools.php" class="dropdown-item"><i class="bx bx-code me-2 text-warning"></i>Generative AI Tools</a></li>
          <li><a href="ar-vr-management.php" class="dropdown-item"><i class="bx bx-glasses me-2 text-info"></i>AR/VR Management</a></li>
          <li><a href="skills-career-roadmaps.php" class="dropdown-item"><i class="bx bx-briefcase me-2 text-success"></i>Skills & Career Roadmaps</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <!-- =================== Instructor Profile & Notifications =================== -->
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse-profile">
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Notifications -->
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="bx bx-bell fs-4 text-primary"></i>
          <span class="badge bg-danger rounded-pill badge-notifications">3</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="notifications.php"><i class="bx bx-bookmark-alt me-2 text-warning"></i>New course available: Advanced AI</a></li>
          <li><a class="dropdown-item" href="notifications.php"><i class="bx bx-time-five me-2 text-info"></i>Upcoming webinar: 2 days left</a></li>
          <li><a class="dropdown-item" href="notifications.php"><i class="bx bx-badge-check me-2 text-success"></i>Quiz results published</a></li>
        </ul>
      </li>

      <!-- Instructor Profile -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="../assets/img/avatars/instructor-avatar.png" alt="Instructor Avatar" class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/instructor-avatar.png" class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block"><?php echo htmlspecialchars($fname . ' ' . $sname); ?></span>
                  <small class="text-muted">Platform <?php echo htmlspecialchars($user_type); ?></small>
                </div>
              </div>
            </a>
          </li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="instructor-profile.php" class="dropdown-item"><i class="bx bx-user me-2 text-primary"></i>Manage Profile</a></li>
          <li><a href="learner-progress.php" class="dropdown-item"><i class="bx bx-line-chart me-2 text-success"></i>Learning & Course Analytics</a></li>
          <li><a href="instructor-alerts.php" class="dropdown-item"><i class="bx bx-bell me-2 text-warning"></i>Alerts & Announcements</a></li>
          <li><a href="instructor-subscriptions.php" class="dropdown-item"><i class="bx bx-wallet me-2 text-info"></i>Subscriptions</a></li>
          <li><a href="instructor-settings.php" class="dropdown-item"><i class="bx bx-cog me-2 text-secondary"></i>Privacy & Custom Settings</a></li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="feature-request.php" class="dropdown-item"><i class="bx bx-bulb me-2 text-warning"></i>Feature Request</a></li>
          <li><a href="help-support.php" class="dropdown-item"><i class="bx bx-help-circle me-2 text-primary"></i>Help & Support</a></li>
          <li><a href="feedback-dashboard.php" class="dropdown-item"><i class="bx bx-line-chart me-2 text-success"></i>Feedback Dashboard</a></li>
          <li><a href="knowledge-base.php" class="dropdown-item"><i class="bx bx-book-open me-2 text-info"></i>Knowledge Base</a></li>
          <li><div class="dropdown-divider"></div></li>
          <li><a href="../../index.php" class="dropdown-item"><i class="bx bx-power-off me-2 text-danger"></i>Log Out</a></li>
        </ul>
      </li>
    </ul>
  </div>

</nav>
