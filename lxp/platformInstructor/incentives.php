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
                        swal.fire("Successful!", "' . $successMessage . '", "success");
                        var urlWithoutMsg = window.location.origin + window.location.pathname;
                        history.replaceState({}, document.title, urlWithoutMsg);
                    });
                  </script>';
        }
        
        if (isset($_REQUEST['error'])) {
            $errorMessage = base64_decode(urldecode($_GET['error']));
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        swal.fire("Invalid Operation!", "' . $errorMessage . '", "error");
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
                <a href="#">Gamification</a>
              </li>
              <li class="breadcrumb-item active">Instructor Incentives</li>
            </ol>
          </nav>
          <!--/ Custom Breadcrumb -->

          <!-- Accordion for Incentives -->
          <div class="accordion mt-3" id="accordionExample">
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
                  <i class="bx bx-gift"></i> &nbsp;&nbsp; Your Instructor Incentives
                </button>
              </h4>
              <div id="accordion1" class="accordion-collapse collapse show">
                <div class="accordion-body">
                  <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                      <div class="card-body">
                        <div class="container mt-5">
                          <!-- Instructor Incentives Table -->
                          <div class="card p-3 mt-3">
                            <h4 class="text-center"><i class="bx bx-award"></i> &nbsp;&nbsp; My Earned Incentives</h4>
                            <table class="table table-striped table-hover text-center">
                              <thead class="table-dark">
                                <tr>
                                  <th>Category</th>
                                  <th><i class="bx bx-task"></i> Activity</th>
                                  <th><i class="bx bx-dollar-circle"></i> Points Earned</th>
                                  <th><i class="bx bx-time"></i> Date Earned</th>
                                  <th><i class="bx bx-medal"></i> Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>📚 Course Development</td>
                                  <td>Published "Advanced Python" Course</td>
                                  <td>250</td>
                                  <td>2024-02-10</td>
                                  <td>✅ Approved</td>
                                </tr>
                                <tr>
                                  <td>🎓 Student Engagement</td>
                                  <td>Answered 100+ Student Queries</td>
                                  <td>150</td>
                                  <td>2024-02-20</td>
                                  <td>✅ Completed</td>
                                </tr>
                                <tr>
                                  <td>📝 Assessments</td>
                                  <td>Created 5 Quizzes for "Machine Learning"</td>
                                  <td>180</td>
                                  <td>2024-03-01</td>
                                  <td>✅ Approved</td>
                                </tr>
                                <tr>
                                  <td>📢 Webinar & Workshops</td>
                                  <td>Hosted "AI in Education" Webinar</td>
                                  <td>200</td>
                                  <td>2024-03-05</td>
                                  <td>✅ Conducted</td>
                                </tr>
                                <tr>
                                  <td>🤝 Community Contributions</td>
                                  <td>Reviewed & Approved 10 Assignments</td>
                                  <td>100</td>
                                  <td>2024-03-15</td>
                                  <td>✅ Completed</td>
                                </tr>
                                <tr>
                                  <td>🏆 Achievements</td>
                                  <td>Earned "Top Instructor" Badge</td>
                                  <td>300</td>
                                  <td>2024-03-25</td>
                                  <td>✅ Awarded</td>
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
