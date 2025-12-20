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
          
		  
		<?php require_once('instructorNav.php');   ?>

           <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

           <div class="container-xxl flex-grow-1 container-p-y">
 <div class="card">
  
  <!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3 class="mb-3 text-center">🏫 Instructor Credentials Management</h3>
    <p class="text-muted text-center">Issue, verify, and manage blockchain-powered certifications & credentials for students.</p>

    <div class="row">
        <!-- 🎓 Issue Blockchain Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/instructor/issue_credentials.jpg" height="200px" class="card-img-top" alt="Issue Credentials">
                <div class="card-body">
                    <h5 class="card-title">🎓 Issue Credentials</h5>
                    <p class="card-text">Generate and issue blockchain-based certificates & badges for your students.</p>
                    <button class="btn btn-primary" onclick="showInfo('Issue Credentials', 'Create tamper-proof blockchain certificates for verified student achievements.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 🔍 Verify Student Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/verify_cred.jpg" height="200px" class="card-img-top" alt="Verify Credentials">
                <div class="card-body">
                    <h5 class="card-title">🔍 Verify Credentials</h5>
                    <p class="card-text">Authenticate student certifications instantly and prevent fraud.</p>
                    <button class="btn btn-success" onclick="showInfo('Verify Credentials', 'Confirm blockchain-backed certifications are legitimate and match student achievements.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 📂 Manage Issued Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/instructor/manage_credentials.jpg" height="200px" class="card-img-top" alt="Manage Credentials">
                <div class="card-body">
                    <h5 class="card-title">📂 Manage Credentials</h5>
                    <p class="card-text">Track issued certificates and monitor student achievements.</p>
                    <button class="btn btn-info" onclick="showInfo('Manage Credentials', 'View and manage all issued credentials in a secure, organized dashboard.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- 🏆 Award Badges & Micro-Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/instructor/award_badges.jpg" height="200px" class="card-img-top" alt="Award Badges">
                <div class="card-body">
                    <h5 class="card-title">🏆 Award Badges</h5>
                    <p class="card-text">Recognize student progress with blockchain-backed micro-credentials.</p>
                    <button class="btn btn-warning" onclick="showInfo('Award Badges', 'Give students verifiable achievements to showcase their skills and knowledge.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 🔗 Share Issued Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/instructor/share_credentials.jpg" height="200px" class="card-img-top" alt="Share Credentials">
                <div class="card-body">
                    <h5 class="card-title">🔗 Share Credentials</h5>
                    <p class="card-text">Allow students to share their certified achievements effortlessly.</p>
                    <button class="btn btn-dark" onclick="showInfo('Share Credentials', 'Enable students to showcase their blockchain-backed certificates on LinkedIn and job platforms.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 📊 Track Credential Engagement -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/instructor/track_usage.jpg" height="200px" class="card-img-top" alt="Track Engagement">
                <div class="card-body">
                    <h5 class="card-title">📊 Track Engagement</h5>
                    <p class="card-text">Monitor how students use their credentials for jobs & education.</p>
                    <button class="btn btn-secondary" onclick="showInfo('Track Engagement', 'View reports on how students share and utilize their credentials in real-world applications.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert Function for Informational Popups -->
<script>
    function showInfo(title, description) {
        Swal.fire({
            title: `🏫 ${title}`,
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
