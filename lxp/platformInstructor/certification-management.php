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
			    
<?php
// Check for info or informational message and display SweetAlert if exists
if (isset($_REQUEST['msg'])) {
    $infoMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("infoful!", "' . $infoMessage . '", "info");
                // Remove the message from the URL without reloading the page
                var urlWithoutMsg = window.location.origin + window.location.pathname;
                history.replaceState({}, document.title, urlWithoutMsg);
            });
          </script>';
}

// Check for error message and display SweetAlert if exists
if (isset($_REQUEST['error'])) {
    $errorMessage = base64_decode(urldecode($_GET['error']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("Invalid Registration!!", "' . $errorMessage . '", "error");
                // Remove the message from the URL without reloading the page
                var urlWithoutError = window.location.origin + window.location.pathname;
                history.replaceState({}, document.title, urlWithoutError);
            });
          </script>';
}				
?>






<div class="col-lg-12 mb-4 order-0">
  <!-- Accordion for Bulk Upload Options -->
  <div class="accordion mt-3" id="accordionExample">
    <div class="accordion-item">
      <h4 class="accordion-header" id="heading3">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#accordion1" aria-expanded="true" aria-controls="accordion1">
     
  <i class="bx bxs-certification"  "font-size: 22px;"></i> &nbsp; Manage Badges & Certifications

        </button>
      </h4>
      <div id="accordion1" class="accordion-collapse collapse show">
        <div class="accordion-body">
         <br>
<div class="table-responsive">
  <table class="table table-bordered m-0 table-hover">
    
	
	 <thead>
      <tr class="text-center">
  <th><i class="bx bx-barcode"></i> Course Code</th>
  <th><i class="bx bx-book"></i> Course Title</th>
  <th><i class="bx bx-task"></i> Completion Status</th>
  <th><i class="bx bx-calendar"></i> Date of Completion</th>
  <th colspan="2"><i class="bx bx-cog"></i> Actions</th>
</tr>

    </thead>
    <tbody>
      <!-- Example Rows -->
      <tr class="text-center">
        <td>HSK101</td>
        <td>Advanced Programming Techniques</td>
        <td>Completed</td>
        <td>2025-01-15</td>
        <td>
          <button class="btn btn-sm btn-warning">Review</button>
        </td>
        <td>
          <button class="btn btn-sm btn-success">Approve</button>
        </td>
      </tr>

      <tr class="text-center">
        <td>SSK102</td>
        <td>Effective Communication Skills</td>
        <td>Completed</td>
        <td>2025-01-20</td>
        <td>
          <button class="btn btn-sm btn-warning">Review</button>
        </td>
        <td>
          <button class="btn btn-sm btn-success">Approve</button>
        </td>
      </tr>

      <tr class="text-center">
        <td>DSK103</td>
        <td>Digital Marketing Strategies</td>
        <td>Completed</td>
        <td>2025-01-25</td>
        <td>
          <button class="btn btn-sm btn-warning">Review</button>
        </td>
        <td>
          <button class="btn btn-sm btn-success">Approve</button>
        </td>
      </tr>

      <tr class="text-center">
        <td>LSK104</td>
        <td>Financial Literacy for Personal Growth</td>
        <td>Completed</td>
        <td>2025-02-01</td>
        <td>
          <button class="btn btn-sm btn-warning">Review</button>
        </td>
        <td>
          <button class="btn btn-sm btn-success">Approve</button>
        </td>
      </tr>

      <tr class="text-center">
        <td>ESK105</td>
        <td>Entrepreneurship Fundamentals</td>
        <td>Completed</td>
        <td>2025-02-05</td>
        <td>
          <button class="btn btn-sm btn-warning">Review</button>
        </td>
        <td>
          <button class="btn btn-sm btn-success">Approve</button>
        </td>
      </tr>

    </tbody>
	
	
	
	
	
	
  </table>
</div>


		
		
		
		
		
      </div> 
    </div>
  </div>
</div>

   
	
		  

        </div>
      </div>
    </div>
  





<?php 
require_once('../platformFooter.php');
?>
