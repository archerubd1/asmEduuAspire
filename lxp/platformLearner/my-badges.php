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

$page = "gamification";
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
              <div class="row">
			    
<?php
// Check for success or informational message and display SweetAlert if exists
if (isset($_REQUEST['msg'])) {
    $successMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("Successful!", "' . $successMessage . '", "success");
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
 
 
 <!-- Custom style1 Breadcrumb -->
                  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
                    <ol class="breadcrumb breadcrumb-style1">
                      
                      <li class="breadcrumb-item">
                        <a href="#">Gamification</a>
                      </li>
                      <li class="breadcrumb-item active">My Badges </li>
                    </ol>
                  </nav>
                  <!--/ Custom style1 Breadcrumb -->
				  
				  
 <!-- Accordion for course description -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for course description -->
  <div class="accordion-item">
    <h4 class="accordion-header" id="heading3">
      <button
        type="button"
        class="accordion-button bg-label-primary"
        data-bs-toggle="collapse"
        data-bs-target="#accordion1"
        aria-expanded="true"
        aria-controls="accordion1"
      >
        <i class="bx bx-badge-check"></i> &nbsp;&nbsp; Your Badges & Certifications
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show"  <!-- Added "show" class -->
     
      <div class="accordion-body">
        
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
                <div class="container mt-5">
      
	  
	  
	  
	  <!-- Badges & Certifications Table -->
        <div class="card p-3 mt-3">
            <h4 class="text-center"><i class="bx bx-medal"></i> &nbsp;&nbsp; Earned Badges & Certifications</h4>
            <table class="table table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th><i class="bx bx-certification"></i> Certification/Badge</th>
                        <th><i class="bx bx-category"></i> Category</th>
                        <th><i class="bx bx-calendar"></i> Date Earned</th>
                        <th><i class="bx bx-award"></i> Status</th>
                        <th><i class="bx bx-download"></i> Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Python for Data Science</td>
                        <td>🎓 Skill-Based Certification</td>
                        <td>2024-02-15</td>
                        <td>✅ Completed</td>
                        <td><a href="#" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Download</a></td>
                    </tr>
                    <tr>
                        <td>JavaScript Advanced</td>
                        <td>🎓 Skill-Based Certification</td>
                        <td>2024-03-05</td>
                        <td>✅ Completed</td>
                        <td><a href="#" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Download</a></td>
                    </tr>
                    <tr>
                        <td>Full Stack Web Development</td>
                        <td>📚 Course Completion</td>
                        <td>2024-03-10</td>
                        <td>✅ Completed</td>
                        <td><a href="#" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Download</a></td>
                    </tr>
                    <tr>
                        <td>Top Problem Solver</td>
                        <td>🏆 Achievement Badge</td>
                        <td>2024-03-15</td>
                        <td>🏅 Awarded</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>100 Days of Code</td>
                        <td>🏆 Achievement Badge</td>
                        <td>2024-03-20</td>
                        <td>🏅 Awarded</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>Cybersecurity Essentials</td>
                        <td>🎓 Skill-Based Certification</td>
                        <td>2024-04-01</td>
                        <td>✅ Completed</td>
                        <td><a href="#" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Download</a></td>
                    </tr>
                    <tr>
                        <td>AI & Machine Learning Fundamentals</td>
                        <td>🎓 Skill-Based Certification</td>
                        <td>2024-04-10</td>
                        <td>✅ Completed</td>
                        <td><a href="#" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Download</a></td>
                    </tr>
                    <tr>
                        <td>Open Source Contributor</td>
                        <td>🤝 Community Badge</td>
                        <td>2024-04-15</td>
                        <td>🏅 Awarded</td>
                        <td>--</td>
                    </tr>
                    <tr>
                        <td>Hackathon Winner</td>
                        <td>🏆 Project-Based Certification</td>
                        <td>2024-04-20</td>
                        <td>🏅 Awarded</td>
                        <td><a href="#" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Download</a></td>
                    </tr>
                    <tr>
                        <td>Mentor of the Month</td>
                        <td>🤝 Community Badge</td>
                        <td>2024-04-25</td>
                        <td>🏅 Awarded</td>
                        <td>--</td>
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
  <!-- End Accordion Item -->
</div>
<!-- End Accordion -->

</div>

	  
  
</div>
</div>
 <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   