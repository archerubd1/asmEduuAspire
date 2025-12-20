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
  <div class="container mt-4">
    <h3 class="mb-3">📋 Mentorship Requests Status</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Date Sent</th>
                <th>Mentor Name</th>
                <th>Mentorship Topic</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Feb 20, 2025</td>
                <td>Dr. Alice Johnson</td>
                <td>Data Science & AI</td>
                <td><span class="badge bg-warning">Pending</span></td>
            </tr>
            <tr>
                <td>Feb 15, 2025</td>
                <td>Mr. Robert Smith</td>
                <td>Leadership & Management</td>
                <td><span class="badge bg-success">Approved</span></td>
            </tr>
            <tr>
                <td>Feb 10, 2025</td>
                <td>Ms. Sarah Lee</td>
                <td>Software Development</td>
                <td><span class="badge bg-danger">Rejected</span></td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<p><br>

<div class="card">

<div class="container mt-5">
    <h3 class="mb-3">✉️ Request a Mentor</h3>
    <form>
        <!-- Mentor Selection -->
        <div class="mb-3">
            <label class="form-label">Select Mentor:</label>
            <select class="form-select">
                <option selected disabled>Choose a Mentor</option>
                <option>Dr. Alice Johnson - Data Science</option>
                <option>Mr. Robert Smith - Leadership</option>
                <option>Ms. Sarah Lee - Software Development</option>
            </select>
        </div>

        <!-- Mentorship Topic -->
        <div class="mb-3">
            <label class="form-label">Mentorship Topic:</label>
            <input type="text" class="form-control" placeholder="e.g., Python for AI, Public Speaking">
        </div>

        <!-- Preferred Date -->
        <div class="mb-3">
            <label class="form-label">Preferred Date:</label>
            <input type="date" class="form-control">
        </div>

        <!-- Additional Comments -->
        <div class="mb-3">
            <label class="form-label">Additional Comments:</label>
            <textarea class="form-control" rows="3" placeholder="Add any extra details..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">📨 Send Request</button>
    </form>
</div>
</div>








</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   