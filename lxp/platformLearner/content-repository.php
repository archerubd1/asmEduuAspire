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

$page = "profile";
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
  <!-- Accordion for Bulk Upload Options -->
  <div class="accordion mt-3" id="accordionExample">
    <div class="accordion-item">
      <h4 class="accordion-header" id="heading3">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#accordion1" aria-expanded="true" aria-controls="accordion1">
          <i class="bx bx-book" style="color: #007bff; font-size: 22px;"></i> &nbsp;EduuAspire lxp Content Repository 
        </button>
      </h4>
      <div id="accordion1" class="accordion-collapse collapse show">
        <div class="accordion-body">
         <br>
		 <div class="row g-3">
		  <!-- PDFs -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #dc3545;" onclick="viewContentStats('pdf')">
      <div class="card-body text-center">
        <i class="bx bx-file" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">PDFs</h6>
        <p>View uploaded documents</p>
      </div>
    </div>
  </div>

  <!-- Videos -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #17a2b8;" onclick="viewContentStats('video')">
      <div class="card-body text-center">
        <i class="bx bx-video" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Videos</h6>
        <p>Browse recorded sessions</p>
      </div>
    </div>
  </div>

  <!-- Quizzes -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #ffc107;" onclick="viewContentStats('quizzes')">
      <div class="card-body text-center">
        <i class="bx bx-task" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Quizzes</h6>
        <p>View assessment stats</p>
      </div>
    </div>
  </div>

  <!-- Assignments -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #28a745;" onclick="viewContentStats('assignments')">
      <div class="card-body text-center">
        <i class="bx bx-bookmark" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Assignments</h6>
        <p>Check submitted work</p>
      </div>
    </div>
  </div>

  <!-- SCORM Packages -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #007bff;" onclick="viewContentStats('scorm')">
      <div class="card-body text-center">
        <i class="bx bx-layer" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">SCORM Packages</h6>
        <p>View SCORM modules</p>
      </div>
    </div>
  </div>

  <!-- Audio Lectures -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #343a40;" onclick="viewContentStats('audio')">
      <div class="card-body text-center">
        <i class="bx bx-microphone" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Audio Lectures</h6>
        <p>Listen to recorded audio</p>
      </div>
    </div>
  </div>

  <!-- Interactive Content -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #6c757d;" onclick="viewContentStats('interactive')">
      <div class="card-body text-center">
        <i class="bx bx-joystick" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Interactive Content</h6>
        <p>Gamified learning tools</p>
      </div>
    </div>
  </div>

  <!-- Research Papers -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #20c997;" onclick="viewContentStats('research')">
      <div class="card-body text-center">
        <i class="bx bx-book" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Research Papers</h6>
        <p>Published whitepapers</p>
      </div>
    </div>
  </div>

  <!-- Case Studies -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #e83e8c;" onclick="viewContentStats('casestudies')">
      <div class="card-body text-center">
        <i class="bx bx-briefcase" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Case Studies</h6>
        <p>Explore real-world cases</p>
      </div>
    </div>
  </div>

  <!-- Learning Paths -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #6610f2;" onclick="viewContentStats('learningpaths')">
      <div class="card-body text-center">
        <i class="bx bx-directions" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Learning Paths</h6>
        <p>Structured learning tracks</p>
      </div>
    </div>
  </div>

  <!-- Workbooks -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #e83e8b; border: 1px solid #e83e8b;" onclick="viewContentStats('workbooks')">
      <div class="card-body text-center">
        <i class="bx bx-note" style="font-size: 40px; color: white;"></i>
        <h6 class="mt-2" style="color: white;">Workbooks</h6>
        <p>Downloadable worksheets</p>
      </div>
    </div>
  </div>

  <!-- Course Materials -->
  <div class="col-md-3">
    <div class="card text-white" style="background-color: #fd7e14;" onclick="viewContentStats('coursematerials')">
      <div class="card-body text-center">
        <i class="bx bx-folder-open" style="font-size: 40px;"></i>
        <h6 class="mt-2" style="color: white;">Course Materials</h6>
        <p>All resources in one place</p>
      </div>
    </div>
  </div>
  
		 
	</div>	 
		 
		  
	


<!-- Content Repository Stats Section -->
<div id="contentStatsSection" class="mt-4" style="display: none;">
  <h5 class="card-title"><i class="bx bx-bar-chart-alt"></i> Content Repository Stats</h5>
  <p id="contentStatsText">Loading data...</p>
</div>
	  
		  

        </div>
      </div>
    </div>
  </div>
</div>

<script>function viewContentStats(resourceType) {
  $.ajax({
    url: 'fetch_content_details.php',
    type: 'POST',
    data: { resource_type: resourceType },
    dataType: 'json',
    success: function(response) {
      if (response.status === 'success' && response.data.length > 0) {
        let contentTable = `
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Course Name</th>
                <th>File Name</th>
                <th>Learning Category</th>
                <th>Institute/Corporate</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>`;

        response.data.forEach(item => {
          contentTable += `
            <tr>
              <td>${item.course_name}</td>
              <td>${item.file_name}</td>
              <td>${item.learning_category}</td>
              <td>${item.institute_corporate}</td>
              <td>${item.created_at}</td>
              <td>
                <a href="${item.file_path}" target="_blank" class="btn btn-primary btn-sm">
                  View ${resourceType.charAt(0).toUpperCase() + resourceType.slice(1)}
                </a>
              </td>
            </tr>`;
        });

        contentTable += `</tbody></table>`;

        Swal.fire({
          title: `📂 ${resourceType.toUpperCase()} Content`,
          html: contentTable,
          icon: "info",
          width: '60%',
          confirmButtonText: "Close",
          confirmButtonColor: "#007bff"
        });

      } else {
        Swal.fire({
          title: "No Data Found",
          text: `No content available for "${resourceType}".`,
          icon: "warning",
          confirmButtonColor: "#dc3545"
        });
      }
    },
    error: function(xhr, status, error) {
      Swal.fire({
        title: "Error!",
        text: "Unable to fetch data.",
        icon: "error",
        confirmButtonColor: "#dc3545"
      });
    }
  });
}

</script>

	  
  
</div>
</div>
 <!-- / Content -->





<?php 
require_once('../platformFooter.php');
?>
