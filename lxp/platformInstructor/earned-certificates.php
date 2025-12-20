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
  
  <!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <h3 class="mb-3">🏆 Instructor Achievements & Recognitions</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Achievement</th>
                <th>Category</th>
                <th>Date Awarded</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Excellence in Teaching -->
            <tr>
                <td>Excellence in Teaching</td>
                <td>📜 Award</td>
                <td>Mar 10, 2025</td>
                <td><span class="badge bg-success">Verified</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Excellence in Teaching', 'Award', 'Mar 10, 2025', 'Verified')">View Details</button>
                </td>
            </tr>

            <!-- Outstanding Mentor -->
            <tr>
                <td>Outstanding Mentor</td>
                <td>🏆 Recognition</td>
                <td>Feb 25, 2025</td>
                <td><span class="badge bg-success">Earned</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Outstanding Mentor', 'Recognition', 'Feb 25, 2025', 'Earned')">View Details</button>
                </td>
            </tr>

            <!-- Curriculum Innovator -->
            <tr>
                <td>Curriculum Innovator</td>
                <td>📜 Award</td>
                <td>Feb 15, 2025</td>
                <td><span class="badge bg-warning">Pending Verification</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Curriculum Innovator', 'Award', 'Feb 15, 2025', 'Pending Verification')">View Details</button>
                </td>
            </tr>

            <!-- Research Contributor -->
            <tr>
                <td>Research Contributor</td>
                <td>🏆 Badge</td>
                <td>Feb 5, 2025</td>
                <td><span class="badge bg-danger">Revoked</span></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="viewAchievement('Research Contributor', 'Badge', 'Feb 5, 2025', 'Revoked')">View Details</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- ✅ SweetAlert Function for Viewing Details -->
<script>
    function viewAchievement(title, category, date, status) {
        Swal.fire({
            title: `🏅 ${title}`,
            html: `
                <strong>Category:</strong> ${category} <br>
                <strong>Date Awarded:</strong> ${date} <br>
                <strong>Status:</strong> <span class="badge bg-${status === 'Verified' || status === 'Earned' ? 'success' : status === 'Pending Verification' ? 'warning' : 'danger'}">${status}</span> <br><br>
                🏆 Download Certificate (if available) or <a href="#">Learn More</a>.
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