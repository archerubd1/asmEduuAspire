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
                    <h4 class="mb-4">📚 Instructor Hub</h4>
                    <p class="text-muted">Empower your teaching with immersive tools, AI-driven course planning, and interactive assessments.</p>
                    
                    <!-- 📌 Feature Sections -->
                    <div class="row">
                        <!-- Immersive Teaching -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🖥️ Immersive Teaching</h5>
                                    <p class="card-text">Deliver engaging lectures in 3D spaces, conduct virtual science experiments, and host historical walkthroughs.</p>
                                    <button class="btn btn-primary" onclick="showPopup('Immersive Teaching', 'Transform your classroom into a 3D learning experience with real-time interactive content.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- AI Course Planning -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">📊 AI Course Planning</h5>
                                    <p class="card-text">Use AI insights to design personalized course structures and optimize learning outcomes.</p>
                                    <button class="btn btn-success" onclick="showPopup('AI Course Planning', 'Leverage AI-powered analytics to plan lessons, track student progress, and tailor instruction.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- Peer Collaboration -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🤝 Instructor Collaboration</h5>
                                    <p class="card-text">Connect with educators worldwide for resource sharing and virtual co-teaching.</p>
                                    <button class="btn btn-info" onclick="showPopup('Instructor Collaboration', 'Join a network of educators to exchange resources, strategies, and collaborative teaching opportunities.')">Learn More</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row mt-4">
                        <!-- Gamification for Engagement -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🏆 Gamified Learning</h5>
                                    <p class="card-text">Incorporate quizzes, leaderboards, and rewards to boost student engagement.</p>
                                    <button class="btn btn-warning" onclick="showPopup('Gamified Learning', 'Enhance student participation with interactive challenges, badges, and XP-driven learning.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- Career Readiness Workshops -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">💼 Career Preparation</h5>
                                    <p class="card-text">Train students with AI-assisted resume workshops and interview simulations.</p>
                                    <button class="btn btn-dark" onclick="showPopup('Career Preparation', 'Prepare your students for the job market with hands-on career-building exercises.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- Global Teaching Access -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🌍 Global Classroom</h5>
                                    <p class="card-text">Host lectures accessible to students worldwide, breaking geographical barriers.</p>
                                    <button class="btn btn-secondary" onclick="showPopup('Global Classroom', 'Expand your teaching reach by engaging students from different regions through virtual lectures.')">Learn More</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 🔥 Advanced Teaching Tools & Simulations -->
                    <h4 class="mt-5 text-center">🔥 Advanced Teaching Tools & Simulations</h4>
                    <div class="row mt-3">
                        <!-- Virtual Labs -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🔬 Virtual Science Lab</h5>
                                    <p class="card-text">Demonstrate real-time experiments safely in a controlled 3D lab.</p>
                                    <button class="btn btn-danger" onclick="showPopup('Virtual Science Lab', 'Enhance hands-on learning with virtual chemistry, physics, and biology experiments.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- AI Teaching Assistants -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🤖 AI Teaching Assistant</h5>
                                    <p class="card-text">Automate grading, provide AI-driven student feedback, and track performance.</p>
                                    <button class="btn btn-success" onclick="showPopup('AI Teaching Assistant', 'Reduce workload by using AI for grading, tracking student queries, and providing personalized feedback.')">Learn More</button>
                                </div>
                            </div>
                        </div>

                        <!-- Business Case Simulations -->
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">📊 Business Strategy Simulations</h5>
                                    <p class="card-text">Engage students with decision-making case studies and entrepreneurship models.</p>
                                    <button class="btn btn-primary" onclick="showPopup('Business Strategy Simulations', 'Teach business and management through interactive strategy-based simulations.')">Learn More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
// 📌 SweetAlert Popup Function
function showPopup(title, message) {
    Swal.fire({
        title: title,
        text: message,
        icon: "info",
        confirmButtonText: "Got It!"
    });
}
</script>

<?php 
require_once('../platformFooter.php');
?>
