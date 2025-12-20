<?php
/**
 *  Astraal  LXP - Learner Milestone Steps
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

$page = "learningPath";
require_once('learnerHead_Nav2.php');
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
<style>
        .icon { font-size: 1.3rem; vertical-align: middle; margin-right: 8px; }
        .icon-blue { color: #007bff; } /* Blue */
        .icon-green { color: #28a745; } /* Green */
        .icon-red { color: #dc3545; } /* Red */
        .icon-orange { color: #fd7e14; } /* Orange */
        .icon-purple { color: #6f42c1; } /* Purple */
        .icon-teal { color: #20c997; } /* Teal */
        .icon-cyan { color: #17a2b8; } /* Cyan */
    </style>
	
	
   <div class="col-lg-12 mb-4 order-0">
 
 
 <!-- Custom style1 Breadcrumb -->
                  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
                    <ol class="breadcrumb breadcrumb-style1">
                      
                      <li class="breadcrumb-item">
                        <a href="learning-path.php">Learning Path</a>
                      </li>
                      <li class="breadcrumb-item active">Steps & Milestones </li>
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
       <i class="bx bx-bullseye"></i> Your Steps & Milestones for Learning Goals

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
       
     <h2 class="text-center"><i class="bx bx-bullseye icon icon-red"></i> Your Steps & Milestones for Learning Goals</h2>
        <form class="mt-4">
            
            <!-- Personal Information -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-user-circle icon icon-blue"></i> 1. Personal Information</h4>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-user icon icon-green"></i> Username:</label>
                    <input type="text" class="form-control" placeholder="Enter your username">
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-id-card icon icon-orange"></i> Full Name:</label>
                    <input type="text" class="form-control" placeholder="Enter your full name">
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-calendar icon icon-purple"></i> Age Group:</label>
                    <select class="form-select">
                        <option>Under 18</option>
                        <option>18-24</option>
                        <option>25-34</option>
                        <option>35-44</option>
                        <option>45+</option>
                    </select>
                </div>
            </div>

            <!-- Learning Goals -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-book-reader icon icon-teal"></i> 2. Learning Goals</h4>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-chalkboard icon icon-cyan"></i> Preferred Learning Method:</label>
                    <select class="form-select">
                        <option>Online Courses</option>
                        <option>Books & Articles</option>
                        <option>Mentorship & Coaching</option>
                        <option>Hands-on Projects</option>
                        <option>Group Learning</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-time icon icon-red"></i> Time Commitment per Week:</label>
                    <select class="form-select">
                        <option>Less than 5 hours</option>
                        <option>5-10 hours</option>
                        <option>10-20 hours</option>
                        <option>Full-time learning</option>
                    </select>
                </div>
            </div>

            <!-- Steps & Milestones -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-flag-checkered icon icon-orange"></i> 3. Define Your Milestones & Steps</h4>
                <div id="milestones-container">
                    <div class="milestone mb-3">
                        <label class="form-label"><i class="bx bx-target-lock icon icon-red"></i> Milestone Title:</label>
                        <input type="text" class="form-control" placeholder="E.g., Learn Python Basics">
                        <label class="form-label mt-2"><i class="bx bx-calendar-check icon icon-green"></i> Target Completion Date:</label>
                        <input type="date" class="form-control">
                        <label class="form-label mt-2"><i class="bx bx-task icon icon-purple"></i> Progress Status:</label>
                        <select class="form-select">
                            <option>Not Started</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                            <option>Dropped</option>
                        </select>
                        <label class="form-label mt-2"><i class="bx bx-list-ul icon icon-blue"></i> Steps to Complete This Milestone:</label>
                        <textarea class="form-control" rows="3" placeholder="List the steps here..."></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-milestone"><i class="bx bx-plus"></i> Add Milestone</button>
            </div>

            <!-- Commitment Statement -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-check-shield icon icon-green"></i> 4. Commitment Statement</h4>
                <textarea class="form-control" rows="3" placeholder="Write a short commitment statement..."></textarea>
            </div>

            

            <button type="submit" class="btn btn-primary w-100"><i class="bx bx-paper-plane icon icon-white"></i> Submit Learning Path Steps & Milestones</button>
        </form>
    </div>
              </div>
            </div>
            
          </div>
        
		
 <script>
        $(document).ready(function () {
            $("#add-milestone").click(function () {
                $("#milestones-container").append(`
                    <div class="milestone mb-3">
                        <label class="form-label"><i class="bx bx-target-lock icon icon-red"></i> Milestone Title:</label>
                        <input type="text" class="form-control" placeholder="E.g., Build a Web App">
                        <label class="form-label mt-2"><i class="bx bx-calendar-check icon icon-green"></i> Target Completion Date:</label>
                        <input type="date" class="form-control">
                        <label class="form-label mt-2"><i class="bx bx-task icon icon-purple"></i> Progress Status:</label>
                        <select class="form-select">
                            <option>Not Started</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                            <option>Dropped</option>
                        </select>
                        <label class="form-label mt-2"><i class="bx bx-list-ul icon icon-blue"></i> Steps to Complete This Milestone:</label>
                        <textarea class="form-control" rows="3" placeholder="List the steps here..."></textarea>
                        <button type="button" class="btn btn-danger mt-2 remove-milestone"><i class="bx bx-trash"></i> Remove</button>
                    </div>
                `);
            });

            $(document).on("click", ".remove-milestone", function () {
                $(this).closest(".milestone").remove();
            });
        });
    </script>
	
	
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
   