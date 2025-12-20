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
  <div class="container mt-5">
    <h3 class="mb-3">📊 Learner Feedback Dashboard</h3>
    <p class="text-muted">Provide feedback on your learning experience, rate courses, and suggest improvements.</p>

    <!-- Feedback Options -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6><i class="bx bx-message-detail"></i> Submit Feedback</h6>
                    <p class="text-muted">Choose feedback type and share your thoughts.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitFeedbackModal">Submit Feedback</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6><i class="bx bx-history"></i> View Past Feedback</h6>
                    <p class="text-muted">See previous feedback and ratings.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#pastFeedbackModal">View Feedback</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Feedback Modal -->
<div class="modal fade" id="submitFeedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📩 Submit Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <!-- Select Feedback Type -->
                    <div class="mb-3">
                        <label class="form-label">📌 Feedback Type</label>
                        <select class="form-select">
                            <option>General Learning Experience</option>
                            <option>Specific Course Feedback</option>
                            <option>Instructor Feedback</option>
                            <option>Platform & UI Experience</option>
                        </select>
                    </div>

                    <!-- Rate Experience -->
                    <div class="mb-3">
                        <label class="form-label">⭐ Rate Your Experience</label>
                        <select class="form-select">
                            <option>⭐ 1 - Poor</option>
                            <option>⭐⭐ 2 - Fair</option>
                            <option>⭐⭐⭐ 3 - Good</option>
                            <option>⭐⭐⭐⭐ 4 - Very Good</option>
                            <option>⭐⭐⭐⭐⭐ 5 - Excellent</option>
                        </select>
                    </div>

                    <!-- Positive Aspects -->
                    <div class="mb-3">
                        <label class="form-label">✅ What Did You Like?</label>
                        <textarea class="form-control" rows="2" placeholder="Share positive aspects..."></textarea>
                    </div>

                    <!-- Suggestions for Improvement -->
                    <div class="mb-3">
                        <label class="form-label">🔄 Suggestions for Improvement</label>
                        <textarea class="form-control" rows="2" placeholder="Suggest improvements..."></textarea>
                    </div>

                    <button type="button" class="btn btn-primary">Submit Feedback</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Past Feedback Modal -->
<div class="modal fade" id="pastFeedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📜 Past Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Rating</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-02-20</td>
                            <td>Course Feedback</td>
                            <td>⭐⭐⭐⭐</td>
                            <td>Great content, but would like more interactive exercises.</td>
                        </tr>
                        <tr>
                            <td>2024-01-15</td>
                            <td>Platform Experience</td>
                            <td>⭐⭐⭐⭐⭐</td>
                            <td>Excellent course structure and engaging instructors.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>






</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   