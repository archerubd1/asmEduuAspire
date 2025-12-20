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
                      <li class="breadcrumb-item active">Incentives </li>
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
        <i class="bx bx-gift"></i> &nbsp;&nbsp; Your Incentives
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
      
	  <!-- Personal Incentives Table -->
        <div class="card p-3 mt-3">
            <h4 class="text-center"><i class="bx bx-award"></i> &nbsp;&nbsp; My Earned Incentives</h4>
            <table class="table table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Category</th>
                        <th><i class="bx bx-task"></i> Activity</th>
                        <th><i class="bx bx-dollar-circle"></i> Points Earned</th>
                        <th><i class="bx bx-time"></i> Date Earned</th>
                        <th><i class="bx bx-medal"></i> Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>📚 Learning Progress</td>
                        <td>Completed "Python for Beginners"</td>
                        <td>100</td>
                        <td>2024-02-15</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>📖 Learning Progress</td>
                        <td>Finished "Data Structures & Algorithms"</td>
                        <td>120</td>
                        <td>2024-02-20</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>🎓 Skill Development</td>
                        <td>Earned "Advanced JavaScript" Certification</td>
                        <td>150</td>
                        <td>2024-03-01</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>🛠️ Platform Engagement</td>
                        <td>Completed Profile & First Login Streak</td>
                        <td>50</td>
                        <td>2024-01-10</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>📝 Assessments</td>
                        <td>Scored 90% on "SQL Basics" Quiz</td>
                        <td>80</td>
                        <td>2024-02-25</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>👨‍💻 Projects & Coding</td>
                        <td>Developed "E-Commerce Website" Project</td>
                        <td>200</td>
                        <td>2024-03-10</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>💡 Problem-Solving</td>
                        <td>Solved 50 LeetCode Problems</td>
                        <td>180</td>
                        <td>2024-03-15</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>🤝 Collaborative Learning</td>
                        <td>Helped Peers in Discussion Forum</td>
                        <td>60</td>
                        <td>2024-03-18</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>🏆 Achievements</td>
                        <td>Earned "Top Contributor" Badge</td>
                        <td>90</td>
                        <td>2024-03-25</td>
                        <td>✅ Completed</td>
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
   