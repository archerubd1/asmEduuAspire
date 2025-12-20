<?php
/**
 * Astraal LXP - Instructor Adaptive learning Paths
 * Refactored for new session-guard workflow (PHP 5.4 compatible)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ ensures unified phx_user_* sessions

// Ensure session is active and valid
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

$page = "ganification";
require_once('instructorHead_Nav2.php');

?>

<!-- Layout container -->
<div class="layout-page">
    <?php require_once('instructorNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="container mt-5">
                    <h4 class="mb-4">📚 AI-Powered Teaching Tools</h4>
                    <p class="text-muted">Enhance your instructional methods with AI-driven tools for grading, engagement, and interactive teaching.</p>

                    <div class="row">
                        <!-- ✍️ AI-Assisted Grading -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="../assets/img/instructor/ai_grading.jpg" height="200px" class="card-img-top" alt="AI Grading">
                                <div class="card-body">
                                    <h5 class="card-title">✍️ AI-Assisted Grading</h5>
                                    <p class="card-text">Automate and streamline grading with AI-powered assessment tools.</p>
                                    <button class="btn btn-primary" onclick="showInfo('AI-Assisted Grading', 'Use AI to analyze and grade assignments, providing instant feedback and reducing workload.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- 🎤 Virtual Lecture Rooms -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="../assets/img/instructor/virtual_lecture.jpg" height="200px" class="card-img-top" alt="Virtual Lecture Rooms">
                                <div class="card-body">
                                    <h5 class="card-title">🎤 Virtual Lecture Rooms</h5>
                                    <p class="card-text">Conduct immersive, interactive online lectures with AI enhancements.</p>
                                    <button class="btn btn-success" onclick="showInfo('Virtual Lecture Rooms', 'Host AI-enhanced virtual classes with real-time analytics, automated attendance, and interactive Q&A.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- 🔬 AR/VR Teaching Simulations -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="../assets/img/instructor/ar_vr_teaching.png" height="200px" class="card-img-top" alt="AR/VR Teaching">
                                <div class="card-body">
                                    <h5 class="card-title">🔬 AR/VR Teaching Simulations</h5>
                                    <p class="card-text">Create interactive, immersive lessons using AR and VR technology.</p>
                                    <button class="btn btn-info" onclick="showInfo('AR/VR Teaching Simulations', 'Develop engaging simulations and interactive training scenarios with AR and VR technology.')">Learn More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ SweetAlert Function for Informational Popups -->
                <script>
                    function showInfo(title, description) {
                        Swal.fire({
                            title: `📚 ${title}`,
                            text: description,
                            icon: "info",
                            confirmButtonText: "Got It!"
                        });
                    }
                </script>
            </div>
        </div>
    </div>


<?php 
require_once('../platformFooter.php');
?>
