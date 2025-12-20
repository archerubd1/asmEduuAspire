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
                    <h4 class="mb-4">🎓 AI-Powered Teaching Tools</h4>
                    <p class="text-muted">Enhance your teaching experience with AI-driven tools for course creation, student engagement, grading automation, and professional growth.</p>

                    <div class="row">
                        <!-- 📚 AI Course Builder -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="../assets/img/instructor/ai_course_builder.jpg" height="200px" class="card-img-top" alt="AI Course Builder">
                                <div class="card-body">
                                    <h5 class="card-title">📚 AI Course Builder</h5>
                                    <p class="card-text">Design interactive and adaptive courses with AI-powered curriculum assistance.</p>
                                    <button class="btn btn-primary" onclick="showInfo('AI Course Builder', 'Create structured courses with AI-generated content, assessments, and interactive elements.')">Learn More</button>
                                    <a href="https://www.coursera.org" target="_blank" class="btn btn-outline-primary">Try Coursera AI</a>
                                </div>
                            </div>
                        </div>

                        <!-- ✍️ AI Grading Assistant -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="../assets/img/instructor/ai_grading_asst.jpg" height="200px" class="card-img-top" alt="AI Grading Assistant">
                                <div class="card-body">
                                    <h5 class="card-title">✍️ AI Grading Assistant</h5>
                                    <p class="card-text">Automate grading, generate feedback, and track student progress efficiently.</p>
                                    <button class="btn btn-success" onclick="showInfo('AI Grading Assistant', 'Use AI to analyze assignments, provide detailed feedback, and assess student performance.')">Learn More</button>
                                    <a href="https://www.turnitin.com" target="_blank" class="btn btn-outline-success">Try Turnitin AI</a>
                                </div>
                            </div>
                        </div>

                        <!-- 🎤 AI Lecture Assistant -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <img src="../assets/img/instructor/ai_lecture_asst.jpg" height="200px" class="card-img-top" alt="AI Lecture Assistant">
                                <div class="card-body">
                                    <h5 class="card-title">🎤 AI Lecture Assistant</h5>
                                    <p class="card-text">Generate lecture summaries, transcripts, and interactive Q&A insights.</p>
                                    <button class="btn btn-info" onclick="showInfo('AI Lecture Assistant', 'Enhance your lectures with AI-powered summaries, transcripts, and engagement tools.')">Learn More</button>
                                    <a href="https://www.otter.ai" target="_blank" class="btn btn-outline-info">Try Otter AI</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ SweetAlert Function for Informational Popups -->
                <script>
                    function showInfo(title, description) {
                        Swal.fire({
                            title: `🎓 ${title}`,
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
