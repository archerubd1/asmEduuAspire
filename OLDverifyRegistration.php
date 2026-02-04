<?php
/**
 * Phoenix LXP - Registration and Forgot Password Handler
 * Updated for eFront 3.15 users table
 */

include_once 'config.php';

// ------------------------------------------------------------
// 1️⃣ Registration Process
// ------------------------------------------------------------
if (isset($_POST['processType']) && $_POST['processType'] === 'RegistrationProcess') {

    // Retrieve and sanitize input
    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $usertype = trim($_POST['usertype']);
    $terms = isset($_POST['terms']);

    // ✅ Validation checks
    if (!$terms) {
        header("Location: register.php?error=" . urlencode(base64_encode("You must agree to the terms and conditions.")));
        exit;
    }

    if (empty($fullname) || empty($email) || empty($mobile) || empty($username) || empty($password) || empty($usertype)) {
        header("Location: register.php?error=" . urlencode(base64_encode("All fields are required.")));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=" . urlencode(base64_encode("Invalid email format.")));
        exit;
    }

    if (!preg_match('/^\d{10}$/', $mobile)) {
        header("Location: register.php?error=" . urlencode(base64_encode("Invalid mobile number. Must be 10 digits.")));
        exit;
    }

    // ✅ Split full name into first and last name
    $nameParts = explode(' ', $fullname, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // ✅ Check for existing username or email
    $checkQuery = "SELECT COUNT(*) AS count FROM users WHERE email = ? OR login = ?";
    $stmt = mysqli_prepare($coni, $checkQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existingCount);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($existingCount > 0) {
            header("Location: register.php?error=" . urlencode(base64_encode("Email or username already exists.")));
            exit;
        }
    } else {
        error_log("Database Query Error (CHECK): " . mysqli_error($coni));
        header("Location: register.php?error=" . urlencode(base64_encode("Database validation error.")));
        exit;
    }

    // ✅ Hash password (MD5 - eFront compatible)
    $hashedPassword = md5($password);

    // ✅ Prepare JSON comments (store mobile, registration metadata)
    $commentsArray = array(
        'mobile' => $mobile,
        'source' => 'PHX LXP Registration',
        'created_at' => date('Y-m-d H:i:s')
    );
    $commentsJson = json_encode($commentsArray);

    // ✅ UNIX timestamp for registration
    $timestamp = time();

    // ✅ Default language and timezone
    $language = 'en';
    $timezone = 'UTC';

    // ✅ Insert into users table
    $insertQuery = "INSERT INTO users 
        (login, password, email, languages_NAME, timezone, name, surname, active, comments, user_type, timestamp, pending, need_pwd_change)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 0, 0)";
    
    $stmt = mysqli_prepare($coni, $insertQuery);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssssi",
            $username,
            $hashedPassword,
            $email,
            $language,
            $timezone,
            $firstName,
            $lastName,
            $commentsJson,
            $usertype,
            $timestamp
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: register.php?msg=" . urlencode(base64_encode("Your account has been created successfully! Please login.")));
            exit;
        } else {
            error_log("Database Insert Error: " . mysqli_error($coni));
            header("Location: register.php?error=" . urlencode(base64_encode("We encountered a technical issue. Please try again later.")));
            exit;
        }
    } else {
        error_log("Database Query Error (INSERT): " . mysqli_error($coni));
        header("Location: register.php?error=" . urlencode(base64_encode("Database operation failed.")));
        exit;
    }
}

// ------------------------------------------------------------
// 2️⃣ Forgot Password Process
// ------------------------------------------------------------
if (isset($_POST['resetPassword']) && $_POST['processType'] === 'ForgotPassword') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Invalid email address format.")));
        exit;
    }

    // ✅ Check if the email exists in users table
    $checkQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($coni, $checkQuery);
    if (!$stmt) {
        error_log("DB Query Error (ForgotPassword SELECT): " . mysqli_error($coni));
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Database error. Please try again later.")));
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId);
    $userExists = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$userExists) {
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("No account found with that email.")));
        exit;
    }

    // ✅ Generate reset token & expiry (1 hour)
    $token = bin2hex(openssl_random_pseudo_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);

    // ✅ Store reset token in comments field (as JSON)
    $fetchCommentsQuery = "SELECT comments FROM users WHERE id = ?";
    $stmt = mysqli_prepare($coni, $fetchCommentsQuery);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $commentsJSON);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $comments = json_decode($commentsJSON, true);
    if (!is_array($comments)) $comments = [];
    $comments['reset_token'] = $token;
    $comments['reset_expires'] = $expires;

    $updatedComments = json_encode($comments);

    $updateQuery = "UPDATE users SET comments = ? WHERE id = ?";
    $stmt = mysqli_prepare($coni, $updateQuery);
    mysqli_stmt_bind_param($stmt, "si", $updatedComments, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // ✅ Send password reset email
    $resetLink = $base_url . "/reset-password.php?token=" . urlencode($token);
    $to = $email;
    $subject = "Password Reset Request - Phoenix LXP";
    $message = "Hello,\n\nWe received a request to reset your Phoenix LXP account password.\n";
    $message .= "Click the link below to reset it:\n$resetLink\n\n";
    $message .= "If you didn’t request this, please ignore this message.\n\nRegards,\nPhoenix LXP Team";
    $headers = "From: no-reply@phxinnovates.com";

    if (mail($to, $subject, $message, $headers)) {
        header("Location: forgot-password.php?msg=" . urlencode(base64_encode("A password reset link has been sent to your email.")));
        exit;
    } else {
        error_log("Email sending failed for $email");
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Failed to send reset email. Try again later.")));
        exit;
    }
}

// ------------------------------------------------------------
// 3️⃣ Fallback - Invalid Request
// ------------------------------------------------------------
header("Location: register.php?error=" . urlencode(base64_encode("Invalid request type.")));
exit;
?>
