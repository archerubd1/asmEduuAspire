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
    <h2 class="mb-4">📄 Peer Review Dashboard</h2>
    <p class="text-muted">Submit assignments for peer review, evaluate others' work, and track feedback.</p>

    <!-- 🔍 Search & Request Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <input type="text" class="form-control w-50" placeholder="Search reviews...">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newReviewModal">+ Request Peer Review</button>
    </div>

    <!-- 📌 Review Status Overview -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🕒 Pending Reviews</h5>
                    <p class="text-muted">Assignments awaiting peer feedback.</p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#pendingReviewsModal">View Pending</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">✅ Completed Reviews</h5>
                    <p class="text-muted">Assignments that have been reviewed.</p>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completedReviewsModal">View Completed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 Featured Reviews -->
    <h4 class="mt-5">🔥 Top Peer Reviews</h4>
    <div class="row mt-3">
        <!-- Review 1 -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📖 Python Data Science Project</h5>
                    <p class="text-muted">Submitted by <strong>John Doe</strong></p>
                    <p>Looking for feedback on my pandas and NumPy implementation.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal1">View Review</button>
                </div>
            </div>
        </div>

        <!-- Review 2 -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🚀 Web Dev Portfolio Review</h5>
                    <p class="text-muted">Reviewed by <strong>Sarah Lee</strong></p>
                    <p>Comprehensive feedback and UI/UX suggestions included.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal2">View Review</button>
                </div>
            </div>
        </div>
    </div>
</div>
<p><br>
</div>

<!-- 📌 Request a New Peer Review Modal -->
<div class="modal fade" id="newReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Request Peer Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">📌 Assignment Title</label>
                        <input type="text" class="form-control" placeholder="Enter title">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">📂 Select Category</label>
                        <select class="form-select">
                            <option>📖 Course Assignments</option>
                            <option>🚀 Final Projects</option>
                            <option>💡 Coding Challenges</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">📎 Upload File (Optional)</label>
                        <input type="file" class="form-control">
                    </div>

                    <button type="button" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 📝 Peer Review Modal (Pending) -->
<div class="modal fade" id="reviewModal1" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📖 Python Data Science Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>John Doe:</strong> Looking for feedback on pandas and NumPy implementation.</p>
                <hr>
                <h6>💬 Peer Feedback</h6>
                <p><strong>Sarah Lee:</strong> Logic is great, but loops could be optimized.</p>
                <p><strong>Mentor:</strong> Consider using vectorized operations.</p>

                <hr>
                <h6>➕ Add Your Review</h6>
                <textarea class="form-control" rows="2" placeholder="Write a review..."></textarea>
                <button class="btn btn-success mt-2">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<!-- 📝 Peer Review Modal (Completed) -->
<div class="modal fade" id="reviewModal2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🚀 Web Dev Portfolio Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Sarah Lee:</strong> Comprehensive feedback provided.</p>
                <hr>
                <h6>⭐ Final Rating</h6>
                <p>Overall Rating: <strong>4.5/5</strong></p>
                <p>Peer Comments: "Great layout and responsiveness!"</p>

                <button class="btn btn-outline-secondary">View Full Feedback</button>
            </div>
        </div>
    </div>
</div>

<!-- 🕒 Pending Reviews Modal -->
<div class="modal fade" id="pendingReviewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🕒 Pending Peer Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Submitted By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>📖 Python Data Science Project</td>
                            <td>John Doe</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal1">Review</button>
                            </td>
                        </tr>
                        <tr>
                            <td>🚀 Machine Learning Model Evaluation</td>
                            <td>Emily Smith</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm">Review</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Completed Reviews Modal -->
<div class="modal fade" id="completedReviewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✅ Completed Peer Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Submitted By</th>
                            <th>Final Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>🚀 Web Dev Portfolio Review</td>
                            <td>Sarah Lee</td>
                            <td>⭐ 4.5/5</td>
                            <td>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal2">View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>🎓 AI Capstone Project</td>
                            <td>Mark Taylor</td>
                            <td>⭐ 4.8/5</td>
                            <td>
                                <button class="btn btn-success btn-sm">View</button>
                            </td>
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
   