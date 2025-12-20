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
     
  <i class="bx bx-poll"  "font-size: 22px;"></i> &nbsp; Manage & Create Skills Assessments 

        </button>
      </h4>
      <div id="accordion1" class="accordion-collapse collapse show">
        <div class="accordion-body">
         <br>
<div class="table-responsive">
  <table class="table table-bordered m-0 table-hover">
    
	
	 <thead>
    <tr class="text-center">
  <th><i class="bx bx-category"></i> Category</th>
  <th><i class="bx bx-detail"></i> Description</th>
  <th colspan="2"><i class="bx bx-cog"></i> Actions</th>
</tr>

    </thead>
    <tbody>
      <!-- Example Rows -->
      <tr>
        <td>Hard Skills</td>
        <td>Technical skills that involve specialized knowledge or training, such as programming, data analysis, or engineering.</td>
        <td>
			<a href="hardskills-assessment.php" class="btn btn-sm btn-primary">Create</a>
		</td>
		<td>
			<a href="#hardskills-assessment-view.php" class="btn btn-sm btn-info">View</a>
		</td>
      </tr>

      <tr>
        <td>Soft Skills</td>
        <td>Personal attributes that enhance one's ability to interact effectively with others, such as communication, teamwork, and adaptability.</td>
         <td>
			<a href="#softskills-assessment.php" class="btn btn-sm btn-primary">Create</a>
		</td>
		<td>
			<a href="#softskills-assessment-view.php" class="btn btn-sm btn-info">View</a>
		</td>
      </tr>

      <tr>
        <td>Digital Skills</td>
        <td>Competencies in using digital tools and technologies, such as software proficiency, online collaboration, and digital marketing.</td>
        <td>
			<a href="#digitalskills-assessment.php" class="btn btn-sm btn-primary">Create</a>
		</td>
		<td>
			<a href="#digitalskills-assessment-view.php" class="btn btn-sm btn-info">View</a>
		</td>
      </tr>

      <tr>
        <td>Life Skills</td>
        <td>Essential skills needed for personal development and well-being, including time management, financial literacy, and stress management.</td>
       <td>
			<a href="#lifeskills-assessment.php" class="btn btn-sm btn-primary">Create</a>
		</td>
		<td>
			<a href="#lifeskills-assessment-view.php" class="btn btn-sm btn-info">View</a>
		</td>
      </tr>

      <tr>
        <td>Entrepreneurial Skills</td>
        <td>Skills related to starting and running a business, such as innovation, risk management, and financial planning.</td>
       <td>
			<a href="#entrepreneurialskills-assessment.php" class="btn btn-sm btn-primary">Create</a>
		</td>
		<td>
			<a href="#entrepreneurialskills-assessment-view.php" class="btn btn-sm btn-info">View</a>
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
