<?php
/**
 *  Astraal LXP - Learner Learning Paths
 * Refactored for new session guard architecture
 * PHP 5.4 compatible (UwAmp / GoDaddy)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified session management

$page = "profile";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}
?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('learnersNav.php');   ?>

           <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

           <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Alerts & Notifications for  </span> <?php echo $get['name']; ?>
    </h4>

    <div class="row">
        <div class="col-md-12">
		
		<!-- Bootstrap Tabs for Notifications -->
        <ul class="nav nav-pills flex-column flex-md-row mb-3" style="gap: 15px;">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#course_alerts"><i class="bx bx-calendar"></i> Course Alerts</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#assessments"><i class="bx bx-task"></i> Assessments</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#skills"><i class="bx bx-bulb"></i> Skills Development</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#collaboration"><i class="bx bx-group"></i> Collaboration</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#gamification"><i class="bx bx-trophy"></i> Gamification</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#career"><i class="bx bx-briefcase"></i> Career Readiness</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#system"><i class="bx bx-cog"></i> System Alerts</a></li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- 1️⃣ Course Alerts & Reminders -->
            <div class="tab-pane fade show active" id="course_alerts">
                <div class="card">
                    <h5 class="card-header">📆 Course Alerts & Reminders</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>📆 Upcoming Deadlines</td><td>Reminds learners about assignment/exam deadlines.</td></tr>
                                <tr><td>🎯 Course Completion Reminder</td><td>Alerts when a course is nearing completion.</td></tr>
                                <tr><td>⏳ Inactivity Alerts</td><td>Notifies learners if they haven’t accessed a course in X days.</td></tr>
                                <tr><td>📚 New Course Enrollment Confirmation</td><td>Confirms enrollment in a new course.</td></tr>
                                <tr><td>🔔 Course Updates & Announcements</td><td>Alerts on new lessons, resources, or changes.</td></tr>
                                <tr><td>🔄 Recommended Next Steps</td><td>Suggests what learners should focus on next.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2️⃣ Assessment Notifications -->
            <div class="tab-pane fade" id="assessments">
                <div class="card">
                    <h5 class="card-header">📝 Assessment & Performance Notifications</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>📝 Upcoming Assessments</td><td>Reminds learners about upcoming quizzes/exams.</td></tr>
                                <tr><td>🎯 Low Performance Alerts</td><td>Notifies when a learner scores below a threshold.</td></tr>
                                <tr><td>🏆 High Score Achievement</td><td>Recognizes top performance in an assessment.</td></tr>
                                <tr><td>⏳ Assessment Retake Reminder</td><td>Suggests retaking quizzes for improvement.</td></tr>
                                <tr><td>📊 Performance Report Alerts</td><td>Sends periodic progress reports.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


			<!-- 3️⃣ Skills Development & Learning Path Alerts -->
            <div class="tab-pane fade" id="skills">
                <div class="card">
                    <h5 class="card-header">🔥 Skills Development & Learning Path Alerts</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>🔥 New Skills Achieved</td><td>Alerts when learners master a new skill.</td></tr>
                                <tr><td>🎯 Skill Gap Alerts</td><td>Notifies learners about skills they need to improve.</td></tr>
                                <tr><td>🔄 Adaptive Learning Suggestions</td><td>AI-driven alerts on recommended lessons.</td></tr>
                                <tr><td>🏆 Skill Certification Achievements</td><td>Notifies when a certificate is earned.</td></tr>
                                <tr><td>🚀 Milestone Achievements</td><td>Recognizes completion of major learning milestones.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4️⃣ Collaboration & Community Engagement Alerts -->
            <div class="tab-pane fade" id="collaboration">
                <div class="card">
                    <h5 class="card-header">💬 Collaboration & Community Engagement Alerts</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>💬 New Discussion Replies</td><td>Alerts when someone replies to a learner’s post.</td></tr>
                                <tr><td>✅ Peer Review Requests</td><td>Notifies learners when they need to review a peer’s work.</td></tr>
                                <tr><td>🏆 Top Contributor Recognition</td><td>Rewards active learners in forums.</td></tr>
                                <tr><td>📢 Group Project Updates</td><td>Alerts on new tasks assigned in group projects.</td></tr>
                                <tr><td>🤝 Mentor Messages</td><td>Notifies learners of mentor feedback or guidance.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			
			  <!-- 5️⃣ Gamification & Engagement Notifications -->
            <div class="tab-pane fade" id="gamification">
                <div class="card">
                    <h5 class="card-header">🏅 Gamification & Engagement Notifications</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>🏅 New Badges Earned</td><td>Alerts when a learner unlocks a new badge.</td></tr>
                                <tr><td>🏆 Leaderboard Status</td><td>Notifies learners of their rank changes.</td></tr>
                                <tr><td>🔥 Daily Learning Streaks</td><td>Encourages learners to maintain daily study streaks.</td></tr>
                                <tr><td>🎁 XP & Rewards Earned</td><td>Notifies about XP points, incentives, or rewards.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 6️⃣ Career & Job Readiness Alerts -->
            <div class="tab-pane fade" id="career">
                <div class="card">
                    <h5 class="card-header">💼 Career & Job Readiness Alerts</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>💼 Career Path Updates</td><td>Alerts on recommended career tracks.</td></tr>
                                <tr><td>📜 Resume Review Completion</td><td>Notifies learners when a mentor reviews their resume.</td></tr>
                                <tr><td>🔥 Job Market Trends</td><td>Sends insights on industry skills demand.</td></tr>
                                <tr><td>📢 Job/Internship Openings</td><td>Alerts learners about new job opportunities.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 7️⃣ System & Platform Engagement Alerts -->
            <div class="tab-pane fade" id="system">
                <div class="card">
                    <h5 class="card-header">🔔 System & Platform Engagement Alerts</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>🔔 Platform Announcements</td><td>Alerts learners on new features and updates.</td></tr>
                                <tr><td>🔄 Profile Update Reminders</td><td>Reminds learners to update their profile.</td></tr>
                                <tr><td>📊 Weekly Learning Summary</td><td>Provides insights on learning activities.</td></tr>
                                <tr><td>⏳ Session Timeout Warning</td><td>Notifies inactive users before automatic logout.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			
			
        </div> <!-- End of tab-content -->







        </div> <!-- End of col-md-12 -->
    </div> <!-- End of row -->
</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   