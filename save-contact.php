<?php
// Database connection
$conn = new mysqli("localhost", "root", "root", "phxinno");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$name    = isset($_POST['userName']) ? $conn->real_escape_string($_POST['userName']) : '';
$email   = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$phone   = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
$message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

// Insert into DB
$sql = "INSERT INTO contact_messages (name, email, phone, message) 
        VALUES ('$name', '$email', '$phone', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Thank you for reaching out! We will get back to you soon.'); window.location='reach-us.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
