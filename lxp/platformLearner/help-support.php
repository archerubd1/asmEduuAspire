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
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"> EduuAspire Help & Support System 
    </h4>

    <!-- Support Options -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6><i class="bx bx-error-circle"></i> Raise a Ticket</h6>
                    <p class="text-muted">Submit a support request for assistance.</p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#raiseTicketModal">Raise Ticket</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6><i class="bx bx-list-check"></i> Check Ticket Status</h6>
                    <p class="text-muted">View the status of your raised tickets.</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ticketStatusModal">View Tickets</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6><i class="bx bx-book"></i> Knowledge Base (Wiki)</h6>
                    <p class="text-muted">Find quick guides and troubleshooting solutions.</p>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#wikiModal">Browse Wiki</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Raise a Ticket Modal -->
<div class="modal fade" id="raiseTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📩 Raise a Support Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Issue Type</label>
                        <select class="form-select">
                            <option>Technical Issue</option>
                            <option>Billing & Payments</option>
                            <option>Course Content Issue</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" placeholder="Describe your issue..."></textarea>
                    </div>
                    <button type="button" class="btn btn-warning">Submit Ticket</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Check Ticket Status Modal -->
<div class="modal fade" id="ticketStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📋 My Support Tickets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Issue Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#12345</td>
                            <td>Technical Issue</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                        </tr>
                        <tr>
                            <td>#12346</td>
                            <td>Billing Inquiry</td>
                            <td><span class="badge bg-success">Resolved</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Wiki Modal -->
<div class="modal fade" id="wikiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📖 EduAspire Knowledge Base</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" placeholder="Search Knowledge Base...">
                <ul class="list-group">
                    <li class="list-group-item">📚 How to enroll in a course?</li>
                    <li class="list-group-item">🔑 How to reset your password?</li>
                    <li class="list-group-item">📩 How to contact support?</li>
                    <li class="list-group-item">💰 Understanding billing & payments</li>
                </ul>
            </div>
        </div>
    </div>
</div>




</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   