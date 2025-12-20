<?php
/**
 *  Astraal LXP - Learner Skills Gap Evaluation
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
                      <li class="breadcrumb-item active">Skills Gap Analysis </li>
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
        <i class="bx bx-line-chart"></i> &nbsp;&nbsp; Snapshot of Your Skills Gap Analysis  
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
       
    <div id="skillGapChart"></div>

<div>
  <p><strong>Average Skill Gap Score:</strong> <span id="avgSkillGap"></span></p>
  <p><strong>High Demand Skills:</strong> <span id="industryDemand"></span></p>
</div>

<script>
  // Sample Data for Multiple Skill Categories
  var skillData = [
    // Technical Skills
    { name: "Python", category: "Technical", current: 2, required: 4, gap: 2, demand: "High", priority: "High", completion: 50 },
    { name: "Cloud Security", category: "Technical", current: 3, required: 4, gap: 1, demand: "High", priority: "Medium", completion: 70 },

    // Soft Skills
    { name: "Communication", category: "Soft Skill", current: 3, required: 4, gap: 1, demand: "High", priority: "High", completion: 60 },
    { name: "Leadership", category: "Soft Skill", current: 2, required: 4, gap: 2, demand: "Medium", priority: "High", completion: 40 },

    // Digital Skills
    { name: "Cybersecurity Awareness", category: "Digital", current: 2, required: 3, gap: 1, demand: "High", priority: "Medium", completion: 65 },
    { name: "Social Media Management", category: "Digital", current: 3, required: 4, gap: 1, demand: "Medium", priority: "Medium", completion: 75 },

    // Life Skills
    { name: "Time Management", category: "Life Skill", current: 3, required: 4, gap: 1, demand: "High", priority: "High", completion: 55 },
    { name: "Critical Thinking", category: "Life Skill", current: 2, required: 4, gap: 2, demand: "High", priority: "High", completion: 45 }
  ];

  var skillNames = skillData.map(skill => skill.name);
  var currentLevels = skillData.map(skill => skill.current);
  var requiredLevels = skillData.map(skill => skill.required);
  var completionRates = skillData.map(skill => skill.completion);
  var skillGapScores = skillData.map(skill => skill.gap);

  var options = {
    series: [
      {
        name: "Current Skill Level",
        data: currentLevels
      },
      {
        name: "Required Skill Level",
        data: requiredLevels
      },
      {
        name: "Learning Completion %",
        type: "line",
        data: completionRates
      }
    ],
    chart: {
      type: "bar",
      height: 400
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "50%"
      }
    },
    dataLabels: {
      enabled: false
    },
    xaxis: {
      categories: skillNames
    },
    colors: ["#008FFB", "#FF4560", "#00E396"], // Blue (current), Red (required), Green (completion %)
    title: {
      text: "Comprehensive Skill Gap Analysis - Technical, Soft, Digital & Life Skills"
    }
  };

  var chart = new ApexCharts(document.querySelector("#skillGapChart"), options);
  chart.render();

  // Compute Average Skill Gap
  var avgSkillGap = (skillGapScores.reduce((a, b) => a + b, 0) / skillGapScores.length).toFixed(1);
  document.getElementById("avgSkillGap").innerText = avgSkillGap;

  // Display Industry Demand Summary
  var highDemandSkills = skillData.filter(skill => skill.demand === "High").map(skill => skill.name).join(", ");
  document.getElementById("industryDemand").innerText = highDemandSkills || "None";
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
   