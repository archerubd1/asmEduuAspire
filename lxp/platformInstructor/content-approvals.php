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
// Check for info or informational message and display SweetAlert if exists
if (isset($_REQUEST['msg'])) {
    $infoMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("infoful!", "' . $infoMessage . '", "info");
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
  <!-- Accordion for Bulk Upload Options -->
  <div class="accordion mt-3" id="accordionExample">
    <div class="accordion-item">
      <h4 class="accordion-header" id="heading3">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#accordion1" aria-expanded="true" aria-controls="accordion1">
     
  <i class="bx bx-list-check" style="color: #ff5733; font-size: 22px;"></i> &nbsp; List of Content for Review &  Approvals

        </button>
      </h4>
      <div id="accordion1" class="accordion-collapse collapse show">
        <div class="accordion-body">
         <br>
		 
        
<div class="table-responsive">
  <table class="table table-bordered m-0 table-hover">
    <thead >
      <tr class="text-center">
    <th><i class="bx bx-hash"></i> #</th>
    <th><i class="bx bx-book"></i> Title</th>
    <th><i class="bx bx-file"></i> Format</th>
    <th><i class="bx bx-layer"></i> Module</th>
    <th><i class="bx bx-signal-5"></i> Level</th>
    <th><i class="bx bx-check-shield"></i> Approval Stat</th>
    <th><i class="bx bx-comment-detail"></i> Feedback</th>
    <th colspan="2"><i class="bx bx-cog"></i> Actions</th>
</tr>

    </thead>
    <tbody>
      <!-- Example Rows -->
      <tr>
        <td>1</td>
        <td>Introduction to Neural Networks</td>
        <td><i class="bx bx-play-circle" style="color: red;"></i> Video</td>
        <td>AI Fundamentals</td>
        <td>Beginner</td>
        <td><span class="badge bg-warning">Awaiting Instructor Approval</span></td>
        <td>Video quality looks good, needs further depth on practical use cases.</td>
       <td>
          <button class="btn btn-sm btn-success"><i class="bx bx-check-circle" style="color: white;"></i> Approve</button></td>
         <td> <button class="btn btn-sm btn-danger"><i class="bx bx-x-circle" style="color: white;"></i> Reject</button>
        </td>
      </tr>

      <tr>
        <td>2</td>
        <td>Data Preprocessing with Pandas</td>
        <td><i class="bx bx-file" style="color: green;"></i> PDF</td>
        <td>Data Science Basics</td>
        <td>Intermediate</td>
        <td><span class="badge bg-info">Under Review</span></td>
        <td>Clear instructions, consider adding more examples for complex datasets.</td>
        <td>
          <button class="btn btn-sm btn-success"><i class="bx bx-check-circle" style="color: white;"></i> Approve</button></td>
         <td> <button class="btn btn-sm btn-danger"><i class="bx bx-x-circle" style="color: white;"></i> Reject</button>
        </td>
      </tr>

      <tr>
        <td>3</td>
        <td>Advanced Reinforcement Learning</td>
        <td><i class="bx bx-code-alt" style="color: blue;"></i> Coding Assignment</td>
        <td>Deep Learning</td>
        <td>Advanced</td>
        <td><span class="badge bg-danger">Needs Revision</span></td>
        <td>Some examples are not running as expected; review code logic.</td>
        <td>
          <button class="btn btn-sm btn-success"><i class="bx bx-check-circle" style="color: white;"></i> Approve</button></td>
         <td> <button class="btn btn-sm btn-danger"><i class="bx bx-x-circle" style="color: white;"></i> Reject</button>
        </td>
      </tr>

      <tr>
        <td>4</td>
        <td>AI and Ethics</td>
        <td><i class="bx bx-news" style="color: brown;"></i> Article</td>
        <td>AI & Society</td>
        <td>Intermediate</td>
        <td><span class="badge bg-info">Under Review</span></td>
        <td>Great insights on ethics; needs more on AI bias mitigation strategies.</td>
        <td>
          <button class="btn btn-sm btn-success"><i class="bx bx-check-circle" style="color: white;"></i> Approve</button></td>
         <td> <button class="btn btn-sm btn-danger"><i class="bx bx-x-circle" style="color: white;"></i> Reject</button>
        </td>
      </tr>

      <tr>
        <td>5</td>
        <td>Introduction to Big Data Technologies</td>
        <td><i class="bx bx-presentation" style="color: purple;"></i> PPT</td>
        <td>Data Engineering</td>
        <td>Beginner</td>
        <td><span class="badge bg-warning">Awaiting Instructor Approval</span></td>
        <td>The concepts are well-explained but should include more real-world examples.</td>
        <td>
          <button class="btn btn-sm btn-success"><i class="bx bx-check-circle" style="color: white;"></i> Approve</button></td>
         <td> <button class="btn btn-sm btn-danger"><i class="bx bx-x-circle" style="color: white;"></i> Reject</button>
        </td>
      </tr>

      <tr>
        <td>6</td>
        <td>Collaborative AI Project</td>
        <td><i class="bx bx-group" style="color: darkblue;"></i> Collaborative Project</td>
        <td>Capstone Projects</td>
        <td>Advanced</td>
        <td><span class="badge bg-warning">Awaiting Instructor Approval</span></td>
        <td>Collaborative project looks promising, further refinement needed on project scope.</td>
       <td>
          <button class="btn btn-sm btn-success"><i class="bx bx-check-circle" style="color: white;"></i> Approve</button></td>
         <td> <button class="btn btn-sm btn-danger"><i class="bx bx-x-circle" style="color: white;"></i> Reject</button>
        </td>
      </tr>
      
    </tbody>
  </table>
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
