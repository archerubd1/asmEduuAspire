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
    <h3 class="mb-3">🏅 Earned Certificates & Badges</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Achievement</th>
                <th>Category</th>
                <th>Date Earned</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Certificate Row -->
            <tr>
                <td>Data Science Mastery</td>
                <td>📜 Certification</td>
                <td>Feb 20, 2025</td>
                <td><span class="badge bg-success">Verified</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Data Science Mastery', 'Certification', 'Feb 20, 2025', 'Verified')">View Status</button>
                </td>
            </tr>

            <!-- Badge Row -->
            <tr>
                <td>AI Problem Solver</td>
                <td>🏆 Badge</td>
                <td>Feb 15, 2025</td>
                <td><span class="badge bg-success">Earned</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('AI Problem Solver', 'Badge', 'Feb 15, 2025', 'Earned')">View Status</button>
                </td>
            </tr>

            <!-- Certificate Row -->
            <tr>
                <td>Python for Beginners</td>
                <td>📜 Certification</td>
                <td>Feb 10, 2025</td>
                <td><span class="badge bg-warning">Pending Verification</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Python for Beginners', 'Certification', 'Feb 10, 2025', 'Pending Verification')">View Status</button>
                </td>
            </tr>

            <!-- Badge Row -->
            <tr>
                <td>Top Contributor</td>
                <td>🏆 Badge</td>
                <td>Feb 5, 2025</td>
                <td><span class="badge bg-danger">Revoked</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Top Contributor', 'Badge', 'Feb 5, 2025', 'Revoked')">View Status</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- ✅ SweetAlert Function for Viewing Status -->
<script>
    function viewAchievement(title, category, date, status) {
        Swal.fire({
            title: `🏅 ${title}`,
            html: `
                <strong>Category:</strong> ${category} <br>
                <strong>Date Earned:</strong> ${date} <br>
                <strong>Status:</strong> <span class="badge bg-${status === 'Verified' || status === 'Earned' ? 'success' : status === 'Pending Verification' ? 'warning' : 'danger'}">${status}</span> <br><br>
                📜 Download Certificate (if available) or <a href="#">Learn More</a>.
            `,
            icon: status === "Verified" || status === "Earned" ? "success" : status === "Pending Verification" ? "warning" : "error",
            confirmButtonText: "Close"
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
   