<?php
/**
 *  Astraal LXP - Learner learning Goals
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

   <div class="col-lg-12 mb-4 order-0">
 
 
 <!-- Custom style1 Breadcrumb -->
                  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
                    <ol class="breadcrumb breadcrumb-style1">
                      
                      <li class="breadcrumb-item">
                        <a href="learning-path.php">Learning Path</a>
                      </li>
                      <li class="breadcrumb-item active">Learning Goals </li>
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
        <i class="bx bx-target-lock"></i> &nbsp;&nbsp; Set Your Learning Goals
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
       
     <h2 class="text-center"><i class="bx bx-target-lock" style="color: #dc3545;"></i> Learning & Development Goal-Setting Form</h2>
        
        <form class="mt-4">
            
            <!-- Personal Information -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-user-circle" style="color: #007bff;"></i> 1. Personal Information</h4>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-user" style="color: #28a745;"></i> Full Name:</label>
                    <input type="text" class="form-control" placeholder="Enter your name">
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-calendar" style="color: #6f42c1;"></i> Age Group:</label>
                    <select class="form-select">
                        <option>Under 18</option>
                        <option>18-24</option>
                        <option>25-34</option>
                        <option>35-44</option>
                        <option>45+</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-graduation" style="color: #fd7e14;"></i> Current Education Level:</label>
                    <select class="form-select">
                        <option>High School</option>
                        <option>Undergraduate</option>
                        <option>Postgraduate</option>
                        <option>Self-Taught</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-briefcase" style="color: #20c997;"></i> Current Industry/Field:</label>
                    <input type="text" class="form-control" placeholder="E.g., Technology, Finance, Healthcare">
                </div>
            </div>

            <!-- Learning Goals -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-book-reader" style="color: #17a2b8;"></i> 2. Learning Goals</h4>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-chalkboard" style="color: #ffc107;"></i> Preferred Learning Method:</label>
                    <select class="form-select">
                        <option>Online Courses</option>
                        <option>Books & Articles</option>
                        <option>Mentorship & Coaching</option>
                        <option>Hands-on Projects</option>
                        <option>Group Learning</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-time" style="color: #dc3545;"></i> Time Commitment per Week:</label>
                    <select class="form-select">
                        <option>Less than 5 hours</option>
                        <option>5-10 hours</option>
                        <option>10-20 hours</option>
                        <option>Full-time learning</option>
                    </select>
                </div>
            </div>

            <!-- Skills Development -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-brain" style="color: #fd7e14;"></i> 3. Skills Development Goals</h4>
                <label class="form-label"><i class="bx bx-code" style="color: #007bff;"></i> Select Hard Skills You Want to Learn:</label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="coding">
                    <label class="form-check-label" for="coding">Programming & Coding</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="design">
                    <label class="form-check-label" for="design">Graphic & UI/UX Design</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="ai">
                    <label class="form-check-label" for="ai">AI & Machine Learning</label>
                </div>
                <label class="mt-3 form-label"><i class="bx bx-chat" style="color: #28a745;"></i> Select Soft Skills to Develop:</label>
                <select class="form-select">
                    <option>Communication</option>
                    <option>Leadership</option>
                    <option>Critical Thinking</option>
                    <option>Public Speaking</option>
                    <option>Time Management</option>
                </select>
            </div>

            <!-- Career Readiness -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-trending-up" style="color: #28a745;"></i> 4. Career Readiness</h4>
                <div class="mb-2">
                    <label class="form-label"><i class="bx bx-briefcase-alt" style="color: #20c997;"></i> Your Career Goal:</label>
                    <select class="form-select">
                        <option>Become a Specialist in My Field</option>
                        <option>Transition to a New Industry</option>
                        <option>Freelance/Contract Work</option>
                        <option>Advance to a Leadership Role</option>
                    </select>
                </div>
            </div>

            <!-- Commitment Statement -->
            <div class="card p-3 mb-3">
                <h4><i class="bx bx-check-circle" style="color: #28a745;"></i> 5. Commitment Statement</h4>
                <textarea class="form-control" rows="3" placeholder="Write a short commitment statement..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bx bx-paper-plane" style="color: #ffffff;"></i> Submit Goals
            </button>
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
   