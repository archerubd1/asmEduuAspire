<?php 
include_once 'config.php';



// forgot-password-process.php

if (isset($_POST['resetPassword']) && $_POST['processType'] === 'ForgotPassword') {
    // Include database connection
    require_once 'config.php'; // Ensure $coni is defined here

    // Retrieve and sanitize email input
    $email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Invalid email address format.")));
        exit;
    }

    // Check if the email exists in the database
    $query = "SELECT id FROM self_registered_users WHERE email = ?";
    $stmt = mysqli_prepare($coni, $query);

    if (!$stmt) {
        error_log("Database Query Error (SELECT PREPARE): " . mysqli_error($coni));
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Database error. Please try again later.")));
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId);
    $userExists = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$userExists) {
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("No account found with that email address.")));
        exit;
    }

    // Generate a secure token and expiration time (e.g., 1 hour)
	$token = bin2hex(openssl_random_pseudo_bytes(32)); // Generate a 64-character token
	$expires = date('Y-m-d H:i:s', time() + 3600); // Set expiration to 1 hour from now


    // Save the token and expiration time in the database
    $query = "UPDATE self_registered_users SET reset_token = ?, reset_expires = ? WHERE id = ?";
    $stmt = mysqli_prepare($coni, $query);

    if (!$stmt) {
        error_log("Database Query Error (UPDATE PREPARE): " . mysqli_error($coni));
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Database error for Update. Please try again later.")));
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $token, $expires, $userId);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Database Query Error (UPDATE EXECUTE): " . mysqli_error($coni));
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Failed to update reset token. Please try again later.")));
        exit;
    }
    mysqli_stmt_close($stmt);

    // Prepare the reset link
    $resetLink = "https://phxinnovates.com/reset-password.php?token=" . urlencode($token);

    // Send the reset link via email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Hi,\n\nWe received a request to reset your password. Click the link below to reset your password:\n\n$resetLink\n\nIf you didn't request a password reset, you can safely ignore this email.\n\nThanks,\nYour Team";
    $headers = "From: no-reply@phxinnovates.com";

    if (mail($to, $subject, $message, $headers)) {
        header("Location: forgot-password.php?msg=" . urlencode(base64_encode("A password reset link has been sent to your email.")));
        exit;
    } else {
        error_log("Failed to send email to $email.");
        header("Location: forgot-password.php?error=" . urlencode(base64_encode("Failed to send email. Please try again later.")));
        exit;
    }
} else {
    // Invalid request method
    header("Location: forgot-password.php?error=" . urlencode(base64_encode("Invalid request.")));
    exit;
}



?>