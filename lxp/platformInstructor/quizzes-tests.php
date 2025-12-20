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


$stmt = $coni->prepare("
    SELECT 
        utc.courses_ID AS cid, 
        c.name AS course_name,
        d.name AS course_category,
        (SELECT COUNT(*) FROM tests t 
         JOIN lessons_to_courses ltc ON t.lessons_ID = ltc.lessons_ID 
         WHERE ltc.courses_ID = utc.courses_ID) AS test_count
    FROM users_to_courses utc
    JOIN courses c ON utc.courses_ID = c.id
    JOIN directions d ON c.directions_ID = d.id
    WHERE utc.users_LOGIN = ?
");
$stmt->bind_param("s", $user_name);
$stmt->execute();

$stmt->store_result(); // Store the result to allow row count checking

// Bind variables to fetch data manually
$stmt->bind_result($cid, $course_name, $course_category, $test_count);

$data = [];
while ($stmt->fetch()) {
    $data[] = [
        'cid' => $cid,
        'course_name' => $course_name,
        'course_category' => $course_category,
        'test_count' => $test_count
    ];
}

?>

<!-- Layout container -->
 <body>
  


<div class="layout-page">
    <?php require_once('instructorNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">

                <?php
                // SweetAlert for messages
                if (isset($_REQUEST['msg'])) {
                    $infoMessage = base64_decode(urldecode($_GET['msg']));
                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function () {
                                swal.fire("Info!", "' . $infoMessage . '", "info");
                                history.replaceState({}, document.title, window.location.pathname);
                            });
                          </script>';
                }

                if (isset($_REQUEST['error'])) {
                    $errorMessage = base64_decode(urldecode($_GET['error']));
                    echo '<script>
                            document.addEventListener("DOMContentLoaded", function () {
                                swal.fire("Error!", "' . $errorMessage . '", "error");
                                history.replaceState({}, document.title, window.location.pathname);
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
                                    <i class="bx bx-task" style="font-size: 22px;"></i> &nbsp; Manage Quizzes & Test
                                </button>
                            </h4>
                            <div id="accordion1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <br>

                                    <div class="table-responsive">
                                        <?php if ($result->num_rows > 0) { ?>
                                            <table class="table table-bordered m-0 table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th><i class="bx bx-barcode"></i> Course Code</th>
                                                        <th><i class="bx bx-book"></i> Course Title</th>
                                                        <th><i class="bx bx-category"></i> Course Category</th>
                                                        <th colspan="3"><i class="bx bx-cog"></i> Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no_tests = true;
                                                    $is_local = ($_SERVER['HTTP_HOST'] == 'localhost');
                                                    $base_url = $is_local ?
                                                        "http://localhost/local_lxp/www/index.php?autologin=568ea71ef53cf630917f2c8815aa9d56" :
                                                        "https://raunakeducares.com/lxp/lxpre/www/index.php?autologin=568ea71ef53cf630917f2c8815aa9d56";

                                                    while ($row = $result->fetch_assoc()) {
                                                        $has_tests = $row['test_count'] > 0;
                                                        if ($has_tests) {
                                                            $no_tests = false;
                                                        }
                                                        echo '<tr class="text-center">
                                                                <td>' . htmlspecialchars($row['cid']) . '</td>
                                                                <td>' . htmlspecialchars($row['course_name']) . '</td>
                                                                <td>' . htmlspecialchars($row['course_category']) . '</td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary" href="' . $base_url . '" target="_blank">Create</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-info ' . (!$has_tests ? 'disabled' : '') . '" ' . ($has_tests ? 'href="' . $base_url . '" target="_blank"' : '') . '>View</a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-success ' . (!$has_tests ? 'disabled' : '') . '" ' . ($has_tests ? 'href="' . $base_url . '" target="_blank"' : '') . '>Evaluate</a>
                                                                </td>
                                                              </tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                    </div>

                                    <?php if ($no_tests) { ?>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                swal.fire('Error', 'No quizzes or tests are available for any assigned courses.', 'error');
                                            });
                                        </script>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } else { ?>
                    <table class="table table-bordered m-0 table-hover">
                        <thead>
                            <tr class="text-center">
                                <th><i class="bx bx-barcode"></i> Course Code</th>
                                <th><i class="bx bx-book"></i> Course Title</th>
                                <th><i class="bx bx-category"></i> Course Category</th>
                                <th colspan="3"><i class="bx bx-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td colspan="6" style="color: red; font-weight: bold;">No courses created and published by this user.</td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>

               
            </div>
        </div>
    </div>
</div>
</body>
<?php require_once('../platformFooter.php'); ?>