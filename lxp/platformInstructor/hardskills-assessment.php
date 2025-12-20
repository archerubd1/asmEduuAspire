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
			  
			  <!-- Custom style1 Breadcrumb 
                  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
                    <ol class="breadcrumb breadcrumb-style1">
                      
                      <li class="breadcrumb-item">
                        <a href="skills-tools.php">Skills Assessment</a>
                      </li>
                      <li class="breadcrumb-item active">Hard Skills Assessment Creation </li>
                    </ol>
                  </nav>
                  <!--/ Custom style1 Breadcrumb -->
				  
			    
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
        <i class="bx bx-cloud-upload"></i> &nbsp;&nbsp; Hard Skills Assessment Creation 
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
<form>

    <!-- Skill Category & Skill Level -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-category text-primary"></i> Skill Category
            </label>
            <select class="form-select" required>
                <option value="">Select a category</option>
                <option>Programming</option>
                <option>Data Science</option>
                <option>Cybersecurity</option>
                <option>AI & Machine Learning</option>
                <option>Finance & Accounting</option>
                <option>Project Management</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">
                <i class="fas fa-layer-group text-primary"></i> Skill Level
            </label>
            <select class="form-select" required>
                <option value="">Select skill level</option>
                <option>Beginner</option>
                <option>Intermediate</option>
                <option>Advanced</option>
            </select>
        </div>
    </div>

    <!-- Assessment Type & Time Limit -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-task text-primary"></i> Assessment Type
            </label>
            <select class="form-select" required>
                <option value="">Select assessment type</option>
                <option>Multiple Choice Questions (MCQ)</option>
                <option>Coding Challenge</option>
                <option>Case Study</option>
                <option>Problem-Solving Exercise</option>
                <option>Practical Task</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-time text-primary"></i> Time Limit (minutes)
            </label>
            <input type="number" class="form-control" placeholder="Enter time limit" required>
        </div>
    </div>

    <!-- Minimum Passing Score & Benchmarking -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-check-shield text-primary"></i> Minimum Passing Score (%)
            </label>
            <input type="number" class="form-control" placeholder="Enter passing score" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-line-chart text-primary"></i> Benchmarking
            </label>
            <select class="form-select">
                <option value="">Choose benchmarking method</option>
                <option>Industry Standard</option>
                <option>Previous Learner Performance</option>
                <option>Custom Benchmark</option>
            </select>
        </div>
    </div>

    <!-- Competency & Learning Objectives Parameters -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-brain text-primary"></i> Core Competencies Assessed
            </label>
            <input type="text" class="form-control" placeholder="E.g., Python proficiency, SQL query writing" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">
                <i class="bx bx-balance text-primary"></i> Knowledge vs. Application Balance
            </label>
            <select class="form-select" required>
                <option value="">Select balance type</option>
                <option>Theory-Focused</option>
                <option>Application-Focused</option>
                <option>Balanced Mix</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">
                <i class="bx bx-briefcase text-primary"></i> Industry Alignment
            </label>
            <textarea class="form-control" rows="2" placeholder="E.g., Align with AWS certification, CFA job roles" required></textarea>
        </div>
    </div>

    <!-- Accessibility & Compliance -->
    <div class="form-check mb-2">
        <input type="checkbox" class="form-check-input" id="accessibilityCheck">
        <label class="form-check-label" for="accessibilityCheck">
            <label class="form-check-label">♿ Ensure accessibility compliance</label>
        </label>
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="plagiarismCheck">
        <label class="form-check-label" for="plagiarismCheck">
            <i class="bx bx-shield-quarter text-primary"></i> Enable plagiarism & cheating prevention
        </label>
    </div>

    <!-- Submit Button -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary">
            <i class="bx bx-save"></i> Create Assessment
        </button>
    </div>

</form>





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
   