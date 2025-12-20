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
  <div class="accordion mt-3" id="accordionExample">
    <div class="accordion-item">
      <h4 class="accordion-header" id="heading3">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#accordion1" aria-expanded="true" aria-controls="accordion1">
          <i class="bx bx-cloud-upload" style="color: #007bff; font-size: 22px;"></i> &nbsp; Bulk Upload Options
        </button>
      </h4><p><br>
      <div id="accordion1" class="accordion-collapse collapse show">
        <div class="accordion-body">
          <div class="row">
            <!-- Bulk Upload Learners -->
            <div class="col-md-4">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-user-plus" style="color: #28a745; font-size: 40px;"></i>
                <h6 class="mt-2">Bulk Upload Learners</h6>
                <button class="btn btn-success btn-sm mt-2" onclick="downloadCSV('learners')">Download Learners CSV</button>
                <button class="btn btn-outline-success btn-sm mt-2" onclick="showUploadSection('learners')">Upload Learners CSV</button>
              </div>
            </div>
            
            <!-- Bulk Upload TOC -->
            <div class="col-md-4">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-book-open" style="color: #ffc107; font-size: 40px;"></i>
                <h6 class="mt-2">Bulk Upload TOC</h6>
                <button class="btn btn-warning btn-sm mt-2" onclick="downloadCSV('toc')">Download TOC CSV</button>
                <button class="btn btn-outline-warning btn-sm mt-2" onclick="showUploadSection('toc')">Upload TOC CSV</button>
              </div>
            </div>
            
            <!-- Bulk Upload Assignments -->
            <div class="col-md-4">
              <div class="card text-center shadow-sm p-3">
                <i class="bx bx-file" style="color: #dc3545; font-size: 40px;"></i>
                <h6 class="mt-2">Bulk Upload Assignments</h6>
                <button class="btn btn-danger btn-sm mt-2" onclick="downloadCSV('assignments')">Download Assignments CSV</button>
                <button class="btn btn-outline-danger btn-sm mt-2" onclick="showUploadSection('assignments')">Upload Assignments CSV</button>
              </div>
            </div>
          </div>
			<p><br><br>
          <!-- File Upload Sections -->
          <div id="uploadSectionLearners" class="mt-4" style="display: none;">
            <label for="csvFileLearners" class="form-label"><i class="bx bx-upload"></i> Upload Learners CSV:</label>
            <input type="file" id="csvFileLearners" class="form-control" accept=".csv">
            <button class="btn btn-info mt-2" onclick="uploadCSV('learners')">Upload</button>
          </div>

          <div id="uploadSectionToc" class="mt-4" style="display: none;">
            <label for="csvFileToc" class="form-label"><i class="bx bx-upload"></i> Upload TOC CSV:</label>
            <input type="file" id="csvFileToc" class="form-control" accept=".csv">
            <button class="btn btn-warning mt-2" onclick="uploadCSV('toc')">Upload</button>
          </div>

          <div id="uploadSectionAssignments" class="mt-4" style="display: none;">
            <label for="csvFileAssignments" class="form-label"><i class="bx bx-upload"></i> Upload Assignments CSV:</label>
            <input type="file" id="csvFileAssignments" class="form-control" accept=".csv">
            <button class="btn btn-danger mt-2" onclick="uploadCSV('assignments')">Upload</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function downloadCSV(type) {
    const csvFiles = {
      "learners": "sample-learners.csv",
      "toc": "sample-toc.csv",
      "assignments": "sample-assignments.csv"
    };
    window.location.href = "../downloads/" + csvFiles[type];
  }

  function showUploadSection(type) {
    // Hide all sections
    document.getElementById('uploadSectionLearners').style.display = "none";
    document.getElementById('uploadSectionToc').style.display = "none";
    document.getElementById('uploadSectionAssignments').style.display = "none";

    // Show selected section
    if (type === 'learners') {
      document.getElementById('uploadSectionLearners').style.display = "block";
    } else if (type === 'toc') {
      document.getElementById('uploadSectionToc').style.display = "block";
    } else if (type === 'assignments') {
      document.getElementById('uploadSectionAssignments').style.display = "block";
    }
  }



function uploadCSV(type) {
    let fileInput;

    // Determine the correct file input based on type
    if (type === 'learners') {
        fileInput = document.getElementById('csvFileLearners');
    } else if (type === 'toc') {
        fileInput = document.getElementById('csvFileToc');
    } else if (type === 'assignments') {
        fileInput = document.getElementById('csvFileAssignments');
    }

    if (!fileInput || fileInput.files.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No File Selected',
            text: 'Please select a CSV file to upload.',
        });
        return;
    }

    let file = fileInput.files[0];
    let formData = new FormData();
    formData.append("csvFile", file);
    formData.append("type", type);

    // Send file to PHP using Fetch API
    fetch('upload_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Upload Successful!',
            text: data,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });

        // Hide the section after upload
        showUploadSection('');
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: 'An error occurred while uploading. Please try again.',
        });
    });
}





</script>

	  
  
</div>
</div>
 <!-- / Content -->





<?php 
require_once('../platformFooter.php');
?>

