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
         Manage Learning Journey &nbsp;&nbsp;  <i class="bx bx-chat"></i> &nbsp;&nbsp;  Mentorship & Social Learning Workshops & Webinars
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show"  <!-- Added "show" class -->
     
      <div class="accordion-body">
        
          <div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">

            <!-- Assignment Type Selection -->
            <div class="mb-3">
                <label for="assignmentType" class="form-label">
                    <i class="bx bx-list-ul icon"></i> Choose Your Workshop & Webinar to Create
                </label>
                <select id="assignmentType" name="assignmentType" onchange="showForm()" required class="form-select">
                    <option value="">-- Select --</option>
                    <option value="mentorshipProgram">Mentorship Programs</option>
                    <option value="socialProgram">Social Programs</option>
                </select>
            </div>

            

            <div id="coffeeChatForm" class="hidden" style="display: none;">
                <h5>Social Programs</h5>
                <p>Details and fields for social program creation go here.</p>
            </div>

            <script>
                function showForm() {
                    // Hide all forms
                    document.querySelectorAll("[id$='Form']").forEach(form => {
                        form.style.display = "none";
                    });

                    // Get selected value
                    let selectedValue = document.getElementById("assignmentType").value;

                    // Show the relevant form
                    if (selectedValue) {
                        let formToShow = document.getElementById(selectedValue + "Form");
                        if (formToShow) {
                            formToShow.style.display = "block";
                        }
                    }
                }
            </script>

        </div>
    </div>
</div>

		  
		<!-- Mentorship Program Creation Form -->
<div id="mentorshipProgramForm" class="hidden">
    <div class="d-flex align-items-end row">
        <div class="col-sm-12">
            <div class="card-body">
                <label for="mentorshipTitle"> <h4>🤝 Mentorship Program Creation</h4></label>
                <hr class="m-0" />
                <p><br>
                <form>
                    <!-- Row 1: Program Details -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="programTitle">
                                <i class="bx bx-crown icon" style="color: #ff5733;"></i> Program Title
                            </label>
                            <input type="text" id="programTitle" name="programTitle" required placeholder="Enter Program Title">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="programDescription">
                                <i class="bx bx-file icon" style="color: #28a745;"></i> Program Description
                            </label>
                            <textarea id="programDescription" name="programDescription" required placeholder="Briefly describe the mentorship program"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="programObjective">
                                <i class="bx bx-bulb icon" style="color: #dc3545;"></i> Program Objective
                            </label>
                            <input type="text" id="programObjective" name="programObjective" required placeholder="Define the main objective">
                        </div>
                    </div>

                    <!-- Row 2: Mentor & Mentee Details -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="mentorCriteria">
                                <i class="bx bx-user-check icon" style="color: #17a2b8;"></i> Mentor Criteria
                            </label>
                            <input type="text" id="mentorCriteria" name="mentorCriteria" required placeholder="Criteria for selecting mentors">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="menteeCriteria">
                                <i class="bx bx-user icon" style="color: #ffc107;"></i> Mentee Criteria
                            </label>
                            <input type="text" id="menteeCriteria" name="menteeCriteria" required placeholder="Criteria for selecting mentees">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="matchingProcess">
                                <i class="bx bx-shuffle icon" style="color: #6c757d;"></i> Matching Process
                            </label>
                            <input type="text" id="matchingProcess" name="matchingProcess" required placeholder="Describe how mentors and mentees are matched">
                        </div>
                    </div>

                    <!-- Row 3: Program Structure -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="sessionFrequency">
                                <i class="bx bx-calendar icon" style="color: #6610f2;"></i> Session Frequency
                            </label>
                            <input type="text" id="sessionFrequency" name="sessionFrequency" required placeholder="Weekly, Monthly, etc.">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sessionFormat">
                                <i class="bx bx-laptop icon" style="color: #fd7e14;"></i> Session Format
                            </label>
                            <input type="text" id="sessionFormat" name="sessionFormat" required placeholder="Online, In-person, Hybrid">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="duration">
                                <i class="bx bx-time icon" style="color: #e83e8c;"></i> Program Duration
                            </label>
                            <input type="text" id="duration" name="duration" required placeholder="e.g., 3 months, 6 months">
                        </div>
                    </div>

                    <!-- Row 4: Additional Features -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="resources">
                                <i class="bx bx-book icon" style="color: #20c997;"></i> Learning Resources
                            </label>
                            <textarea id="resources" name="resources" required placeholder="List available learning materials"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="evaluationCriteria">
                                <i class="bx bx-trophy icon" style="color: #198754;"></i> Evaluation Criteria
                            </label>
                            <textarea id="evaluationCriteria" name="evaluationCriteria" required placeholder="How success is measured"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="feedbackMechanism">
                                <i class="bx bx-chat icon" style="color: #ff69b4;"></i> Feedback Mechanism
                            </label>
                            <textarea id="feedbackMechanism" name="feedbackMechanism" required placeholder="How feedback is collected and addressed"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check-circle" style="color: #198754;"></i> Create Mentorship Program
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

	
<!-- Social Program Creation Form -->
<div id="socialProgramForm" class="hidden">
    <div class="d-flex align-items-end row">
        <div class="col-sm-12">
            <div class="card-body">
                <label for="socialProgramTitle"> <h4>🌍 Social Program Creation</h4></label>
                <hr class="m-0" />
                <p><br>
                <form>
                    <!-- Row 1: Program Details -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="programTitle">
                                <i class="bx bx-edit icon" style="color: #ff5733;"></i> Program Title
                            </label>
                            <input type="text" id="programTitle" name="programTitle" required placeholder="Enter program title">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="programCategory">
                                <i class="bx bx-category icon" style="color: #28a745;"></i> Program Category
                            </label>
                            <select id="programCategory" name="programCategory" required>
                                <option value="Environmental Sustainability">Environmental Sustainability</option>
                                <option value="Community Development">Community Development</option>
                                <option value="Education & Awareness">Education & Awareness</option>
                                <option value="Healthcare & Wellness">Healthcare & Wellness</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="targetAudience">
                                <i class="bx bx-user icon" style="color: #dc3545;"></i> Target Audience
                            </label>
                            <input type="text" id="targetAudience" name="targetAudience" required placeholder="E.g., Youth, Elderly, Underprivileged">
                        </div>
                    </div>

                    <!-- Row 2: Objectives and Duration -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="programObjectives">
                                <i class="bx bx-target-lock icon" style="color: #17a2b8;"></i> Program Objectives
                            </label>
                            <textarea id="programObjectives" name="programObjectives" required placeholder="State key objectives of the program"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="programDuration">
                                <i class="bx bx-time icon" style="color: #ffc107;"></i> Duration
                            </label>
                            <input type="text" id="programDuration" name="programDuration" required placeholder="E.g., 6 months, 1 year">
                        </div>
                    </div>

                    <!-- Row 3: Activities and Resources -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="keyActivities">
                                <i class="bx bx-list-check icon" style="color: #6610f2;"></i> Key Activities
                            </label>
                            <textarea id="keyActivities" name="keyActivities" required placeholder="List key activities planned"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="requiredResources">
                                <i class="bx bx-box icon" style="color: #fd7e14;"></i> Required Resources
                            </label>
                            <textarea id="requiredResources" name="requiredResources" required placeholder="Mention resources needed"></textarea>
                        </div>
                    </div>

                    <!-- Row 4: Funding & Evaluation -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fundingSources">
                                <i class="bx bx-money icon" style="color: #e83e8c;"></i> Funding Sources
                            </label>
                            <input type="text" id="fundingSources" name="fundingSources" required placeholder="E.g., Donations, Grants, Crowdfunding">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="evaluationCriteria">
                                <i class="bx bx-bar-chart-alt icon" style="color: #20c997;"></i> Evaluation Criteria
                            </label>
                            <textarea id="evaluationCriteria" name="evaluationCriteria" required placeholder="Define success metrics"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check-circle" style="color: #198754;"></i> Create Program
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
   