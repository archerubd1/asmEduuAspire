<?php
/**
 * learnersNav.php
 * Safe, session-aware, and PHP 5.4 compatible
 */

// Ensure config and session
require_once('../../config.php');
if (session_id() == '') session_start();

// Validate session before proceeding
if (!isset($_SESSION['phx_logged_in']) || $_SESSION['phx_logged_in'] !== true) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

// Retrieve session data
$user_login = isset($_SESSION['phx_user_login']) ? $_SESSION['phx_user_login'] : '';
$user_type  = isset($_SESSION['phx_user_type']) ? $_SESSION['phx_user_type'] : 'Learner';

// Initialize fallback values
$fname = 'Platform';
$sname = 'Learner';

// -----------------------------------------------------------------------------
// Fetch Learner Details from DB
// -----------------------------------------------------------------------------
if ($user_login != '') {
    if ($stmt = $coni->prepare("SELECT name, surname FROM users WHERE login = ? LIMIT 1")) {
        $stmt->bind_param("s", $user_login);
        $stmt->execute();
        $stmt->bind_result($name, $surname);

        if ($stmt->fetch()) {
            $fname = $name;
            $sname = $surname;
        } else {
            // Quietly log issue for debugging, no UI output
            error_log("⚠️ learnersNav: No user found for login '$user_login'");
        }

        $stmt->close();
    } else {
        error_log("❌ learnersNav: SQL prepare failed - " . $coni->error);
    }
} else {
    error_log("⚠️ learnersNav: Empty user_login session value.");
}
?>



  <!-- learners Navbar -->

        <nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar"
>
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>


<!---------------------   GAMIFICATION FEATURES DropDown Menu ------------------------------->

<div class="navbar-nav-left d-flex align-items-center" id="navbar-collapse">
  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Gamification & Interactivities Menu -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        <div class="avatar">
          <img
            src="../assets/img/avatars/gamification.png"
            alt="Gamification Avatar"
            class="w-px-40 h-auto rounded-circle"
          />
        </div>
      </a>
      <!-- Ensure the dropdown opens on the right -->
      <ul class="dropdown-menu dropdown-menu-right shadow-lg">
        <li>
          <a class="dropdown-item" href="#">
            <img
              src="../assets/img/success.png"
              alt="Gamification Dropdown"
              class="w-px-40 h-auto rounded-circle"
            />
            <span class="align-middle">Gamification Features</span>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>

        <li>
          <a class="dropdown-item" href="leaderboards.php">
            <i class="bx bxs-trophy me-2 text-primary"></i> 
            <span class="align-middle">Leaderboards</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="points-system.php">
            <i class="bx bx-star me-2 text-warning"></i>
            <span class="align-middle">Point Systems</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="incentives.php">
            <i class="bx bxs-coupon me-2 text-success"></i>
            <span class="align-middle">Incentives</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="my-badges.php">
            <i class="bx bxs-purchase-tag me-2 text-info"></i>
            <span class="align-middle">My Badges</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="behavioral-insights.php">
            <i class="bx bx-pie-chart-alt me-2 text-secondary"></i>
            <span class="align-middle">Behavioral Insights</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="adaptive-learning-paths.php">
            <i class="bx bxs-layer me-2 text-dark"></i>
            <span class="align-middle">Adaptive Learning Paths</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>

 



<!---------------------   PERSONALIZATION  FEATURES DropDown Menu ------------------------------->
<div class="navbar-nav-left d-flex align-items-center" id="navbar-collapse" style="padding-left: 20px;">
  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Personalization & Interactivities Menu -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        <div class="avatar">
          <img
            src="../assets/img/personalized.png"
            alt="Personalization Avatar"
            class="w-px-40 h-auto rounded-circle"
          />
        </div>
      </a>
      <!-- Ensure the dropdown opens on the right -->
      <ul class="dropdown-menu dropdown-menu-right shadow-lg">
        <li>
          <a class="dropdown-item" href="#">
            <img
              src="../assets/img/adjust.png"
              alt="Personalization Dropdown"
              class="w-px-40 h-auto rounded-circle"
            />
            <span class="align-middle">Personalization Features</span>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>

        <li>
          <a class="dropdown-item" href="discussion-forums.php">
            <i class="bx bx-chat me-2 text-primary"></i> 
            <span class="align-middle">Discussion Forums</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="peer-reviews.php">
            <i class="bx bx-star me-2 text-warning"></i> 
            <span class="align-middle">Peer Reviews</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="metaverse-spaces.php">
            <i class="bx bx-globe me-2 text-success"></i> 
            <span class="align-middle">Metaverse Spaces</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="mentorship-request.php">
            <i class="bx bx-rocket me-2 text-info"></i> 
            <span class="align-middle">Mentorship Request</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="ai-recommendations.php">
            <i class="bx bx-chart me-2 text-secondary"></i> 
            <span class="align-middle">AI Recommendations</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="earned-certificates.php">
            <i class="bx bx-award me-2 text-dark"></i> 
            <span class="align-middle">Earned Certificates</span>
          </a>
        </li>
        
        <li>
          <div class="dropdown-divider"></div>
        </li>

        <li>
          <a class="dropdown-item" href="blockchain-credentials.php">
            <i class="bx bxs-bank me-2 text-primary"></i> 
            <span class="align-middle">Blockchain Credentials</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="generative-ai-tools.php">
            <i class="bx bx-code me-2 text-warning"></i> 
            <span class="align-middle">Generative AI Tools</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="ar-vr-management.php">
            <i class="bx bx-glasses me-2 text-info"></i> 
            <span class="align-middle">AR/VR Management</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="skills-career-roadmaps.php">
            <i class="bx bx-briefcase me-2 text-success"></i> 
            <span class="align-middle">Skills & Career Roadmaps</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>


<!---------------------   CONTENT REPO & CONTENT  FEATURES DropDown Menu ------------------------------->
<div class="navbar-nav-left d-flex align-items-center" id="navbar-collapse" style="padding-left: 20px;">
  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Gamification & Interactivities Menu -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        <div class="avatar">
          <img
            src="../assets/img/content.png"
            alt="Personalization Avatar"
            class="w-px-40 h-auto rounded-circle"
          />
        </div>
      </a>
      <!-- Ensure the dropdown opens on the right -->
      <ul class="dropdown-menu dropdown-menu-right shadow-lg">
        <li>
          <a class="dropdown-item" href="#">
            <img
              src="../assets/img/creative-writing.png"
              alt="Gamification Dropdown"
              class="w-px-40 h-auto rounded-circle"
            />
            <span class="align-middle">Content Library Features</span>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>

        <li>
          <a class="dropdown-item" href="content-repository.php">
            <i class="bx bx-book me-2 text-primary"></i>
            <span class="align-middle">Content Repository</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="learning-resources.php">
            <i class="bx bx-bookmark me-2 text-warning"></i>
            <span class="align-middle">Learning Resources</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="interactive-resources.php">
            <i class="bx bx-receipt me-2 text-success"></i>
            <span class="align-middle">Interactive Resources</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="localization.php">
            <i class="bx bx-world me-2 text-info"></i>
            <span class="align-middle">Localization</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="live-sessions.php">
            <i class="bx bx-play-circle me-2 text-secondary"></i>
            <span class="align-middle">Live Sessions</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="affiliate-programs.php">
            <i class="bx bx-award me-2 text-dark"></i>
            <span class="align-middle">Affiliate Programs</span>
          </a>
        </li>
        
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item" href="assignments.php">
            <i class="bx bx-bookmark me-2 text-primary"></i>
            <span class="align-middle">Assignments</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="assessments_quizzes.php">
            <i class="bx bx-edit-alt me-2 text-warning"></i>
            <span class="align-middle">Assessments & Quizzes</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="grades-feedback.php">
            <i class="bx bx-message-alt-check me-2 text-info"></i>
            <span class="align-middle">Grades & Feedback</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="custom-analytics.php">
            <i class="bx bx-pie-chart-alt-2 me-2 text-success"></i>
            <span class="align-middle">Custom Analytics</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>



<!----------------------  The STANDARD Profile & Logout DropDown Menu ---------------------->
  
 <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Notifications -->
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
      >
        <i class="bx bx-bell fs-4 text-primary"></i>
        <span class="badge bg-danger rounded-pill badge-notifications">3</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="learner-alerts.php">
            <i class="bx bx-bookmark-alt me-2 text-warning"></i>
            <span>New course available: Advanced AI</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="learner-alerts.php">
            <i class="bx bx-time-five me-2 text-info"></i>
            <span>Upcoming webinar: 2 days left</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="learner-alerts.php">
            <i class="bx bx-badge-check me-2 text-success"></i>
            <span>Quiz results published</span>
          </a>
        </li>
      </ul>
    </li>
    <!-- /Notifications -->

    <!-- Learner Profile -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a
        class="nav-link dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown"
      >
        <div class="avatar avatar-online">
          <img
            src="../assets/img/avatars/learner-avatar.png"
            alt="Learner Avatar"
            class="w-px-40 h-auto rounded-circle"
          />
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="#">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                  <img
                    src="../assets/img/avatars/learner-avatar.png"
                    alt="Learner Avatar"
                    class="w-px-40 h-auto rounded-circle"
                  />
                </div>
              </div>
              <div class="flex-grow-1">
                <span class="fw-semibold d-block"><?php echo $fname.'  '.$sname; ?></span>
                <small class="text-muted">Platform <?php echo $user_type; ?></small>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item" href="learner-profile.php">
            <i class="bx bx-user me-2 text-primary"></i>
            <span class="align-middle">Manage Profile</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="#learner-progress.php">
            <i class="bx bx-line-chart me-2 text-success"></i>
            <span class="align-middle">Learning & Course Analytics</span>
          </a>
        </li>
		<li>
          <a class="dropdown-item" href="#learner-alerts.php">
            <i class="bx bx-bell me-2 text-warning"></i>
            <span class="align-middle">Alerts & Announcements</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="#learner-subscriptions.php">
            <i class="bx bx-wallet me-2 text-info"></i>
            <span class="align-middle">Subscriptions</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="#learner-settings.php">
            <i class="bx bx-cog me-2 text-secondary"></i>
            <span class="align-middle">Privacy & Custom Settings</span>
          </a>
        </li>
        
        <li>
         <div class="dropdown-divider"></div>
				</li>
				<li>
				  <a class="dropdown-item" href="#feature-request.php">
					<i class="bx bx-bulb me-2 text-warning"></i> <!-- Represents a request/idea -->
					<span class="align-middle">Feature Request</span>
				  </a>
				</li>
				<li>
				  <a class="dropdown-item" href="#help-support.php">
					<i class="bx bx-help-circle me-2 text-primary"></i> <!-- Represents help or support -->
					<span class="align-middle">Help & Support</span>
				  </a>
				</li>
				<li>
				  <a class="dropdown-item" href="#feedback-dashboard.php">
					<i class="bx bx-line-chart me-2 text-success"></i> <!-- Represents analytics or feedback tracking -->
					<span class="align-middle">Feedback Dashboard</span>
				  </a>
				</li>
				<li>
				  <a class="dropdown-item" href="#knowledge-base.php">
					<i class="bx bx-book-open me-2 text-info"></i> <!-- Represents a knowledge repository -->
					<span class="align-middle">Knowledge Base</span>
				  </a>
				</li>
				<li>
				  <div class="dropdown-divider"></div>
				</li>

        </li>
        <li>
          <a class="dropdown-item" href="../../index.php">
            <i class="bx bx-power-off me-2 text-danger"></i>
            <span class="align-middle">Log Out</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</div>

  
  
  
</nav>