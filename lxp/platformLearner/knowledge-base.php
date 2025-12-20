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
  <div class="container mt-5">
   
   
   
   <div class="container mt-5">
    <h2 class="mb-3">📖 EduuAspire Knowledge Base</h2>
    <p class="text-muted">Find answers, guides, and best practices for your learning journey.</p>

    <!-- 🔍 Search Bar -->
    <div class="input-group mb-4">
        <input type="text" class="form-control" placeholder="Search for articles..." id="searchInput">
        <button class="btn btn-primary">🔍 Search</button>
    </div>

    <div class="row">
        <!-- 📚 Categories Section -->
        <div class="col-md-8">
            <h4 class="mb-3">📂 Browse Categories</h4>
            <div class="accordion" id="knowledgeBaseAccordion">

                <!-- 1️⃣ Getting Started -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#gettingStarted">
                            🚀 Getting Started
                        </button>
                    </h2>
                    <div id="gettingStarted" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item">📚 <a href="#">How to Sign Up and Log In?</a></li>
                                <li class="list-group-item">🎯 <a href="#">Setting Up Your Learning Goals</a></li>
                                <li class="list-group-item">🔄 <a href="#">Understanding Course Categories</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 2️⃣ Course Management -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#courseManagement">
                            📚 Course Management
                        </button>
                    </h2>
                    <div id="courseManagement" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item">📥 <a href="#">Enrolling in a Course</a></li>
                                <li class="list-group-item">⏳ <a href="#">Tracking Course Progress</a></li>
                                <li class="list-group-item">✅ <a href="#">Completing & Earning Certifications</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 3️⃣ Assessments & Exams -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#assessments">
                            📝 Assessments & Exams
                        </button>
                    </h2>
                    <div id="assessments" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item">🧠 <a href="#">How to Attempt a Quiz?</a></li>
                                <li class="list-group-item">🎯 <a href="#">Understanding Grading & Scores</a></li>
                                <li class="list-group-item">🔄 <a href="#">Retaking an Assessment</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 4️⃣ Certifications & Badges -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#certifications">
                            🏆 Certifications & Badges
                        </button>
                    </h2>
                    <div id="certifications" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item">🏅 <a href="#">How to Earn Certifications?</a></li>
                                <li class="list-group-item">📜 <a href="#">Downloading Your Certificates</a></li>
                                <li class="list-group-item">🔗 <a href="#">Sharing Certifications on LinkedIn</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 5️⃣ Troubleshooting & Support -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#support">
                            🛠 Troubleshooting & Support
                        </button>
                    </h2>
                    <div id="support" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <ul class="list-group">
                                <li class="list-group-item">📧 <a href="#">Contacting Support</a></li>
                                <li class="list-group-item">🔄 <a href="#">Resetting Your Password</a></li>
                                <li class="list-group-item">🚨 <a href="#">Reporting a Bug</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ⭐ Most Popular Articles -->
        <div class="col-md-4">
            <h4 class="mb-3">⭐ Most Popular Articles</h4>
            <ul class="list-group">
                <li class="list-group-item">📚 <a href="#">How to Enroll in a Course?</a></li>
                <li class="list-group-item">🏆 <a href="#">How to Earn a Certificate?</a></li>
                <li class="list-group-item">🔑 <a href="#">Resetting Your Password</a></li>
                <li class="list-group-item">📝 <a href="#">Understanding Grading System</a></li>
            </ul>

            <!-- 📅 Recently Updated -->
            <h4 class="mt-4">📅 Recently Updated</h4>
            <ul class="list-group">
                <li class="list-group-item">🔄 <a href="#">New Course Categories Explained</a></li>
                <li class="list-group-item">💡 <a href="#">Upcoming Features & Updates</a></li>
                <li class="list-group-item">🆕 <a href="#">New Certification Programs</a></li>
            </ul>
        </div>
    </div>
</div>





</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   