<?php
/**
 * Phoenix LXP - User Login Verification
 * Compatible with eFront 3.15 'users' table
 */

require_once('config.php');

// -----------------------------------------------------------------------------
// 1️⃣ Validate DB Connection
// -----------------------------------------------------------------------------
if (!isset($coni) || !$coni) {
    die("Database connection failed: " . mysqli_connect_error());
}

// -----------------------------------------------------------------------------
// 2️⃣ Verify Login Request
// -----------------------------------------------------------------------------
if (isset($_POST['newWorkflowLOGIN']) && isset($_POST['processLogin']) && $_POST['processLogin'] === 'loginProcess') {

    // Optional CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: phxlogin.php?error=" . urlencode(base64_encode("Invalid session token. Please try again.")));
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === '' || $password === '') {
        header("Location: phxlogin.php?error=" . urlencode(base64_encode("Please enter both Username and Password.")));
        exit;
    }

    // -------------------------------------------------------------------------
    // 3️⃣ Hash password (eFront uses MD5)
    // -------------------------------------------------------------------------
    $hashedPassword = md5($password);

    // -------------------------------------------------------------------------
    // 4️⃣ Query eFront users table
    // -------------------------------------------------------------------------
    $sql = "SELECT id, login, email, name, surname, user_type, active 
            FROM users 
            WHERE (login = ? OR email = ?) 
              AND password = ? 
            LIMIT 1";
    $stmt = mysqli_prepare($coni, $sql);

    if (!$stmt) {
        error_log("DB Error (verifyLogin SELECT): " . mysqli_error($coni));
        header("Location: phxlogin.php?error=" . urlencode(base64_encode("Database error. Please try again later.")));
        exit;
    }

    mysqli_stmt_bind_param($stmt, "sss", $username, $username, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // -------------------------------------------------------------------------
    // 5️⃣ Credentials match
    // -------------------------------------------------------------------------
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $user_id, $loginName, $email, $fname, $lname, $userType, $active);
        mysqli_stmt_fetch($stmt);

        // Inactive account
        if ((int)$active !== 1) {
            header("Location: phxlogin.php?error=" . urlencode(base64_encode("Your account is inactive. Please contact support.")));
            exit;
        }

        // Prevent session fixation
        session_regenerate_id(true);

        // -----------------------------------------------------
        // 6️⃣ Set secure session variables
        // -----------------------------------------------------
        $_SESSION['phx_user_id']    = $user_id;
        $_SESSION['phx_user_login'] = $loginName;
        $_SESSION['phx_user_name']  = trim($fname . ' ' . $lname);
        $_SESSION['phx_user_email'] = $email;
        $_SESSION['phx_user_type']  = strtolower(trim($userType));
        $_SESSION['phx_logged_in']  = true;
        $_SESSION['login_time']     = time();

        // -----------------------------------------------------
        // 7️⃣ Redirect by user role
        // -----------------------------------------------------
        $redirect = "lxp/marketplace/dashboard.php"; // default

        switch ($_SESSION['phx_user_type']) {
            case 'student':
                $redirect = "lxp/platformLearner/learnerDashboard.php";
                break;
            case 'professor':
                $redirect = "lxp/platformInstructor/instructorDashboard.php";
                break;
            case 'professional':
                $redirect = "lxp/professional/dashboard.php";
                break;
            case 'corporate':
                $redirect = "lxp/corporate/dashboard.php";
                break;
            case 'institute':
                $redirect = "lxp/institute/dashboard.php";
                break;
				 case 'coordinator':
                $redirect = "lxp/professional/dashboard.php";
                break;
            case 'platformadmin':
                $redirect = "lxp/corporate/dashboard.php";
                break;
            case 'deptadmin':
                $redirect = "lxp/deptadmin/dashboard.php";
                break;
        }

        header("Location: " . $redirect);
        exit;

    } else {
        header("Location: phxlogin.php?error=" . urlencode(base64_encode("Invalid username or password.")));
        exit;
    }

} else {
    header("Location: phxlogin.php?error=" . urlencode(base64_encode("Invalid request. Please login again.")));
    exit;
}
?>
