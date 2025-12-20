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
  
  <?php require_once('instructorNav.php'); ?>

  <!-- Content wrapper -->
  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">
        
        <?php
        // Check for success or error messages and display SweetAlert if exists
        if (isset($_REQUEST['msg'])) {
            $successMessage = base64_decode(urldecode($_GET['msg']));
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        swal.fire("Success!", "' . $successMessage . '", "success");
                        var urlWithoutMsg = window.location.origin + window.location.pathname;
                        history.replaceState({}, document.title, urlWithoutMsg);
                    });
                  </script>';
        }
        
        if (isset($_REQUEST['error'])) {
            $errorMessage = base64_decode(urldecode($_GET['error']));
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        swal.fire("Error!", "' . $errorMessage . '", "error");
                        var urlWithoutError = window.location.origin + window.location.pathname;
                        history.replaceState({}, document.title, urlWithoutError);
                    });
                  </script>';
        }        
        ?>

        <div class="col-lg-12 mb-4 order-0">
          <!-- Custom Breadcrumb -->
          <nav aria-label="breadcrumb" class="d-flex justify-content-end">
            <ol class="breadcrumb breadcrumb-style1">
              <li class="breadcrumb-item">
                <a href="#">Instructor Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Teaching Performance & Recognition</li>
            </ol>
          </nav>
          <!--/ Custom Breadcrumb -->

          <!-- Accordion for Instructor Performance & Recognition -->
          <div class="accordion mt-3" id="accordionExample">
            <div class="accordion-item">
              <h4 class="accordion-header" id="heading1">
                <button
                  type="button"
                  class="accordion-button bg-label-primary"
                  data-bs-toggle="collapse"
                  data-bs-target="#accordion1"
                  aria-expanded="true"
                  aria-controls="accordion1"
                >
                  <i class="bx bx-trophy"></i> &nbsp;&nbsp; Your Teaching Performance & Recognition
                </button>
              </h4>
              <div id="accordion1" class="accordion-collapse collapse show">
                <div class="accordion-body">
                  <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                      <div class="card-body">
                        <div class="container mt-5">
                          <!-- Instructor Performance Table -->
                          <div class="card p-3 mt-3">
                            <h4 class="text-center"><i class="bx bx-award"></i> &nbsp;&nbsp; My Teaching Achievements</h4>
                            <table class="table table-striped table-hover text-center">
                              <thead class="table-dark">
                                <tr>
                                  <th>Category</th>
                                  <th><i class="bx bx-book-open"></i> Contribution</th>
                                  <th><i class="bx bx-trophy"></i> Points Earned</th>
                                  <th><i class="bx bx-calendar"></i> Date</th>
                                  <th><i class="bx bx-badge-check"></i> Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>📖 Course Development</td>
                                  <td>Created "Mastering Data Science" Course</td>
                                  <td>300</td>
                                  <td>2024-02-10</td>
                                  <td>✅ Approved</td>
                                </tr>
                                <tr>
                                  <td>🎤 Webinars & Workshops</td>
                                  <td>Conducted "AI & Ethics" Webinar</td>
                                  <td>250</td>
                                  <td>2024-02-18</td>
                                  <td>✅ Completed</td>
                                </tr>
                                <tr>
                                  <td>🧑‍🎓 Student Engagement</td>
                                  <td>Provided 200+ Feedback Reviews</td>
                                  <td>180</td>
                                  <td>2024-02-25</td>
                                  <td>✅ Recognized</td>
                                </tr>
                                <tr>
                                  <td>📜 Assessments & Grading</td>
                                  <td>Designed Final Exams for ML Course</td>
                                  <td>220</td>
                                  <td>2024-03-05</td>
                                  <td>✅ Approved</td>
                                </tr>
                                <tr>
                                  <td>🏅 Mentorship & Guidance</td>
                                  <td>Guided 5 Capstone Projects</td>
                                  <td>200</td>
                                  <td>2024-03-12</td>
                                  <td>✅ Completed</td>
                                </tr>
                                <tr>
                                  <td>🌟 Instructor Recognition</td>
                                  <td>Awarded "Innovative Educator" Title</td>
                                  <td>350</td>
                                  <td>2024-03-20</td>
                                  <td>✅ Honored</td>
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
          </div>
          <!-- End Accordion -->
        </div>
      </div>
    </div>
    <!-- / Content -->
    <?php require_once('../platformFooter.php'); ?>
