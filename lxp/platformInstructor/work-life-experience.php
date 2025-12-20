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
<style>
       
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
       
        
        .icon {
            margin-right: 8px;
            font-size: 20px;
            color: #007bff;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .hidden {
            display: none;
        }

    </style>
	
<div class="col-lg-12 mb-4 order-0">
 
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
         Manage Learning Journey &nbsp;&nbsp;  <i class="bx bx-briefcase-alt"></i> &nbsp;&nbsp;  Work Life Assignments
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show">  <!-- Added "show" class -->
     
      <div class="accordion-body">
 <!-- Work Life Learning Assignments -->
<div class="row">
<p><br>
  <!-- Tasks -->
  <div class="col-md-4">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-task" style="color: #dc3545; font-size: 40px;"></i>
      <h6 class="mt-2">Create Collaborative Tasks</h6>
      <p>These are collaborative learning activities that can be taken up individually or as a group.</p>
      <button class="btn btn-danger btn-sm mt-2" onclick="createAssignment('tasks')">Create Task</button>
    </div>
  </div>

  <!-- Engage Peers -->
  <div class="col-md-4">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-group" style="color: #17a2b8; font-size: 40px;"></i>
      <h6 class="mt-2">Engage Peers</h6>
      <p>Build your own team to discuss and solve various tasks/problems collaboratively.</p>
      <button class="btn btn-info btn-sm mt-2" onclick="createAssignment('engage_peers')">Initiate Teamwork</button>
    </div>
  </div>

  <!-- Mentor Peers -->
  <div class="col-md-4">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-chalkboard" style="color: #ffc107; font-size: 40px;"></i>
      <h6 class="mt-2">Mentor Peers</h6>
      <p>Lead your peers and mentor them with the insights and skills that you have gained.</p>
      <button class="btn btn-warning btn-sm mt-2" onclick="createAssignment('mentor_peers')">Start Mentoring</button>
    </div>
  </div>
  <p><br>
  <!-- Team Building Assessments -->
  <div class="col-md-6">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-user-check" style="color: #28a745; font-size: 40px;"></i>
      <h6 class="mt-2">Team Building Assessments</h6>
      <p>Help your learner develop the skills needed to work and succeed in teams.</p>
      <button class="btn btn-success btn-sm mt-2" onclick="createAssignment('team_assessments')">Create Assessment</button>
    </div>
  </div>
  
  <!-- Self Assessments -->
  <div class="col-md-6">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-refresh" style="color: #007bff; font-size: 40px;"></i>
      <h6 class="mt-2">Self Assessments</h6>
      <p>Evaluate your skills and aptitude through self-reflection techniques.</p>
      <button class="btn btn-primary btn-sm mt-2" onclick="createAssignment('self_assessment')">Create Self-Assessment</button>
    </div>
  </div>
  
</div>

<script>
  function createAssignment(type) {
    alert("Creating assignment for: " + type);
    // Add logic to handle assignment creation
  }
</script>


		
		
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
   