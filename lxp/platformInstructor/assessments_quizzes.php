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


// Prepare SQL statement
$stmt = $coni->prepare("
    SELECT 
        utc.courses_ID AS cid, 
        c.name AS course_name,
        d.name AS course_category,
        t.content_ID AS unitID,
        t.name AS test_name
    FROM users_to_courses utc
    JOIN courses c ON utc.courses_ID = c.id
    JOIN directions d ON c.directions_ID = d.id
    JOIN lessons_to_courses ltc ON utc.courses_ID = ltc.courses_ID
    JOIN tests t ON ltc.lessons_ID = t.lessons_ID
    WHERE utc.users_LOGIN = ?
    AND t.content_ID IS NOT NULL
    ORDER BY c.name, t.name
");

$stmt->bind_param("s", $user_name);
$stmt->execute();
$stmt->store_result(); // Store result to check row count

// Bind the result variables
$stmt->bind_result($cid, $course_name, $course_category, $unitID, $test_name);

$data = [];
while ($stmt->fetch()) {
    $data[] = [
        'cid' => $cid,
        'course_name' => $course_name,
        'course_category' => $course_category,
        'unitID' => $unitID,
        'test_name' => $test_name
    ];
}

$stmt->close();
?>

<!-- Layout container -->
<div class="layout-page">
    <?php require_once('instructorNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <?php
                // Display success/error messages if present
                if (isset($_REQUEST['msg'])) {
                    $infoMessage = base64_decode(urldecode($_GET['msg']));
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function () {
                            swal.fire("Info", "' . htmlspecialchars($infoMessage) . '", "info");
                            history.replaceState({}, document.title, window.location.origin + window.location.pathname);
                        });
                    </script>';
                }

                if (isset($_REQUEST['error'])) {
                    $errorMessage = base64_decode(urldecode($_GET['error']));
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function () {
                            swal.fire("Error", "' . htmlspecialchars($errorMessage) . '", "error");
                            history.replaceState({}, document.title, window.location.origin + window.location.pathname);
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
                                    <i class="bx bx-task" style="font-size: 22px;"></i> &nbsp; Manage Quizzes & Tests
                                </button>
                            </h4>
                            <div id="accordion1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <br>
                                    <div class="table-responsive">
                                        <?php if (count($data) > 0) { ?>
                                            <table class="table table-bordered m-0 table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th><i class="bx bx-barcode"></i> Course Code</th>
                                                        <th><i class="bx bx-book"></i> Course Title</th>
                                                        <th><i class="bx bx-book"></i> Test Title</th>
                                                        <th><i class="bx bx-category"></i> Course Category</th>
                                                        <th><i class="bx bx-cog"></i> Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data as $row) { ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['cid']) ?></td>
                                                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['test_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['course_category']) ?></td>
                                                            <td>
                                                                <button onclick="autologinAndNavigate(<?= htmlspecialchars($row['unitID']) ?>)" 
                                                                        class="btn btn-info btn-sm">
                                                                    <i class="mdi mdi-view-list"></i> Attempt Test
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <table class="table table-bordered m-0 table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th><i class="bx bx-barcode"></i> Course Code</th>
                                                        <th><i class="bx bx-book"></i> Course Title</th>
                                                        <th><i class="bx bx-category"></i> Course Category</th>
                                                        <th colspan="2"><i class="bx bx-cog"></i> Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td colspan="5" style="color: red; font-weight: bold;">
                                                            No courses assigned to this user.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php require_once('../platformFooter.php'); ?>
            </div>
        </div>
    </div>
</div>

<script>
function autologinAndNavigate(unitId) {
    if (!unitId) {
        console.error("Unit ID is missing!");
        return;
    }

    // Determine if running on localhost or server
    const isLocal = window.location.hostname === "localhost";

    // Define URLs for local and server environments
    const baseAutologinUrl = isLocal 
        ? "http://localhost/local_lxp/www/index.php?autologin=27005d0d021b8aff4b63b776505a56e1"
        : "https://raunakeducares.com/lxp/lxpre/www/index.php?autologin=27005d0d021b8aff4b63b776505a56e1";

    const testPageUrl = isLocal 
        ? `http://localhost/local_lxp/www/student.php?view_unit=${unitId}`
        : `https://raunakeducares.com/lxp/lxpre/www/student.php?view_unit=${unitId}`;

    // Open the test page in a new tab immediately
    const newTab = window.open("", "_blank");

    fetch(baseAutologinUrl, {
        method: 'GET',
        credentials: 'include' // Ensure cookies are carried forward
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Autologin failed");
        }
        // Navigate to the test page in the new tab
        newTab.location.href = testPageUrl;
    })
    .catch(error => {
        console.error("Autologin error:", error);
        newTab.close(); // Close the new tab if login fails
    });
}
</script>
