<?php
/**
 *  Astraal LXP - Learner Learning Paths
 * Refactored for new session guard architecture
 * PHP 5.4 compatible (UwAmp / GoDaddy)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified session management

$page = "gamification";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}
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
                        <a href="#">Gamification</a>
                      </li>
                      <li class="breadcrumb-item active">Points System  </li>
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
        <i class="bx bx-dollar-circle"></i> &nbsp;&nbsp; Points Earned Status  
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
      
	  
	    <!-- Learners Points Leaderboard -->
        <div class="card p-3 mt-3">
            <h4 class="text-center"><i class="bx bx-trophy"></i> Leaderboard - Points Earned</h4>
            <table class="table table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Learner</th>
                        <th>Total Points</th>
                        <th>Learning Activities</th>
                        <th>Assessments</th>
                        <th>Skills Developed</th>
                        <th>Projects</th>
                        <th>Coding Challenges</th>
                        <th>Collaboration</th>
                    </tr>
                </thead>
                <tbody id="pointsTable">
                    <!-- Data will be inserted dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Points Breakdown Chart -->
        <div class="card p-3 mt-4">
            <h4 class="text-center"><i class="bx bx-bar-chart-alt-2"></i> Breakdown of Points Earned</h4>
            <div id="pointsBreakdownChart"></div>
        </div>

        <!-- Skills Radar Chart -->
        <div class="card p-3 mt-4">
            <h4 class="text-center"><i class="bx bx-brain"></i> Skills Earned vs Peers</h4>
            <div id="skillsRadarChart"></div>
        </div>

        <!-- Points Distribution Pie Chart -->
        <div class="card p-3 mt-4">
            <h4 class="text-center"><i class="bx bx-pie-chart"></i> Points Distribution</h4>
            <div id="pointsPieChart"></div>
        </div>
    </div>

    <script>
        // Sample Learner Data
        const learners = [
            { name: "Alice", total: 4200, learning: 800, assessments: 700, skills: 900, projects: 1000, coding: 600, collaboration: 200 },
            { name: "Bob", total: 3900, learning: 750, assessments: 680, skills: 850, projects: 950, coding: 500, collaboration: 170 },
            { name: "Charlie", total: 3700, learning: 700, assessments: 600, skills: 820, projects: 900, coding: 450, collaboration: 230 },
            { name: "You", total: 3600, learning: 720, assessments: 590, skills: 810, projects: 890, coding: 420, collaboration: 270 },
            { name: "David", total: 3400, learning: 650, assessments: 570, skills: 780, projects: 850, coding: 400, collaboration: 250 }
        ];

        // Sort learners by total points (Descending Order)
        learners.sort((a, b) => b.total - a.total);

        // Populate Leaderboard Table
        let pointsHTML = "";
        learners.forEach((learner, index) => {
            let badge = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
            let highlight = learner.name === "You" ? 'style="background-color: #f8d7da;"' : "";
            pointsHTML += `
                <tr ${highlight}>
                    <td>${badge} ${index + 1}</td>
                    <td>${learner.name}</td>
                    <td>${learner.total}</td>
                    <td>${learner.learning}</td>
                    <td>${learner.assessments}</td>
                    <td>${learner.skills}</td>
                    <td>${learner.projects}</td>
                    <td>${learner.coding}</td>
                    <td>${learner.collaboration}</td>
                </tr>
            `;
        });
        document.getElementById("pointsTable").innerHTML = pointsHTML;

        // Stacked Bar Chart - Points Breakdown
        var optionsBreakdown = {
            series: [
                { name: 'Learning Activities', data: learners.map(l => l.learning) },
                { name: 'Assessments', data: learners.map(l => l.assessments) },
                { name: 'Skills Developed', data: learners.map(l => l.skills) },
                { name: 'Projects', data: learners.map(l => l.projects) },
                { name: 'Coding Challenges', data: learners.map(l => l.coding) },
                { name: 'Collaboration', data: learners.map(l => l.collaboration) }
            ],
            chart: { type: 'bar', height: 350, stacked: true },
            xaxis: { categories: learners.map(l => l.name) },
            colors: ['#007bff', '#fd7e14', '#17a2b8', '#28a745', '#ffc107', '#6f42c1']
        };
        new ApexCharts(document.querySelector("#pointsBreakdownChart"), optionsBreakdown).render();

        // Radar Chart - Skills Mastery
        var optionsRadar = {
            series: learners.map(l => ({
                name: l.name,
                data: [l.skills, l.projects, l.coding, l.learning]
            })),
            chart: { type: 'radar', height: 350 },
            labels: ['Skills Developed', 'Projects', 'Coding Challenges', 'Learning Activities'],
            colors: ['#dc3545', '#17a2b8', '#ffc107', '#28a745']
        };
        new ApexCharts(document.querySelector("#skillsRadarChart"), optionsRadar).render();

        // Pie Chart - Points Distribution
        var optionsPie = {
            series: [learners[3].learning, learners[3].assessments, learners[3].skills, learners[3].projects, learners[3].coding, learners[3].collaboration], // "You"
            chart: { type: 'pie', height: 350 },
            labels: ['Learning Activities', 'Assessments', 'Skills', 'Projects', 'Coding Challenges', 'Collaboration'],
            colors: ['#007bff', '#fd7e14', '#17a2b8', '#28a745', '#ffc107', '#6f42c1']
        };
        new ApexCharts(document.querySelector("#pointsPieChart"), optionsPie).render();
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
   