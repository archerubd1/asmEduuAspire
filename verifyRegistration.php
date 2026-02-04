<?php
/**
 * Phoenix LXP - Registration and Forgot Password Handler
 * PHP 5.4 | MySQL 5.x | Uwamp compatible
 */

include_once 'config.php';

// ------------------------------------------------------------
// 1️⃣ Registration Process
// ------------------------------------------------------------
if (isset($_POST['processType']) && $_POST['processType'] === 'RegistrationProcess') {

    // Input
    $fullname = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $mobile   = trim($_POST['mobile']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $usertype = trim($_POST['usertype']);
    $terms    = isset($_POST['terms']);

    // Validation
    if (!$terms) {
        header("Location: register.php?error=" . urlencode(base64_encode("You must agree to the terms and conditions.")));
        exit;
    }

    if ($fullname === '' || $email === '' || $mobile === '' || $username === '' || $password === '' || $usertype === '') {
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

    // Split name
    $nameParts = explode(' ', $fullname, 2);
    $firstName = $nameParts[0];
    $lastName  = isset($nameParts[1]) ? $nameParts[1] : '';

    // Check existing user
    $checkQuery = "SELECT COUNT(*) FROM users WHERE email = ? OR login = ?";
    $stmt = mysqli_prepare($coni, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ss", $email, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $exists);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($exists > 0) {
        header("Location: register.php?error=" . urlencode(base64_encode("Email or username already exists.")));
        exit;
    }

    // Password hash (eFront compatible)
    $hashedPassword = md5($password);

    // Comments JSON
    $commentsJson = json_encode(array(
        'mobile'     => $mobile,
        'source'     => 'PHX LXP Registration',
        'created_at' => date('Y-m-d H:i:s')
    ));

    $timestamp = time();
    $language  = 'en';
    $timezone  = 'UTC';

    // ------------------------------------------------------------
    // INSERT INTO USERS
    // ------------------------------------------------------------
    $insertUser = "
        INSERT INTO users
        (login, password, email, languages_NAME, timezone, name, surname, active, comments, user_type, timestamp, pending, need_pwd_change)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 0, 0)
    ";

    $stmt = mysqli_prepare($coni, $insertUser);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssi",
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

    if (!mysqli_stmt_execute($stmt)) {
        error_log("User Insert Failed: " . mysqli_error($coni));
        header("Location: register.php?error=" . urlencode(base64_encode("Registration failed.")));
        exit;
    }

    mysqli_stmt_close($stmt);

    // ------------------------------------------------------------
    // ✅ INSERT EMPTY LEARNER SHELL (THIS WAS MISSING)
    // ------------------------------------------------------------
    $userId = mysqli_insert_id($coni);

    $insertLearner = "
        INSERT INTO learners (user_id, timezone, language, status)
        VALUES (?, 'UTC', 'en', 'inactive')
    ";

    $stmt = mysqli_prepare($coni, $insertLearner);
    mysqli_stmt_bind_param($stmt, "i", $userId);

    if (!mysqli_stmt_execute($stmt)) {
        error_log("Learner Insert Failed for user_id $userId");
        header("Location: register.php?error=" . urlencode(base64_encode("Account created but learner profile failed.")));
        exit;
    }

    mysqli_stmt_close($stmt);

    // Success
    header("Location: register.php?msg=" . urlencode(base64_encode("Your account has been created successfully! Please login.")));
    exit;
}

// ------------------------------------------------------------
// 2️⃣ Forgot Password Process (UNCHANGED)
// ------------------------------------------------------------
if (isset($_POST['resetPassword']) && $_POST['processType'] === 'ForgotPassword') {

    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Invalid email address format.")));
        exit;
    }

    $stmt = mysqli_prepare($coni, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$found) {
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("No account found with that email.")));
        exit;
    }

    $token   = bin2hex(openssl_random_pseudo_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);

    $stmt = mysqli_prepare($coni, "SELECT comments FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $commentsJSON);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $comments = json_decode($commentsJSON, true);
    if (!is_array($comments)) $comments = array();

    $comments['reset_token']   = $token;
    $comments['reset_expires'] = $expires;

    $stmt = mysqli_prepare($coni, "UPDATE users SET comments = ? WHERE id = ?");
    $updated = json_encode($comments);
    mysqli_stmt_bind_param($stmt, "si", $updated, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $resetLink = $base_url . "/reset-password.php?token=" . urlencode($token);
    mail($email, "Password Reset - Phoenix LXP", "Reset your password:\n$resetLink", "From: no-reply@phxinnovates.com");

    header("Location: forgot-password.php?msg=" . urlencode(base64_encode("Password reset link sent.")));
    exit;
}

// ------------------------------------------------------------
// 3️⃣ Invalid Request
// ------------------------------------------------------------
header("Location: register.php?error=" . urlencode(base64_encode("Invalid request type.")));
exit;
