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
          
		  
		<?php require_once('instructorNav.php');   ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
			  
   <div class="col-lg-12 mb-4 order-0">
   
				

 <!-- Accordion for course description -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for course description -->
  <div class="accordion-item">
    <h4 class="accordion-header" id="heading3">
      <button
        type="button"
        class="accordion-button bg-label-info"
        data-bs-toggle="collapse"
        data-bs-target="#accordion1"
        aria-expanded="true"
        aria-controls="accordion1"
      >
        <i class="bx bx-library"></i> &nbsp;&nbsp; Learning Content Management System (LCMS) 
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show"  <!-- Added "show" class -->
     
      <div class="accordion-body">
        
          <div class="d-flex align-items-end row">
            <div class="col-sm-9">
              <div class="card-body">
                <p>
                You will navigate externally to the lxp - Learning Content Management System (LCMS) interface that allows you to manage courses, materials, and student progress.
                </p>
              </div>
            </div>
            <div class="col-sm-3 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img
                  src="../assets/img/illustrations/lcms.png"
                  height="140"
                  alt="Learning Path Progress"
                  data-app-dark-img="illustrations/lcms.png"
                  data-app-light-img="illustrations/lcms.png"
                />
              </div>
            </div>
          </div>
        
      </div> 
    </div>
  </div>
  <!-- End Accordion Item -->
</div>
<!-- End Accordion -->



<div class="row mt-4">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body text-center">
        <?php
          // Check if running on localhost or server
          $isLocal = ($_SERVER['HTTP_HOST'] === "localhost");

          // Define URLs for local and server environments
          $contentUrl = $isLocal 
              ? "http://localhost/local_lxp/www/index.php?autologin=568ea71ef53cf630917f2c8815aa9d56"
              : "https://raunakeducares.com/lxp/lxpre/www/index.php?autologin=568ea71ef53cf630917f2c8815aa9d56";
        ?>
        <a href="<?= htmlspecialchars($contentUrl) ?>" target="_blank" class="btn btn-primary">
          <i class="bx bx-file"></i> Create & Publish Content
        </a>
      </div>
    </div>
  </div>
</div>



	  
  
</div>
</div>
 <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   