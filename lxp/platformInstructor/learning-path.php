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


// SQL query
$sql_query = "SELECT 
    c.id AS course_id,
    c.name AS course_name,
    c.directions_ID,
    d.name AS direction_name,
    MIN(ltc.lessons_ID) AS first_lesson_id
FROM 
    courses c
JOIN 
    users_to_courses utc ON c.id = utc.courses_id
JOIN 
    directions d ON c.directions_ID = d.id
LEFT JOIN 
    lessons_to_courses ltc ON c.id = ltc.courses_ID
WHERE 
    utc.users_login = '$user_name'
GROUP BY 
    c.id, c.name, c.directions_ID, d.name";

// Execute the query
$courses = mysqli_query($coni, $sql_query);

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


// Function to fetch classification mapping (Removed redundant mapping)
function mapClassification($classification) {
    $validClassifications = array(
        'Active Learning', 
        'Curated Paths', 
        'Skills Booster', 
        'Level Up Courses', 
        'Crowd Favourites'
    );
	$classification = trim($classification); // Remove leading/trailing spaces
    $classification = ucwords(strtolower($classification)); // Convert to title case
    return in_array($classification, $validClassifications) ? $classification : '';
}

// Fetch all rows from the result set and store them in an array
$rows = array();
while ($row = mysqli_fetch_assoc($courses)) {
    $rows[] = $row;
}

// Function to display different course categories
function echoCoursesByCategory($rows, $category) {
    echoCoursesTable($rows, $category);
}

// Function to display course table
function echoCoursesTable($rows, $targetClassification) {
    $found = false; // Flag to check if courses exist


    $html = '<div class="table-responsive">
        <table class="table table-bordered no-scroll-table">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="text-align: center; border: 1px solid #dddddd;">Code</th>
                    <th style="text-align: center; border: 1px solid #dddddd;">Name</th>
                    <th style="text-align: center; border: 1px solid #dddddd;">Direction Name</th>
                    <th colspan="2" style="text-align: center; border: 1px solid #dddddd;">Action</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($rows as $row) {
		 
        $classification = isset($row['direction_name']) ? $row['direction_name'] : '';
        $programClassification = mapClassification($classification);

        if ($programClassification === $targetClassification) {
            $found = true;
           $html .= '<tr>
    <td class="course-details">' . htmlspecialchars($row['course_id']) . '</td>
    <td class="course-details">' . htmlspecialchars($row['course_name']) . '</td>
    <td class="course-details">' . htmlspecialchars($classification) . '</td>
    
    <!-- Course Description with AutoLogin -->
    <td class="course-action">
        <a class="custom-tooltip" href="#" onclick="autoLoginAndRedirect(\'course_info\', ' . urlencode($row['course_id']) . ')" 
           data-bs-toggle="tooltip" data-bs-offset="0,4" 
           data-bs-placement="bottom" data-bs-html="true" 
           title="Update Course Description">
            <i class="bx bx-book-open" style="color: #28a745; font-size: 24px;"></i>
        </a>
    </td>

    <!-- Start Learning with AutoLogin -->
    <td class="course-action">
    <a class="custom-tooltip" href="#" 
       onclick="autoLoginAndRedirect(\'start_learning\', ' . urlencode($row['course_id']) . ', ' . (isset($row['first_lesson_id']) ? urlencode($row['first_lesson_id']) : 'null') . ')" 
       data-bs-toggle="tooltip" data-bs-offset="0,4" 
       data-bs-placement="bottom" data-bs-html="true" 
       title="Review & Edit Course Content">
        <i class="bx bx-play-circle" style="color: #007bff; font-size: 24px;"></i>
    </a>
</td>
</tr>';
        }
    }

    $html .= '</tbody></table></div>';

    echo $found ? $html : '<h4>Your Course Allocation is underway! Please check later.</h4>';
}




	
?>
<script>
function autoLoginAndRedirect(action, courseId, lessonId) {
    // Detect if running on localhost or live server
    let isLocalhost = window.location.hostname === "localhost";

    // Set auto-login URL dynamically
    let autoLoginUrl = isLocalhost 
        ? "http://localhost/raunakeducares.com/lxp/lxpre/www/index.php?autologin=568ea71ef53cf630917f2c8815aa9d56"
        : "https://raunakeducares.com/lxp/lxpre/www/index.php?autologin=568ea71ef53cf630917f2c8815aa9d56";

    // Initialize redirect URL
    let redirectUrl = "";

    // Set redirect URL based on action and environment
    if (isLocalhost) {
        if (action === "course_info") {
            redirectUrl = `http://localhost/raunakeducares.com/lxp/lxpre/www/professor.php?ctg=lessons&course=${courseId}&op=course_info`;
        } else if (action === "start_learning") {
            if (lessonId) {
                redirectUrl = `http://localhost/raunakeducares.com/lxp/lxpre/www/professor.php?lessons_ID=${lessonId}&from_course=${courseId}`;
            } else {
                alert("No lessons found for this course.");
                return;
            }
        }
    } else {  // Live server
        if (action === "course_info") {
            redirectUrl = `https://raunakeducares.com/lxp/lxpre/www/professor.php?ctg=lessons&course=${courseId}&op=course_info`;
        } else if (action === "start_learning") {
            if (lessonId) {
                redirectUrl = `https://raunakeducares.com/lxp/lxpre/www/professor.php?lessons_ID=${lessonId}&from_course=${courseId}`;
            } else {
                alert("No lessons found for this course.");
                return;
            }
        }
    }

    // Perform autologin first, then redirect
    fetch(autoLoginUrl, { credentials: 'include' })
        .then(response => {
            if (response.ok) {
                window.open(redirectUrl, '_blank'); // Opens in a new tab
            } else {
                alert("Autologin failed! Please try again.");
            }
        })
        .catch(error => console.error("Autologin Error:", error));
}
</script>



<div class="col-lg-12 mb-4 order-0">
  <!-- Accordion for Learning Path Management -->
  <div class="accordion mt-3" id="learningPathAccordion">
    <div class="accordion-item">
      <h4 class="accordion-header" id="learningPathHeader">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#learningPathPanel" aria-expanded="true" aria-controls="learningPathPanel">
		   <i class="bx bx-line-chart" style="color: #007bff; font-size: 22px;"></i> &nbsp; Manage Learning Journey &nbsp;|&nbsp; Learning Paths
          
        </button>
      </h4>
      <div id="learningPathPanel" class="accordion-collapse collapse show">
        <div class="accordion-body">
          <br>

          <!-- Learning Path Functionalities -->
          <div class="row">
            <!-- Analyze Training Needs -->
            <div class="col-md-2">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-search-alt" style="color: #dc3545; font-size: 40px;"></i>
                <h6 class="mt-2">Training  Needs</h6>
                <button class="btn btn-danger btn-sm mt-2" onclick="handleProcess('training_needs')">Start Analysis</button>
              </div>
            </div>

            <!-- Evaluate Skill Gaps -->
            <div class="col-md-2">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-bar-chart-alt-2" style="color: #17a2b8; font-size: 40px;"></i>
                <h6 class="mt-2">Evaluate Skill Gaps</h6>
                <button class="btn btn-info btn-sm mt-2" onclick="handleProcess('skill_gaps')">Evaluate Skills</button>
              </div>
            </div>

            <!-- Define Learning Goals -->
            <div class="col-md-2">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-bullseye" style="color: #ffc107; font-size: 40px;"></i>
                <h6 class="mt-2">Learning Goals</h6>
                <button class="btn btn-warning btn-sm mt-2" onclick="handleProcess('learning_goals')">Set Goals</button>
              </div>
            </div>

            <!-- Optimize Content -->
            <div class="col-md-2">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-slider-alt" style="color: #28a745; font-size: 40px;"></i>
                <h6 class="mt-2">Optimize Content</h6>
                <button class="btn btn-success btn-sm mt-2" onclick="handleProcess('optimize_content')">Refine Content</button>
              </div>
            </div>

            <!-- Define Steps and Milestones -->
            <div class="col-md-2">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-flag" style="color: #007bff; font-size: 40px;"></i>
                <h6 class="mt-2">Steps & Milestones</h6>
                <button class="btn btn-primary btn-sm mt-2" onclick="handleProcess('steps_milestones')">Set Milestones</button>
              </div>
            </div>

            <!-- Package Learning Paths -->
            <div class="col-md-2">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-package" style="color: #343a40; font-size: 40px;"></i>
                <h6 class="mt-2">Learning Paths</h6>
                <button class="btn btn-dark btn-sm mt-2" onclick="handleProcess('package_learning')">Package Now</button>
              </div>
            </div>
<p><br>
            <!-- Monitor Learning Paths -->
            <div class="col-md-12">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-pulse" style="color: #6c757d; font-size: 40px;"></i>
                <h6 class="mt-2">Monitor Learning Paths</h6>
                <button class="btn btn-secondary btn-sm mt-2" onclick="handleProcess('monitor_learning')">Track Progress</button>
              </div>
            </div>
          </div>

          <br><br>

          <!-- File Upload Section -->
          <div id="uploadSection" class="mt-4" style="display: none;">
            <label for="csvFile" class="form-label"><i class="bx bx-upload"></i> Upload Learning Path Data:</label>
            <input type="file" id="csvFile" class="form-control"> 
            <button class="btn btn-info mt-2" onclick="uploadCSV()">Upload</button>
          </div>




<p><br>
 <!-----------        Tabs for Learning Paths ------------------------->
    <div class="row">
      <div class="col-lg-12 mb-4 order-0">
        <div class="card">
		
          <div class="card-header">
  <ul class="nav nav-pills mb-3 gap-3" role="tablist">
    <!-- Learning Path Tab -->
    <li class="nav-item">
      <a href="#tab-learning-path" class="nav-link active" data-bs-toggle="pill" role="tab" aria-selected="true">
        <i class="bx bx-book-open text-primary"></i> Active Learning
      </a>
    </li>
    <!-- Problem Solving Tab -->
    <li class="nav-item">
      <a href="#tab-problem-solving" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-brain text-danger"></i> Curated Paths
      </a>
    </li>
    <!-- Coding Ground Tab -->
    <li class="nav-item">
      <a href="#tab-coding-ground" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-code-alt text-success"></i> Skills Booster
      </a>
    </li>
    <!-- Critical Thinking Tab -->
    <li class="nav-item">
      <a href="#tab-critical-thinking" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-bulb text-warning"></i> Level-Up Learning
      </a>
    </li>
    <!-- Project Management Tab -->
    <li class="nav-item">
      <a href="#tab-project-management" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-line-chart text-info"></i> Crowd Favorites
      </a>
    </li>
  </ul>
</div>

		  
          <div class="card-body">
            <div class="tab-content">
			
			
              <!-- Learning Path Tab Content -->
              <div class="tab-pane fade show active" id="tab-learning-path" role="tabpanel">
               
										<?php 
										
											echoCoursesByCategory($rows, 'Active Learning');
										?>
              </div>


			<!-- Softskills  Tab Content -->
              <div class="tab-pane fade" id="tab-problem-solving" role="tabpanel">
						
														
							<?php 
										 
											echoCoursesByCategory($rows, 'Curated Paths');

										?>
							

              </div>

			<!-- Life skills  Tab Content -->
              <div class="tab-pane fade" id="tab-coding-ground" role="tabpanel">
               
									<?php 
									echoCoursesByCategory($rows, 'Skills Booster');

									?>
              </div>
			  
			  <!-- Entrepreneuer skills  Tab Content -->
              <div class="tab-pane fade" id="tab-critical-thinking" role="tabpanel">
                
				
				
										<?php 
									echoCoursesByCategory($rows, 'Level Up Courses');

									?> 
										 
										 
              </div>
			  
			  <!-- Digital skills  Tab Content -->
              <div class="tab-pane fade" id="tab-project-management" role="tabpanel">
                
				
				 
						<?php 
									echoCoursesByCategory($rows, 'Crowd Favourites');
									?>
                
              </div>
			  
			  


              <!-- Other Tabs Content -->
              <!-- Add similar structure for other tabs -->
            </div>
          </div>
        </div>
      </div>
    </div>
 
<!---------------   CLOSE of Tabs of Learning Paths ----------------------->

















        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for Handling Learning Paths -->
<script>
  function handleProcess(type) {
    alert(type.replace("_", " ") + " process initiated!");
  }

  function uploadCSV() {
    alert("Learning Path Data Uploaded Successfully!");
    document.getElementById('uploadSection').style.display = "none";
  }
</script>


  
</div>
</div>
 <!-- / Content -->





<?php 
require_once('../platformFooter.php');
?>
