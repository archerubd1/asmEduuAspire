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
                      <li class="breadcrumb-item active">Leaderboards </li>
                    </ol>
                  </nav>
                  <!--/ Custom style1 Breadcrumb -->
				  
				  
 <!-- Accordion for Course Overview and Monitoring -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for Course Overview -->
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
        <i class="bx bx-bar-chart-alt-2"></i> &nbsp;&nbsp; Learner Performance Overview  
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
       <!-- Instructor Dashboard Table -->
        <div class="card p-3 mt-3">
            <h4 class="text-center">📜 Learner Performance Leaderboard</h4>
            <table class="table table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Learner</th>
                        <th>Overall Progress (%)</th>
                        <th>Milestones Completed</th>
                        <th>Skills Mastered</th>
                        <th>Weekly Study Hours</th>
                    </tr>
                </thead>
                <tbody id="leaderboardTable">
                    <!-- Data will be dynamically inserted for instructor review -->
                </tbody>
            </table>
        </div>

        <!-- Learner Progress Comparison Chart -->
        <div class="card p-3 mt-4">
            <h4 class="text-center">📊 Learner Progress Comparison</h4>
            <div id="progressChart"></div>
        </div>

        <!-- Skill Mastery Radar Chart -->
        <div class="card p-3 mt-4">
            <h4 class="text-center">🎯 Skill Mastery Comparison</h4>
            <div id="skillsChart"></div>
        </div>

        <!-- Weekly Learning Hours Line Chart -->
        <div class="card p-3 mt-4">
            <h4 class="text-center">⏳ Weekly Learning Hours Comparison</h4>
            <div id="learningHoursChart"></div>
        </div>
    </div>

    <script>
        // Sample Learner Data (for instructor view)
        const learners = [
            { name: "Alice", progress: 95, milestones: 8, skills: 6, hours: [12, 14, 15, 16] },
            { name: "Bob", progress: 85, milestones: 7, skills: 5, hours: [10, 13, 12, 15] },
            { name: "Charlie", progress: 75, milestones: 6, skills: 4, hours: [9, 10, 11, 14] },
            { name: "You", progress: 68, milestones: 5, skills: 4, hours: [8, 12, 13, 11] }, // Instructor view, including your own progress
            { name: "David", progress: 60, milestones: 4, skills: 3, hours: [7, 9, 10, 12] },
            { name: "Eve", progress: 50, milestones: 3, skills: 2, hours: [5, 7, 8, 10] }
        ];

        // Sort learners by overall progress (Descending Order)
        learners.sort((a, b) => b.progress - a.progress);

        // Populate Learner Performance Leaderboard
        let leaderboardHTML = "";
        learners.forEach((learner, index) => {
            let badge = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
            let highlight = learner.name === "You" ? 'style="background-color: #f8d7da;"' : "";
            leaderboardHTML += `
                <tr ${highlight}>
                    <td>${badge} ${index + 1}</td>
                    <td>${learner.name}</td>
                    <td>${learner.progress}%</td>
                    <td>${learner.milestones}</td>
                    <td>${learner.skills}</td>
                    <td>${learner.hours.reduce((a, b) => a + b, 0) / learner.hours.length} hrs</td>
                </tr>
            `;
        });
        document.getElementById("leaderboardTable").innerHTML = leaderboardHTML;

        // Progress Comparison Bar Chart (Milestones, Skills, and Progress Overview)
        var optionsProgress = {
            series: [
                { name: 'Overall Progress (%)', data: learners.map(l => l.progress) },
                { name: 'Milestones Completed', data: learners.map(l => l.milestones) },
                { name: 'Skills Mastered', data: learners.map(l => l.skills) }
            ],
            chart: { type: 'bar', height: 350 },
            xaxis: { categories: learners.map(l => l.name) },
            colors: ['#007bff', '#28a745', '#fd7e14']
        };
        new ApexCharts(document.querySelector("#progressChart"), optionsProgress).render();

        // Skill Mastery Radar Chart for Instructor's Insight
        var optionsSkills = {
            series: learners.map(l => ({
                name: l.name,
                data: [l.skills * 15, l.milestones * 12, l.progress * 1.2]
            })),
            chart: { type: 'radar', height: 350 },
            labels: ['Skills Mastery', 'Milestones', 'Overall Progress'],
            colors: ['#17a2b8', '#ffc107', '#dc3545']
        };
        new ApexCharts(document.querySelector("#skillsChart"), optionsSkills).render();

        // Weekly Learning Hours Line Chart for Trend Analysis
        var optionsHours = {
            series: learners.map(l => ({
                name: l.name,
                data: l.hours
            })),
            chart: { type: 'line', height: 350 },
            xaxis: { categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'] },
            colors: ['#6f42c1', '#fd7e14', '#20c997', '#dc3545', '#007bff']
        };
        new ApexCharts(document.querySelector("#learningHoursChart"), optionsHours).render();
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
