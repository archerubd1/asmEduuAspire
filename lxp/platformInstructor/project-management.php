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
         Manage Learning Journey &nbsp;&nbsp;  <i class="bx bx-task"></i> &nbsp;&nbsp;  Project Management
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show"  <!-- Added "show" class -->
     
      <div class="accordion-body">
        
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
               
					<!-- Project Type Selection -->
					<div class="mb-3">
						<label for="learning_category" class="form-label">
							<i class="bx bx-list-ul icon"></i> Select the Category of the Project to Create
						</label>
						   <select id="assignmentType" name="assignmentType" onchange="showForm()" required>
							<option value="">-- Select --</option>
							<option value="academicCourse">Academic Course-Based</option>
							<option value="industryBased">Industry Based </option>
							
							<option value="businessBased">Business & Entrepreneurship Based</option>
							
							</select>
					</div>
					

				<script>
					function showForm() {
						// Hide all forms initially
						let forms = document.querySelectorAll(".hidden");
						forms.forEach(form => form.style.display = "none");

						// Get selected value
						let selectedValue = document.getElementById("assignmentType").value;

						// Show relevant form based on selection
						if (selectedValue === "academicCourse") {
							document.getElementById("academicCourseForm").style.display = "block";
						} else if (selectedValue === "industryBased") {
							document.getElementById("industryBasedForm").style.display = "block";
						
						} else if (selectedValue === "businessBased") {
							document.getElementById("businessBasedForm").style.display = "block";
						} 
					}
				</script>  
			   
              </div>
            </div>
		</div>
		  
		  
		  
		 <!-- Fact or Opinion Form -->
			<div id="academicCourseForm" class="hidden">
			<div class="d-flex align-items-end row">
            <div class="col-sm-12">
			<div class="card-body">
                
				<label for="factTopic"> <h4>📌 Academic Projects</h4></label>
				 <hr class="m-0" />
				 <p><br>
			
			</div>
			</div>
			</div>
			</div>
	

 <!-- Coffee House Chat Form -->
    <div id="industryBasedForm" class="hidden">
	
	<div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="chatTopic"> <h4>☕ Industry Based Projects</h4></label>
            <hr class="m-0" />
            <p><br>
          
        </div>
    </div>
</div>
    </div>
	
	
	

    <!-- Talk It Out Form -->
    <div id="businessBasedForm" class="hidden">
        <div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="businessBased"><h4>✪ Business Based Projects</h4></label>
            <hr class="m-0" />
            <p><br>
            
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
   