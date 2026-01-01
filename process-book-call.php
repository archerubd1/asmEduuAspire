<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: book-a-call.php");
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header("Location: book-a-call.php?error=".urlencode(base64_encode("Invalid request.")));
    exit;
}


$stmt = $coni->prepare("
INSERT INTO book_strategy_calls
(full_name,email,phone,organization,role,interest_area,message,source_page,ip_address)
VALUES (?,?,?,?,?,?,?,?,?)
");

$ipAddress = !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
    ? $_SERVER['HTTP_X_FORWARDED_FOR']
    : $_SERVER['REMOTE_ADDR'];

$stmt->bind_param(
  "sssssssss",
  $_POST['full_name'],
  $_POST['email'],
  $_POST['phone'],
  $_POST['organization'],
  $_POST['role'],
  $_POST['interest_area'],
  $_POST['message'],
  $_POST['source_page'],
  $ipAddress
);

if ($stmt->execute()) {
    $msg = base64_encode("Our EdTech team will contact you shortly.");
    header("Location: book-a-call.php?msg=".urlencode($msg));
} else {
    $err = base64_encode("Unable to submit request. Please try again.");
    header("Location: book-a-call.php?error=".urlencode($err));
}

$stmt->close();
$coni->close();
exit;
