<?php
/**
 * Astraal LXP - Learner Learning Paths (Assignments View)
 * Debugged for unified session-guard
 * Dynamic AutoLogin Token
 * PHP 5.4 compatible | UwAmp / GoDaddy
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // unified session management

$page = "learningPath";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// ✅ Validate session (consistent with new guard)
// -----------------------------------------------------------------------------
if (
    !isset($_SESSION['phx_user_id']) ||
    !isset($_SESSION['phx_user_login'])
) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

// Extract session values safely
$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];
$phx_user_name  = isset($_SESSION['phx_user_name']) ? $_SESSION['phx_user_name'] : '';

// -----------------------------------------------------------------------------
// ✅ Database connection check
// -----------------------------------------------------------------------------
if (!isset($coni) || !$coni) {
    die("❌ Database connection not established. Please check config.php.");
}

// -----------------------------------------------------------------------------
// ✅ Fetch learner autologin token dynamically
// -----------------------------------------------------------------------------
$autoLoginToken = '';
$query = mysqli_query(
    $coni,
    "SELECT autologin FROM users WHERE login = '" . mysqli_real_escape_string($coni, $phx_user_login) . "' LIMIT 1"
);
if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $autoLoginToken = isset($row['autologin']) ? trim($row['autologin']) : '';
}
if ($autoLoginToken == '') {
    $autoLoginToken = 'MISSING_AUTOTOKEN'; // fallback debug marker
}

// -----------------------------------------------------------------------------
// ✅ Prepare and execute learner course/test mapping query
// -----------------------------------------------------------------------------
$sql = "
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
";

if ($stmt = mysqli_prepare($coni, $sql)) {

    mysqli_stmt_bind_param($stmt, "s", $phx_user_login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $cid, $course_name, $course_category, $unitID, $test_name);

    $data = array();
    while (mysqli_stmt_fetch($stmt)) {
        $data[] = array(
            'cid'             => $cid,
            'course_name'     => $course_name,
            'course_category' => $course_category,
            'unitID'          => $unitID,
            'test_name'       => $test_name
        );
    }

    mysqli_stmt_close($stmt);
} else {
    die("❌ SQL Error: " . mysqli_error($coni));
}
?>

<!-- Layout container -->
<div class="layout-page">
    <?php require_once('learnersNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <?php
                // Display SweetAlert messages
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
                                <button type="button" class="accordion-button bg-label-primary"
                                    data-bs-toggle="collapse" data-bs-target="#accordion1" aria-expanded="true"
                                    aria-controls="accordion1">
                                    <i class="bx bx-task" style="font-size: 22px;"></i> &nbsp; Manage Assignments
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
                                                        <th><i class="bx bx-book"></i> Assignment Title</th>
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
                                                            <td class="text-center">
                                                                <button onclick="autologinAndNavigate(<?= htmlspecialchars($row['unitID']) ?>)" 
                                                                        class="btn btn-info btn-sm">
                                                                    <i class="mdi mdi-view-list"></i> Attempt Assignment
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
                                                        <th><i class="bx bx-book"></i> Assignment Title</th>
                                                        <th><i class="bx bx-category"></i> Course Category</th>
                                                        <th><i class="bx bx-cog"></i> Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td colspan="5" style="color: red; font-weight: bold;">
                                                            No course assignments assigned to this user.
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
        console.error("❌ Unit ID missing or invalid.");
        return;
    }

    // Determine environment (localhost vs production)
    const isLocal = window.location.hostname === "localhost";

    // Fetch dynamic auto-login key from PHP
    const autoLoginKey = "<?= htmlspecialchars($autoLoginToken) ?>";

    if (!autoLoginKey || autoLoginKey === 'MISSING_AUTOTOKEN') {
        alert("Auto-login key not configured for this user. Please contact support.");
        return;
    }

    // Construct URLs
    const baseAutologinUrl = isLocal 
        ? `http://localhost/evidya/www/index.php?autologin=${autoLoginKey}`
        : `https://raunakeducares.com/lxp/lxpre/www/index.php?autologin=${autoLoginKey}`;

    const testPageUrl = isLocal 
        ? `http://localhost/evidya/www/student.php?view_unit=${unitId}`
        : `https://raunakeducares.com/lxp/lxpre/www/student.php?view_unit=${unitId}`;

    // Open the test page in a new tab immediately
    const newTab = window.open("", "_blank");

    // Perform auto-login request
    fetch(baseAutologinUrl, { method: 'GET', credentials: 'include' })
        .then(response => {
            if (!response.ok) {
                throw new Error("Auto-login failed");
            }
            // Navigate to test page
            newTab.location.href = testPageUrl;
        })
        .catch(error => {
            console.error("❌ Autologin error:", error);
            newTab.close();
        });
}
</script>
