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
    <h4 class="mb-4">🕶️ AR/VR Learning Preferences</h4>
    <p class="text-muted">Customize your immersive learning experience with AR, VR, and metaverse settings.</p>

    <div class="row">
        <!-- 🎓 Enable VR Learning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/vr_learning.jpg"height="200px" class="card-img-top" alt="VR Learning">
                <div class="card-body">
                    <h5 class="card-title">🎓 Enable VR Learning</h5>
                    <p class="card-text">Experience lessons in a fully immersive virtual environment.</p>
                    <button class="btn btn-primary" onclick="showInfo('Enable VR Learning', 'Turn on virtual reality learning for courses, interactive training, and simulations.')">Learn More</button>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="vrLearning">
                        <label class="form-check-label" for="vrLearning">Enable VR Learning</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔍 Enable AR Learning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ar_learning.jpg" height="200px"class="card-img-top" alt="AR Learning">
                <div class="card-body">
                    <h5 class="card-title">🔍 Enable AR Learning</h5>
                    <p class="card-text">Enhance real-world learning with interactive AR overlays.</p>
                    <button class="btn btn-success" onclick="showInfo('Enable AR Learning', 'Turn on augmented reality for interactive lessons, 3D models, and real-world applications.')">Learn More</button>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="arLearning">
                        <label class="form-check-label" for="arLearning">Enable AR Learning</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🌐 Enable Metaverse Learning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/metaverse_learning.jpg" height="200px"class="card-img-top" alt="Metaverse Learning">
                <div class="card-body">
                    <h5 class="card-title">🌐 Enable Metaverse Learning</h5>
                    <p class="card-text">Engage in immersive, AI-driven metaverse classrooms.</p>
                    <button class="btn btn-info" onclick="showInfo('Enable Metaverse Learning', 'Join AI-powered 3D learning spaces, virtual labs, and metaverse-based study groups.')">Learn More</button>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="metaverseLearning">
                        <label class="form-check-label" for="metaverseLearning">Enable Metaverse Learning</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- 🏗️ Enable Virtual Labs -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/virtual_lab.jpeg" height="200px"class="card-img-top" alt="Virtual Labs">
                <div class="card-body">
                    <h5 class="card-title">🏗️ Enable Virtual Labs</h5>
                    <p class="card-text">Conduct physics, chemistry, and engineering experiments safely in a virtual lab.</p>
                    <button class="btn btn-warning" onclick="showInfo('Enable Virtual Labs', 'Perform experiments in physics, chemistry, and engineering through AI-powered virtual simulations.')">Learn More</button>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="virtualLabs">
                        <label class="form-check-label" for="virtualLabs">Enable Virtual Labs</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🎭 Enable 3D Interactive Simulations -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/3d_simulations.jpeg"height="200px" class="card-img-top" alt="3D Interactive Simulations">
                <div class="card-body">
                    <h5 class="card-title">🎭 Enable 3D Interactive Simulations</h5>
                    <p class="card-text">Engage in interactive 3D role-playing for business, law, or medicine.</p>
                    <button class="btn btn-dark" onclick="showInfo('Enable 3D Interactive Simulations', 'Practice role-playing in law, business, or medical case studies through immersive 3D environments.')">Learn More</button>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="interactiveSimulations">
                        <label class="form-check-label" for="interactiveSimulations">Enable 3D Simulations</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🚀 Enable Gamified Learning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/gamified_learning.png" height="200px"class="card-img-top" alt="Gamified Learning">
                <div class="card-body">
                    <h5 class="card-title">🚀 Enable Gamified Learning</h5>
                    <p class="card-text">Turn learning into an engaging challenge with XP, badges, and rewards.</p>
                    <button class="btn btn-danger" onclick="showInfo('Enable Gamified Learning', 'Enable badges, leaderboards, and XP points to make learning competitive and fun.')">Learn More</button>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="gamifiedLearning">
                        <label class="form-check-label" for="gamifiedLearning">Enable Gamified Learning</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert Function for Informational Popups -->
<script>
    function showInfo(title, description) {
        Swal.fire({
            title: `🕶️ ${title}`,
            text: description,
            icon: "info",
            confirmButtonText: "Got It!"
        });
    }
</script>



<p><br><p><br>  
</div>





</div>








</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   