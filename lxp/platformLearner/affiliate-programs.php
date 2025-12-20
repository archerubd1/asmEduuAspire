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
<div class="card-body">


<div class="container mt-5">
    <h4 class="mb-4 text-primary">
        <i class='bx bx-gift bx-md' style="color:#007bff;"></i> Join Our Affiliate Programs
    </h4>

    <div class="row">
        <!-- Card 1 - General Affiliate Program -->
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">General Affiliate Program</h5>
                    <p class="card-text">Earn commissions for every learner you refer to our platform.</p>
                    <ul class="list-unstyled">
                        <li><i class='bx bx-check-circle'></i> 10% commission per sale</li>
                        <li><i class='bx bx-check-circle'></i> Real-time tracking dashboard</li>
                    </ul>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">Enroll Now</button>
                </div>
            </div>
        </div>

        <!-- Card 2 - Influencer Affiliate -->
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Influencer Affiliate</h5>
                    <p class="card-text">Perfect for social media influencers & bloggers.</p>
                    <ul class="list-unstyled">
                        <li><i class='bx bx-check-circle'></i> Up to 15% commission</li>
                        <li><i class='bx bx-check-circle'></i> Custom promo codes</li>
                    </ul>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#enrollModal">Enroll Now</button>
                </div>
            </div>
        </div>

        <!-- Card 3 - Corporate Affiliate -->
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">Corporate Affiliate</h5>
                    <p class="card-text">Refer companies, HR professionals, and training providers.</p>
                    <ul class="list-unstyled">
                        <li><i class='bx bx-check-circle'></i> Bulk referral bonuses</li>
                        <li><i class='bx bx-check-circle'></i> Corporate learning discounts</li>
                    </ul>
                    <button class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#enrollModal">Enroll Now</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Card 4 - Student Ambassador -->
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h5 class="card-title text-info">Student Ambassador</h5>
                    <p class="card-text">Earn rewards by promoting our platform on your campus.</p>
                    <ul class="list-unstyled">
                        <li><i class='bx bx-check-circle'></i> Free learning credits</li>
                        <li><i class='bx bx-check-circle'></i> Campus event sponsorships</li>
                    </ul>
                    <button class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#enrollModal">Enroll Now</button>
                </div>
            </div>
        </div>

        <!-- Card 5 - Educator Affiliate -->
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h5 class="card-title text-danger">Educator Affiliate</h5>
                    <p class="card-text">Refer Teachers & trainers can earn by referring students & content.</p>
                    <ul class="list-unstyled">
                        <li><i class='bx bx-check-circle'></i> 12% commission</li>
                        <li><i class='bx bx-check-circle'></i> Free educator resources</li>
                    </ul>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#enrollModal">Enroll Now</button>
                </div>
            </div>
        </div>

        <!-- Card 6 - Tech Partner Affiliate -->
        <div class="col-md-4">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h5 class="card-title text-secondary">Tech Partner Affiliate</h5>
                    <p class="card-text">Refer SaaS & EdTech companies looking to integrate with us.</p>
                    <ul class="list-unstyled">
                        <li><i class='bx bx-check-circle'></i> Custom revenue-sharing model</li>
                        <li><i class='bx bx-check-circle'></i> API integrations</li>
                    </ul>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#enrollModal">Enroll Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enrollment Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollModalLabel">Affiliate Program Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="program" class="form-label">Select Program</label>
                        <select class="form-select" id="program">
                            <option>General Affiliate Program</option>
                            <option>Influencer Affiliate</option>
                            <option>Corporate Affiliate</option>
                            <option>Student Ambassador</option>
                            <option>Educator Affiliate</option>
                            <option>Tech Partner Affiliate</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</div>




<p><br><br>
</div>
</div>
</div>
 <!-- / Content -->


<?php 
require_once('../platformFooter.php');
?>
