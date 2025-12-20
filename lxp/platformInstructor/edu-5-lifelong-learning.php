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
         Manage Learning Journey &nbsp;&nbsp;  <i class="bx bx-infinite"></i> &nbsp;&nbsp;  Edu 5.0 Lifelong Learning Tasks
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show">  <!-- Added "show" class -->
     
      <div class="accordion-body">

<!-- Edu 5.0 Personalized Learning Assignments -->
<div class="row">
  <p><br>
  <!-- Genius Hours -->
  <div class="col-md-3">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-brain" style="color: #dc3545; font-size: 40px;"></i>
      <h6 class="mt-2">Genius Hours</h6>
      <p class="small">Allow your learners to choose their own learning content, and methodology.</p>
      <button class="btn btn-danger btn-sm mt-2" onclick="createAssignment('genius_hours')">Enable Learning</button>
    </div>
  </div>

  <!-- Passion Projects -->
  <div class="col-md-3">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-heart" style="color: #17a2b8; font-size: 40px;"></i>
      <h6 class="mt-2">Passion Projects</h6>
      <p class="small">Develop projects aligned with academic, work, and life skills.</p>
      <button class="btn btn-info btn-sm mt-2" onclick="createAssignment('passion_projects')">Create Project</button>
    </div>
  </div>

  <!-- The Peripheral -->
  <div class="col-md-3">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-plug" style="color: #ffc107; font-size: 40px;"></i>
      <h6 class="mt-2">The Peripheral</h6>
      <p class="small">Assign & let learners explore beyond the mundane AI/ML/VR/AR/MR.</p>
      <button class="btn btn-warning btn-sm mt-2" onclick="createAssignment('the_peripheral')">Allow Exploration</button>
    </div>
  </div>

  <!-- The Minds' I -->
  <div class="col-md-3">
    <div class="card text-center shadow-sm p-3">
      <i class="bx bx-pen" style="color: #28a745; font-size: 40px;"></i>
      <h6 class="mt-2">The Minds' I</h6>
      <p class="small">Allow learners to Challenge the notions of self, identity, and cognition.</p>
      <button class="btn btn-success btn-sm mt-2" onclick="createAssignment('minds_i')">let them Reflect & Evolve</button>
    </div>
  </div>
<p><br>
</div>

<!-- JavaScript Functions -->
<script>
  function createAssignment(type) {
    switch (type) {
      case 'genius_hours':
        alert("Starting Genius Hours - Create Learners to Choose their own learning path!");
        break;
      case 'passion_projects':
        alert("Initiating Passion Project - let learners Turn their ideas into reality!");
        break;
      case 'the_peripheral':
        alert("let the learners Explore cutting-edge technologies!");
        break;
      case 'minds_i':
        alert("Deep reflection - let learners Challenge their own  perspectives!");
        break;
      default:
        alert("Invalid selection.");
    }
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
   