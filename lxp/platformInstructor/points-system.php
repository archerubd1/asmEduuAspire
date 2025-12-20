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
<div class="col-lg-12 mb-4 order-0">
  
  <!-- Custom style1 Breadcrumb -->
  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
    <ol class="breadcrumb breadcrumb-style1">
      <li class="breadcrumb-item">
        <a href="#">Gamification</a>
      </li>
      <li class="breadcrumb-item active">Instructor Dashboard</li>
    </ol>
  </nav>
  <!--/ Custom style1 Breadcrumb -->

  <!-- Accordion for Instructor View -->
  <div class="accordion mt-3" id="accordionExample">
    <div class="accordion-item">
      <h4 class="accordion-header" id="headingInstructor">
        <button
          type="button"
          class="accordion-button bg-label-primary"
          data-bs-toggle="collapse"
          data-bs-target="#accordionInstructor"
          aria-expanded="true"
          aria-controls="accordionInstructor"
        >
          <i class="bx bx-user-check"></i> &nbsp;&nbsp; Instructor Overview
        </button>
      </h4>
      <div id="accordionInstructor" class="accordion-collapse collapse show">
        <div class="accordion-body">
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
                <div class="container mt-5">

                  <!-- Top Performing Learners -->
                  <div class="card p-3 mt-3">
                    <h4 class="text-center"><i class="bx bx-trophy"></i> Top Learners</h4>
                    <table class="table table-striped table-hover text-center">
                      <thead class="table-dark">
                        <tr>
                          <th>Rank</th>
                          <th>Learner</th>
                          <th>Total Points</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr><td>🥇 1</td><td>Alice</td><td>4200</td></tr>
                        <tr><td>🥈 2</td><td>Bob</td><td>3900</td></tr>
                        <tr><td>🥉 3</td><td>Charlie</td><td>3700</td></tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Learner Performance Breakdown -->
                  <div class="card p-3 mt-4">
                    <h4 class="text-center"><i class="bx bx-bar-chart-alt-2"></i> Learner Performance Breakdown</h4>
                    <div id="performanceBreakdownChart"></div>
                  </div>

                  <!-- Engagement Metrics -->
                  <div class="card p-3 mt-4">
                    <h4 class="text-center"><i class="bx bx-trending-up"></i> Engagement Overview</h4>
                    <div id="engagementChart"></div>
                  </div>
                </div>

                <script>
                  // Static Data for Instructor View
                  const topLearners = [
                    { name: "Alice", total: 4200 },
                    { name: "Bob", total: 3900 },
                    { name: "Charlie", total: 3700 }
                  ];

                  // Bar Chart - Performance Breakdown
                  var optionsPerformance = {
                    series: [
                      { name: 'Learning Activities', data: [800, 750, 700] },
                      { name: 'Assessments', data: [700, 680, 600] },
                      { name: 'Projects', data: [1000, 950, 900] }
                    ],
                    chart: { type: 'bar', height: 350, stacked: true },
                    xaxis: { categories: ["Alice", "Bob", "Charlie"] },
                    colors: ['#007bff', '#fd7e14', '#28a745']
                  };
                  new ApexCharts(document.querySelector("#performanceBreakdownChart"), optionsPerformance).render();

                  // Pie Chart - Engagement Metrics
                  var optionsEngagement = {
                    series: [30, 25, 20, 15, 10],
                    chart: { type: 'pie', height: 350 },
                    labels: ['Quizzes', 'Assignments', 'Projects', 'Collaborations', 'Other'],
                    colors: ['#007bff', '#fd7e14', '#17a2b8', '#28a745', '#ffc107']
                  };
                  new ApexCharts(document.querySelector("#engagementChart"), optionsEngagement).render();
                </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php 
require_once('../platformFooter.php');
?>
   