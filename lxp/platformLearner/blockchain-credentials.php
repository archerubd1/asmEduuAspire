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
  
  <!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3 class="mb-3 text-center">🔗 Blockchain Credentials Dashboard</h3>
    <p class="text-muted text-center">Securely manage, verify, and share your blockchain-powered certificates & badges.</p>

    <div class="row">
        <!-- 📜 Earn & Store Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/earn_store_cred.png"  height="200px"class="card-img-top" alt="Store Credentials">
                <div class="card-body">
                    <h5 class="card-title">📜 Earn & Store Credentials</h5>
                    <p class="card-text">Securely store your certifications, badges, and credentials in your digital wallet.</p>
                    <button class="btn btn-primary" onclick="showInfo('Earn & Store Credentials', 'Store blockchain-powered certificates & badges in a tamper-proof, permanent digital wallet.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 💼 Share Credentials with Employers -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/share_cred.png" height="200px"class="card-img-top" alt="Share Credentials">
                <div class="card-body">
                    <h5 class="card-title">💼 Share Credentials</h5>
                    <p class="card-text">Easily share your credentials with employers, universities, and social networks.</p>
                    <button class="btn btn-success" onclick="showInfo('Share Credentials', 'Generate secure links to share verified blockchain credentials with employers, LinkedIn, and job applications.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 🎓 Verify Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/verify_cred.jpg" height="200px"class="card-img-top" alt="Verify Credentials">
                <div class="card-body">
                    <h5 class="card-title">🎓 Verify Credentials</h5>
                    <p class="card-text">Instantly verify the authenticity and ownership of your credentials.</p>
                    <button class="btn btn-info" onclick="showInfo('Verify Credentials', 'Employers & universities can instantly verify authenticity without intermediaries, reducing fraud.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- 🔄 Transfer & Stack Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/transfer_cred.jpg" height="200px"class="card-img-top" alt="Transfer Credentials">
                <div class="card-body">
                    <h5 class="card-title">🔄 Transfer & Stack Credentials</h5>
                    <p class="card-text">Build a lifelong learning profile with stackable certifications.</p>
                    <button class="btn btn-warning" onclick="showInfo('Transfer & Stack Credentials', 'Transfer micro-credentials across universities & employers, stacking them into larger certifications.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 🔑 Monetize Credentials -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/monetize_money.jpg"height="200px" class="card-img-top" alt="Monetize Credentials">
                <div class="card-body">
                    <h5 class="card-title">🔑 Monetize Credentials</h5>
                    <p class="card-text">Unlock exclusive jobs, scholarships, and career perks using your credentials.</p>
                    <button class="btn btn-dark" onclick="showInfo('Monetize Credentials', 'Use blockchain-backed credentials for job opportunities, scholarships, and career perks.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 📂 Digital Wallet -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/digi_wallet.jpg" height="200px"class="card-img-top" alt="Digital Wallet">
                <div class="card-body">
                    <h5 class="card-title">📂 Digital Wallet</h5>
                    <p class="card-text">Store and manage all your blockchain credentials in one secure place.</p>
                    <button class="btn btn-secondary" onclick="showInfo('Digital Wallet', 'A secure storage where learners can organize blockchain-powered credentials.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row -->
    <div class="row mt-4">
        <!-- 🛡 Credential Verification Portal -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/verify_portal.jpg" height="200px"class="card-img-top" alt="Verification Portal">
                <div class="card-body">
                    <h5 class="card-title">🛡 Verification Portal</h5>
                    <p class="card-text">Employers & institutions can verify credentials with a single click.</p>
                    <button class="btn btn-primary" onclick="showInfo('Verification Portal', 'A dedicated portal where organizations can verify blockchain credentials instantly.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 🔌 API Integration -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/api_integration.jpg" height="200px"class="card-img-top" alt="API Integration">
                <div class="card-body">
                    <h5 class="card-title">🔌 API Integration</h5>
                    <p class="card-text">Seamlessly integrate credentials into recruitment & education systems.</p>
                    <button class="btn btn-success" onclick="showInfo('API Integration', 'HR systems & universities can fetch verified blockchain credentials for instant validation.')">Learn More</button>
                </div>
            </div>
        </div>

        <!-- 📜 Credential Tracking -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/credentials_track.jpg" height="200px"class="card-img-top" alt="Tracking">
                <div class="card-body">
                    <h5 class="card-title">📜 Credential Tracking</h5>
                    <p class="card-text">See verification logs & track where credentials are used.</p>
                    <button class="btn btn-danger" onclick="showInfo('Credential Tracking', 'Learners can track verification logs to see who accessed their credentials and when.')">Learn More</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert Function for Informational Popups -->
<script>
    function showInfo(title, description) {
        Swal.fire({
            title: `🔗 ${title}`,
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
   