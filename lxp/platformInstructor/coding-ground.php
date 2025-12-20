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
  <!-- Accordion for Problem Solving Skill Development -->
  <div class="accordion mt-3" id="problemSolvingAccordion">
    <div class="accordion-item">
      <h4 class="accordion-header" id="problemSolvingHeader">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#problemSolvingPanel" aria-expanded="true" aria-controls="problemSolvingPanel">
           <i class="bx bx-code-alt" style="color: #007bff; font-size: 22px;"></i> &nbsp; Manage Learning Journey &nbsp;|&nbsp; Coding Ground Skills
        </button>
      </h4>
      <div id="problemSolvingPanel" class="accordion-collapse collapse show">
        <div class="accordion-body">
          <br>

          <!-- Coding Ground Activities -->
          <div class="row">
            <!-- Daily Practice & Challenges -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-code icon" style="color: #6610f2;"></i>
                <h6 class="mt-2">Daily Practice & Challenges</h6>
                <button class="btn btn-primary btn-sm mt-2" onclick="showModal('Daily Practice & Challenges', 
                'Builds muscle memory for coding syntax and problem-solving patterns. Reinforces concepts through repetition and incremental learning.',
                'Example: Daily Coding Problems → Solve a problem every day to sharpen problem-solving speed.')">Learn More</button>
            </div>
        </div>

        <!-- Competitive & Timed Coding -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-timer icon" style="color: #dc3545;"></i>
                <h6 class="mt-2">Competitive & Timed Coding</h6>
                <button class="btn btn-danger btn-sm mt-2" onclick="showModal('Competitive & Timed Coding',
                'Develops speed & accuracy, crucial for assessments. Improves coding under pressure.',
                'Example: Speed Coding Contests → Enhances typing speed and algorithm efficiency.')">Learn More</button>
            </div>
        </div>

        <!-- Hackathons & Real-World Applications -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-rocket icon" style="color: #17a2b8;"></i>
                <h6 class="mt-2">Hackathons & Real-World Applications</h6>
                <button class="btn btn-info btn-sm mt-2" onclick="showModal('Hackathons & Real-World Applications',
                'Strengthens the ability to solve complex, real-world problems. Encourages teamwork and innovation.',
                'Example: Hackathons → Learners build a working project within a limited time.')">Learn More</button>
            </div>
        </div>

        <!-- Pair Programming & Code Reviews -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-group icon" style="color: #ffc107;"></i>
                <h6 class="mt-2">Pair Programming & Code Reviews</h6>
                <button class="btn btn-warning btn-sm mt-2" onclick="showModal('Pair Programming & Code Reviews',
                'Develops critical thinking by analyzing others’ code. Improves debugging & efficiency through peer feedback.',
                'Example: Peer Code Reviews → Learners get feedback on readability, logic, and efficiency.')">Learn More</button>
            </div>
        </div>

        <!-- Algorithm & Problem-Solving Focus -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-brain icon" style="color: #28a745;"></i>
                <h6 class="mt-2">Algorithm & Problem-Solving Focus</h6>
                <button class="btn btn-success btn-sm mt-2" onclick="showModal('Algorithm & Problem-Solving Focus',
                'Strengthens logical reasoning and problem decomposition skills. Builds confidence in solving complex problems independently.',
                'Example: Data Structures & Algorithms Bootcamps → Learners work on real-world algorithms.')">Learn More</button>
            </div>
        </div>

        <!-- Gamification & Rewards -->
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-trophy icon" style="color: #007bff;"></i>
                <h6 class="mt-2">Gamification & Rewards</h6>
                <button class="btn btn-primary btn-sm mt-2" onclick="showModal('Gamification & Rewards',
                'Motivates learners through healthy competition. Keeps learners engaged in their coding journey.',
                'Example: Leaderboard Systems → Learners track progress and aim to improve.')">Learn More</button>
            </div>
        </div>

        <!-- Industry-Ready Learning -->
        <div class="col-md-12 mb-3">
            <div class="card text-center shadow-sm p-3">
                <i class="bx bx-briefcase icon" style="color: #343a40;"></i>
                <h6 class="mt-2">Industry-Ready Learning</h6>
                <button class="btn btn-dark btn-sm mt-2" onclick="showModal('Industry-Ready Learning',
                'Prepares learners for real-world coding challenges and interviews. Encourages best practices in debugging and testing.',
                'Example: LeetCode-style Interview Questions → Mimics FAANG interview problems.')">Learn More</button>
            </div>
        </div>
          

          </div>

          <br><br>


<!-- Modal for displaying details -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalDescription"></p>
                <hr>
                <p><strong>🔹 Example:</strong></p>
                <p id="modalExample"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showModal(title, description, example) {
        document.getElementById('infoModalLabel').innerText = title;
        document.getElementById('modalDescription').innerText = description;
        document.getElementById('modalExample').innerText = example;
        var modal = new bootstrap.Modal(document.getElementById('infoModal'));
        modal.show();
    }
</script>

<!-- Icons from Boxicons -->
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
         

        </div>
      </div>
	  
	  
	  
    </div>
  </div>
</div>


  
</div>
</div>
 <!-- / Content -->





<?php 
require_once('../platformFooter.php');
?>
