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
        // Display success message
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
        
        // Display error message
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

          <!-- Breadcrumb Navigation -->
          <nav aria-label="breadcrumb" class="d-flex justify-content-end">
            <ol class="breadcrumb breadcrumb-style1">
              <li class="breadcrumb-item">
                <a href="#">Gamification</a>
              </li>
              <li class="breadcrumb-item active">Student Progress & Adaptive Learning</li>
            </ol>
          </nav>
          <!-- End Breadcrumb -->

          <!-- Accordion for Instructor Dashboard -->
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
                  <i class="bx bx-user-check"></i> &nbsp;&nbsp; Manage Student Learning Paths
                </button>
              </h4>
              <div id="accordion1" class="accordion-collapse collapse show">
                <div class="accordion-body">

                  <!-- Student Learning Paths Table -->
                  <div class="card p-3 mt-3">
                    <h4 class="text-center"><i class="bx bx-chart"></i> &nbsp;&nbsp; Student Progress Overview</h4>
                    <table class="table table-striped table-hover text-center">
                      <thead class="table-dark">
                        <tr>
                          <th><i class="bx bx-user"></i> Student Name</th>
                          <th><i class="bx bx-book"></i> Current Course</th>
                          <th><i class="bx bx-bar-chart"></i> Performance</th>
                          <th><i class="bx bx-brain"></i> Suggested Adaptations</th>
                          <th><i class="bx bx-task"></i> Next Steps</th>
                          <th><i class="bx bx-check-circle"></i> Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Alice Johnson</td>
                          <td>Python for Data Science</td>
                          <td>Quiz Avg: 85%</td>
                          <td>Introduce real-world datasets for practice</td>
                          <td>Assign a mini-project on Data Analysis</td>
                          <td>In Progress</td>
                        </tr>
                        <tr>
                          <td>Michael Lee</td>
                          <td>Machine Learning Basics</td>
                          <td>Quiz Avg: 70%</td>
                          <td>More hands-on coding exercises needed</td>
                          <td>Provide additional problem sets</td>
                          <td>In Progress</td>
                        </tr>
                        <tr>
                          <td>Sophia Patel</td>
                          <td>Full Stack Web Development</td>
                          <td>Project Score: 90%</td>
                          <td>Enhance skills with backend development</td>
                          <td>Deploy an API using Express.js</td>
                          <td>Completed</td>
                        </tr>
                        <tr>
                          <td>David Kim</td>
                          <td>Data Structures & Algorithms</td>
                          <td>Quiz Avg: 60%</td>
                          <td>Encourage more coding practice</td>
                          <td>Assign LeetCode exercises</td>
                          <td>In Progress</td>
                        </tr>
                        <tr>
                          <td>Emma Wilson</td>
                          <td>Cybersecurity Fundamentals</td>
                          <td>Quiz Avg: 50%</td>
                          <td>Needs foundational reinforcement</td>
                          <td>Provide guided tutorials</td>
                          <td>Struggling</td>
                        </tr>
                        <tr>
                          <td>James Brown</td>
                          <td>Advanced JavaScript</td>
                          <td>Project Score: 75%</td>
                          <td>Improve understanding of ES6+</td>
                          <td>Develop a full authentication system</td>
                          <td>In Progress</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- End Table -->
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
