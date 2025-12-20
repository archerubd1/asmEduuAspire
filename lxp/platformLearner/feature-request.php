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
        <span class="text-muted fw-light">Feature Request By  </span> <?php echo $get['name']; ?>
    </h4>

    <div class="row">
    <div class="col-md-12">
        <!-- Tab Content Wrapper -->
        <div class="tab-content">
            <!-- Feature Request Tab -->
            <div class="tab-pane fade show active" id="feature-request">
                <div class="card">
                    <h5 class="card-header">🚀 Request a Feature for EduAspire LXP</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Feature Name</th>
                                    <th>Description</th>
                                    <th>Urgency</th>
                                    <th>Category</th>
                                    <th>Additional Comments</th>
                                    <th>Request</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" placeholder="Feature Title"></td>
                                    <td><input type="text" class="form-control" placeholder="Short Description"></td>
                                    <td>
                                        <select class="form-select">
                                            <option>Low</option>
                                            <option>Medium</option>
                                            <option>High</option>
                                            <option>Critical</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select">
                                            <option>UI/UX Improvement</option>
                                            <option>New Learning Tools</option>
                                            <option>Social & Collaboration</option>
                                            <option>Analytics & Reporting</option>
                                            <option>Gamification</option>
                                            <option>Mobile App Features</option>
                                            <option>Other</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Any extra details"></td>
                                    <td>
                                        <button class="btn btn-primary">Submit Request</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- End of card-body -->
                </div> <!-- End of card -->
            </div> <!-- End of tab-pane -->
        </div> <!-- End of tab-content -->
    </div> <!-- End of col-md-12 -->
</div> <!-- End of row -->

</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   