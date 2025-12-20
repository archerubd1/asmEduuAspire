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
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card"> 
                <div class="container mt-5">
                    <h2 class="mb-4">📑 Instructor Review Dashboard</h2>
                    <p class="text-muted">Manage student submissions, provide feedback, and track assessment progress.</p>

                    <!-- 🔍 Search Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <input type="text" class="form-control w-50" placeholder="Search student submissions...">
                    </div>

                    <!-- 📌 Review Overview -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">🕒 Pending Submissions</h5>
                                    <p class="text-muted">Assignments awaiting your review.</p>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#pendingReviewsModal">View Pending</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">✅ Reviewed Submissions</h5>
                                    <p class="text-muted">Assignments that have been graded and given feedback.</p>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#completedReviewsModal">View Completed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🕒 Pending Reviews Modal -->
        <div class="modal fade" id="pendingReviewsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">🕒 Pending Student Submissions</h5>
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
                                    <td>📖 Data Science Project</td>
                                    <td>John Doe</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal1">Review</button>
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
                        <h5 class="modal-title">✅ Reviewed Assignments</h5>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📝 Review Modal -->
        <div class="modal fade" id="reviewModal1" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">📖 Data Science Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>John Doe:</strong> Submitted for review.</p>
                        <hr>
                        <h6>💬 Instructor Feedback</h6>
                        <textarea class="form-control" rows="3" placeholder="Write your feedback..."></textarea>
                        <button class="btn btn-success mt-2">Submit Feedback</button>
                    </div>
                </div>
            </div>
        </div>

        </div>

<?php 
require_once('../platformFooter.php');
?>
