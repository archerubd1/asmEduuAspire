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
                      <li class="breadcrumb-item active">Adaptive Learning Paths </li>
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
        <i class="bx bx-merge"></i> &nbsp;&nbsp; Your Adaptive Learning Paths
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
      
	  <!-- Adaptive Learning Paths Table -->
        <div class="card p-3 mt-3">
            <h4 class="text-center"><i class="bx bx-customize"></i> &nbsp;&nbsp; Personalized Adaptive Learning Plan</h4>
            <table class="table table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th><i class="bx bx-book-open"></i> Learning Goal</th>
                        <th><i class="bx bx-line-chart"></i> Skill Level</th>
                        <th><i class="bx bx-time-five"></i> Time Commitment</th>
                        <th><i class="bx bx-task"></i> Assessment Performance</th>
                        <th><i class="bx bx-brain"></i> Adaptation Strategy</th>
                        <th><i class="bx bx-layer"></i> Recommended Next Steps</th>
                        <th><i class="bx bx-check-circle"></i> Completion Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Python for Data Science</td>
                        <td>Intermediate</td>
                        <td>8 hrs/week</td>
                        <td>Quiz Avg: 85%</td>
                        <td>🔄 Increase challenge level, introduce case studies</td>
                        <td>Complete an AI project using Pandas & Matplotlib</td>
                        <td>⏳ In Progress</td>
                    </tr>
                    <tr>
                        <td>Machine Learning Basics</td>
                        <td>Beginner</td>
                        <td>6 hrs/week</td>
                        <td>Quiz Avg: 70%</td>
                        <td>🔄 Reinforce fundamentals, add more practice problems</td>
                        <td>Revisit Linear Regression module before moving forward</td>
                        <td>⏳ In Progress</td>
                    </tr>
                    <tr>
                        <td>Full Stack Web Development</td>
                        <td>Advanced</td>
                        <td>10 hrs/week</td>
                        <td>Project Score: 90%</td>
                        <td>🔄 Focus on deployment strategies & APIs</td>
                        <td>Deploy a REST API using Node.js & Express</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>Data Structures & Algorithms</td>
                        <td>Intermediate</td>
                        <td>7 hrs/week</td>
                        <td>Quiz Avg: 60%</td>
                        <td>🔄 More problem-solving practice needed</td>
                        <td>Solve 20 LeetCode problems before proceeding</td>
                        <td>⏳ In Progress</td>
                    </tr>
                    <tr>
                        <td>Cybersecurity Fundamentals</td>
                        <td>Beginner</td>
                        <td>5 hrs/week</td>
                        <td>Quiz Avg: 50%</td>
                        <td>🔄 Adapt by revisiting previous lessons</td>
                        <td>Watch guided tutorials on encryption techniques</td>
                        <td>⚠️ Struggling</td>
                    </tr>
                    <tr>
                        <td>UI/UX Design Principles</td>
                        <td>Advanced</td>
                        <td>6 hrs/week</td>
                        <td>Design Review Score: 95%</td>
                        <td>🔄 Introduce real-world application projects</td>
                        <td>Redesign an existing website UX using Figma</td>
                        <td>✅ Completed</td>
                    </tr>
                    <tr>
                        <td>Blockchain Development</td>
                        <td>Beginner</td>
                        <td>4 hrs/week</td>
                        <td>Quiz Avg: 65%</td>
                        <td>🔄 Provide interactive coding exercises</td>
                        <td>Build a basic smart contract using Solidity</td>
                        <td>⏳ In Progress</td>
                    </tr>
                    <tr>
                        <td>Advanced JavaScript</td>
                        <td>Intermediate</td>
                        <td>8 hrs/week</td>
                        <td>Project Score: 75%</td>
                        <td>🔄 Reinforce ES6+ concepts</td>
                        <td>Implement a JavaScript-based authentication system</td>
                        <td>⏳ In Progress</td>
                    </tr>
                    <tr>
                        <td>Cloud Computing & AWS</td>
                        <td>Beginner</td>
                        <td>6 hrs/week</td>
                        <td>Quiz Avg: 80%</td>
                        <td>🔄 Adapt by adding hands-on labs</td>
                        <td>Deploy a web app using AWS Lambda</td>
                        <td>⏳ In Progress</td>
                    </tr>
                    <tr>
                        <td>Big Data Analytics</td>
                        <td>Advanced</td>
                        <td>10 hrs/week</td>
                        <td>Project Score: 92%</td>
                        <td>🔄 Expand into predictive modeling</td>
                        <td>Work on a real-time analytics dashboard</td>
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
   