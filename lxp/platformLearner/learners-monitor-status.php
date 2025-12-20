<?php
/**
 *  Astraal LXP - Learner Monitor Status
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
                      <li class="breadcrumb-item active">Monitor Status </li>
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
        <i class="bx bx-line-chart"></i> &nbsp;&nbsp; Snapshot of Learning Path Status 
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
       <h2 class="text-center">📊 Learner's Learning Path Progress</h2>
        
        <div class="row">
            <!-- Overall Progress Doughnut Chart -->
            <div class="col-md-6">
                <div class="card p-3">
                    <h4 class="text-center">📈 Overall Progress</h4>
                    <div id="progressChart"></div>
                </div>
            </div>

            <!-- Skill Development Radar Chart -->
            <div class="col-md-6">
                <div class="card p-3">
                    <h4 class="text-center">🛠️ Skills Development</h4>
                    <div id="skillsChart"></div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Time Commitment Bar Chart -->
            <div class="col-md-6">
                <div class="card p-3">
                    <h4 class="text-center">⏳ Planned vs. Actual Learning Time</h4>
                    <div id="timeChart"></div>
                </div>
            </div>

            <!-- Milestone Completion Pie Chart -->
            <div class="col-md-6">
                <div class="card p-3">
                    <h4 class="text-center">🏆 Milestone Completion Status</h4>
                    <div id="milestoneChart"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Overall Learning Progress Doughnut Chart
        var optionsProgress = {
            series: [60, 30, 10], // Completed, In Progress, Not Started
            chart: { type: 'donut' },
            labels: ['Completed', 'In Progress', 'Not Started'],
            colors: ['#28a745', '#ffc107', '#dc3545']
        };
        new ApexCharts(document.querySelector("#progressChart"), optionsProgress).render();

        // Skills Development Radar Chart
        var optionsSkills = {
            series: [{
                name: "Skill Progress (%)",
                data: [80, 70, 50, 60, 90, 40] // Example: Programming: 80%, AI & ML: 90%
            }],
            chart: { type: 'radar', height: 350 },
            labels: ['Programming', 'Critical Thinking', 'Communication', 'Time Management', 'AI & ML', 'Graphic Design'],
            colors: ['#007bff']
        };
        new ApexCharts(document.querySelector("#skillsChart"), optionsSkills).render();

        // Time Commitment Bar Chart (Planned vs. Actual Learning Hours)
        var optionsTime = {
            series: [
                { name: 'Planned Hours', data: [10, 12, 15, 14] },
                { name: 'Actual Hours', data: [8, 14, 12, 16] }
            ],
            chart: { type: 'bar', height: 350 },
            xaxis: { categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'] },
            colors: ['#17a2b8', '#28a745']
        };
        new ApexCharts(document.querySelector("#timeChart"), optionsTime).render();

        // Milestone Completion Pie Chart
        var optionsMilestone = {
            series: [2, 4, 6, 1], // Not Started, In Progress, Completed, Dropped
            chart: { type: 'pie' },
            labels: ['Not Started', 'In Progress', 'Completed', 'Dropped'],
            colors: ['#dc3545', '#ffc107', '#28a745', '#6c757d']
        };
        new ApexCharts(document.querySelector("#milestoneChart"), optionsMilestone).render();
    </script>

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
   