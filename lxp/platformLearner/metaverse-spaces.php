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
 <div class="card"> 
<div class="container mt-5">
    <h4 class="mb-4 ">🌐 Metaverse Learning Hub</h4>
    <p class="text-muted">Experience immersive learning, virtual labs, AI-driven learning paths, and career simulations.</p>

    <!-- 📌 Feature Sections -->
    <div class="row">
        <!-- Immersive Learning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
               <!---  <img src="../assets/img/immersive-learning.png" class="card-img-top" alt="Immersive Learning">   --->
                <div class="card-body">
                    <h5 class="card-title">🎓 Immersive Learning</h5>
                    <p class="card-text">Experience history, science, and business case studies in 3D environments.</p>
                    <button class="btn btn-primary" onclick="showPopup('Immersive Learning', 'Step into historical events, conduct virtual science experiments, and explore real-world business case studies in an interactive 3D space.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- Personalized Learning Paths -->
        <div class="col-md-4">
            <div class="card shadow-sm">
               
                <div class="card-body">
                    <h5 class="card-title">🧠 Personalized AI Learning</h5>
                    <p class="card-text">AI adapts lessons based on your progress and interests.</p>
                    <button class="btn btn-success" onclick="showPopup('Personalized AI Learning', 'AI-driven learning adapts to your skills, suggesting customized courses and interactive challenges to maximize growth.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- Social & Peer Collaboration -->
        <div class="col-md-4">
            <div class="card shadow-sm">
             <!---   <img src="peer_collaboration.jpg" class="card-img-top" alt="Peer Collaboration"> --->
                <div class="card-body">
                    <h5 class="card-title">🤝 3D Collaboration</h5>
                    <p class="card-text">Join global learners in virtual whiteboard brainstorming & discussions.</p>
                    <button class="btn btn-info" onclick="showPopup('3D Collaboration', 'Engage with peers worldwide in virtual study rooms, whiteboard brainstorming sessions, and immersive group projects.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- Gamification -->
        <div class="col-md-4">
            <div class="card shadow-sm">
<!--- <img src="gamification.jpg" class="card-img-top" alt="Gamification"> --->
                <div class="card-body">
                    <h5 class="card-title">🏆 Gamification & XP</h5>
                    <p class="card-text">Earn badges, XP points, and climb leaderboards for learning achievements.</p>
                    <button class="btn btn-warning" onclick="showPopup('Gamification & XP', 'Earn XP points, unlock badges, and climb leaderboards by completing challenges, quizzes, and collaborative tasks.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- Career Readiness -->
        <div class="col-md-4">
            <div class="card shadow-sm">
           <!---      <img src="career_readiness.jpg" class="card-img-top" alt="Career Readiness"> --->
                <div class="card-body">
                    <h5 class="card-title">💼 Career Readiness</h5>
                    <p class="card-text">Simulate job interviews, resume building, and workplace scenarios.</p>
                    <button class="btn btn-dark" onclick="showPopup('Career Readiness', 'Prepare for your future career with AI-driven resume workshops, mock job interviews, and real-world workplace scenarios.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- Global Access -->
        <div class="col-md-4">
            <div class="card shadow-sm">
           <!---      <img src="global_learning.jpg" class="card-img-top" alt="Global Learning"> --->
                <div class="card-body">
                    <h5 class="card-title">🌍 Global Access</h5>
                    <p class="card-text">Join top universities and virtual campus tours worldwide.</p>
                    <button class="btn btn-secondary" onclick="showPopup('Global Learning', 'Access top universities globally, attend live VR lectures, and participate in virtual campus tours.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 Featured Events & VR Classrooms -->
    <h4 class="mt-5 text-center">🔥 Featured Virtual Events & VR Classrooms</h4>
    <div class="row mt-3">
        <!-- VR Chemistry Lab -->
        <div class="col-md-4">
            <div class="card shadow-sm">
          <!---       <img src="chemistry_lab.jpg" class="card-img-top" alt="VR Chemistry Lab"> --->
                <div class="card-body">
                    <h5 class="card-title">🔬 VR Chemistry Lab</h5>
                    <p class="card-text">Conduct safe, real-time chemistry experiments in a 3D lab.</p>
                    <button class="btn btn-danger" onclick="showPopup('VR Chemistry Lab', 'Perform virtual chemistry experiments safely, mixing elements and observing real-time reactions.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- AI Mock Interviews -->
        <div class="col-md-4">
            <div class="card shadow-sm">
           <!---      <img src="mock_interview.jpg" class="card-img-top" alt="Mock Interviews">   --->
                <div class="card-body">
                    <h5 class="card-title">🎤 AI Mock Interviews</h5>
                    <p class="card-text">Practice real-world job interviews with AI-based feedback.</p>
                    <button class="btn btn-success" onclick="showPopup('AI Mock Interviews', 'Enhance your interview skills with AI-driven feedback, realistic scenarios, and practice sessions.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- Virtual Business Simulation -->
        <div class="col-md-4">
            <div class="card shadow-sm">
           <!---     <img src="business_simulation.jpg" class="card-img-top" alt="Business Simulation">    --->
                <div class="card-body">
                    <h5 class="card-title">📊 Virtual Business Simulation</h5>
                    <p class="card-text">Make decisions in real-world business case scenarios.</p>
                    <button class="btn btn-primary" onclick="showPopup('Virtual Business Simulation', 'Test your business acumen with real-world simulations, strategy development, and decision-making exercises.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</div>
<p><br><br>
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






</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   