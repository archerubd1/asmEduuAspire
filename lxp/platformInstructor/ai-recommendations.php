<<?php
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

$page = "aiContent";
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
                // Display messages using SweetAlert
                if (isset($_REQUEST['msg'])) {
                    $infoMessage = base64_decode(urldecode($_GET['msg']));
                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function () {
                                swal.fire("Success!", "' . $infoMessage . '", "info");
                                history.replaceState({}, document.title, window.location.origin + window.location.pathname);
                            });
                          </script>';
                }

                if (isset($_REQUEST['error'])) {
                    $errorMessage = base64_decode(urldecode($_GET['error']));
                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function () {
                                swal.fire("Error!", "' . $errorMessage . '", "error");
                                history.replaceState({}, document.title, window.location.origin + window.location.pathname);
                            });
                          </script>';
                }
                ?>

                <div class="col-lg-12 mb-4 order-0">
                    <!-- Accordion for Instructor AI-Powered Teaching Resources -->
                    <div class="accordion mt-3" id="accordionExample">
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="heading3">
                                <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
                                        data-bs-target="#accordion1" aria-expanded="true" aria-controls="accordion1">
                                    <i class="bx bx-brain" style="color: #ff5733; font-size: 22px;"></i> &nbsp; AI-Powered Teaching Resources for Instructors  
                                </button>
                            </h4>
                            <div id="accordion1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-bordered m-0 table-hover">
                                            <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Resource Title</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>Difficulty Level</th>
                                                <th>Student Engagement</th>
                                                <th>Performance Impact</th>
                                                <th>AI Insights</th>
                                                <th colspan="2">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Interactive AI Lab Simulation</td>
                                                <td><i class="bx bx-cube" style="color: blue;"></i> Virtual Lab</td>
                                                <td>Machine Learning</td>
                                                <td>Intermediate</td>
                                                <td><span class="badge bg-success">High</span></td>
                                                <td><span class="badge bg-info">Improved concept retention</span></td>
                                                <td>Highly effective for hands-on experience</td>
                                                <td><button class="btn btn-sm btn-primary"><i class="bx bx-show"></i> Preview</button></td>
                                                <td><button class="btn btn-sm btn-success"><i class="bx bx-download"></i> Use</button></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>AI-Generated Quiz Bank</td>
                                                <td><i class="bx bx-list-check" style="color: green;"></i> Quiz</td>
                                                <td>Deep Learning</td>
                                                <td>Advanced</td>
                                                <td><span class="badge bg-warning">Moderate</span></td>
                                                <td><span class="badge bg-info">Enhanced engagement</span></td>
                                                <td>Useful for testing knowledge gaps</td>
                                                <td><button class="btn btn-sm btn-primary"><i class="bx bx-show"></i> Preview</button></td>
                                                <td><button class="btn btn-sm btn-success"><i class="bx bx-download"></i> Use</button></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Automated Code Review Tool</td>
                                                <td><i class="bx bx-code-alt" style="color: red;"></i> Coding Tool</td>
                                                <td>Python Programming</td>
                                                <td>Beginner</td>
                                                <td><span class="badge bg-danger">Low</span></td>
                                                <td><span class="badge bg-info">Improved debugging skills</span></td>
                                                <td>AI-powered feedback on code quality</td>
                                                <td><button class="btn btn-sm btn-primary"><i class="bx bx-show"></i> Preview</button></td>
                                                <td><button class="btn btn-sm btn-success"><i class="bx bx-download"></i> Use</button></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>AI Ethics Case Study</td>
                                                <td><i class="bx bx-book-open" style="color: brown;"></i> Case Study</td>
                                                <td>AI & Ethics</td>
                                                <td>Advanced</td>
                                                <td><span class="badge bg-success">High</span></td>
                                                <td><span class="badge bg-info">Encourages critical thinking</span></td>
                                                <td>Recommended for ethical discussions</td>
                                                <td><button class="btn btn-sm btn-primary"><i class="bx bx-show"></i> Preview</button></td>
                                                <td><button class="btn btn-sm btn-success"><i class="bx bx-download"></i> Use</button></td>
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


<?php require_once('../platformFooter.php'); ?>
