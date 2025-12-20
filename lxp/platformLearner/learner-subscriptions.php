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
        <span class="text-muted fw-light">Subscriptions At a Glance for  </span> <?php echo $get['name']; ?>
    </h4>

    <div class="row">
        <div class="col-md-12">
		
		<!-- Bootstrap Tabs for Subscription Categories -->
        <ul class="nav nav-pills flex-column flex-md-row mb-3" style="gap: 15px;">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#general"><i class="bx bx-list-check"></i> General Overview</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#courses"><i class="bx bx-book"></i> Course Subscriptions</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#learning"><i class="bx bx-laptop"></i> Learning & Hands-on</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tech"><i class="bx bx-cube"></i> Advanced Tech</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#analytics"><i class="bx bx-line-chart"></i> Analytics & Recommendations</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#engagement"><i class="bx bx-time"></i> Engagement Metrics</a></li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- 1️⃣ General Subscription Overview -->
            <div class="tab-pane fade show active" id="general">
                <div class="card">
                    <h5 class="card-header">📝 General Subscription Overview</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>📝 Total Subscriptions</td><td>Number of courses and services learner has subscribed to.</td></tr>
                                <tr><td>📆 Subscription Start Date</td><td>When the learner first subscribed.</td></tr>
                                <tr><td>⏳ Subscription Duration</td><td>Time remaining for active subscriptions.</td></tr>
                                <tr><td>🚀 Renewal Status</td><td>Auto-renewal enabled/disabled.</td></tr>
                                <tr><td>💳 Subscription Payment Status</td><td>Paid, free, trial, or pending payment.</td></tr>
                                <tr><td>🔄 Subscription Type</td><td>Monthly, yearly, lifetime, or one-time purchase.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2️⃣ Course Subscriptions by Category -->
            <div class="tab-pane fade" id="courses">
                <div class="card">
                    <h5 class="card-header">📚 Course Subscriptions</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Course Category</th><th>Data Points to Track</th></tr></thead>
                            <tbody>
                                <tr><td>📚 Active Courses</td><td>Number of ongoing courses.</td></tr>
                                <tr><td>🔥 Curated Courses</td><td>High-quality courses selected for the learner.</td></tr>
                                <tr><td>⚡ Skills Booster Courses</td><td>Short-term courses focused on skill development.</td></tr>
                                <tr><td>🎯 LevelUp Courses</td><td>Advanced courses for higher expertise.</td></tr>
                                <tr><td>⭐ Crowd Favorites</td><td>Popular courses with highest engagement.</td></tr>
                                <tr><td>🌍 Marketplace Courses</td><td>Courses from third-party providers.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3️⃣ Learning & Hands-on Subscriptions -->
            <div class="tab-pane fade" id="learning">
                <div class="card">
                    <h5 class="card-header">💻 Learning Experience & Hands-on Subscriptions</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Feature</th><th>Data Points to Track</th></tr></thead>
                            <tbody>
                                <tr><td>💻 Programming Labs</td><td>Number of active lab environments.</td></tr>
                                <tr><td>🏗 Projects</td><td>Number of real-world projects enrolled in.</td></tr>
                                <tr><td>🎓 Mentorship</td><td>Number of mentorship sessions booked.</td></tr>
                                <tr><td>🏢 Internships</td><td>Number of internship opportunities applied for.</td></tr>
                                <tr><td>📜 Skills & Compliance</td><td>Compliance certifications subscribed to.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4️⃣ Advanced Technologies -->
            <div class="tab-pane fade" id="tech">
                <div class="card">
                    <h5 class="card-header">🕶 Advanced Technologies Subscriptions</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Feature</th><th>Data Points to Track</th></tr></thead>
                            <tbody>
                                <tr><td>🕶 AR/VR Courses & Labs</td><td>Number of AR/VR-based courses subscribed to.</td></tr>
                                <tr><td>🔗 Blockchain Courses</td><td>Courses focusing on blockchain and cryptocurrency.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

			
            <!-- 5️⃣ Analytics, Insights & Recommendations -->
            <div class="tab-pane fade" id="analytics">
                <div class="card">
                    <h5 class="card-header">📊 Analytics, Insights & Recommendations</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Feature</th><th>Data Points to Track</th></tr></thead>
                            <tbody>
                                <tr><td>📊 Personalized Recommendations</td><td>AI-suggested courses based on learner behavior.</td></tr>
                                <tr><td>🎯 Skill Gap Analysis</td><td>Recommended courses based on missing skills.</td></tr>
                                <tr><td>🔍 Learning Path Tracking</td><td>Progress in AI-driven learning paths.</td></tr>
                                <tr><td>⭐ Top-Rated Courses Subscribed</td><td>Courses with highest learner ratings.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 6️⃣ Subscription-Based Engagement Metrics -->
            <div class="tab-pane fade" id="engagement">
                <div class="card">
                    <h5 class="card-header">📈 Subscription-Based Engagement Metrics</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Metric</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>⏰ Average Time Spent per Course</td><td>Engagement level in each subscription.</td></tr>
                                <tr><td>🎯 Completion Rate per Subscription</td><td>Percentage of completion.</td></tr>
                                <tr><td>📆 Last Accessed Date</td><td>The most recent login to a subscribed course.</td></tr>
                                <tr><td>📈 Weekly/Monthly Active Learning Hours</td><td>Total study time per subscription.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>





        </div> <!-- End of col-md-12 -->
    </div> <!-- End of row -->
</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   