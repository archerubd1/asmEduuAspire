<?php
// Database connection
$conn = new mysqli("localhost", "root", "root", "phxinno");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data safely
$domain  = isset($_POST['internshipDomain']) ? $conn->real_escape_string($_POST['internshipDomain']) : '';
$name    = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
$email   = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$phone   = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
$message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

// File upload
$resumePath = "";
if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $uploadDir = __DIR__ . "/uploads/resumes/"; // absolute path for safety
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $_FILES['resume']['name']);
    $resumePath = "uploads/resumes/" . $fileName;

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $uploadDir . $fileName)) {
        // success
    } else {
        echo "<script>alert('Resume upload failed. Please try again.'); window.history.back();</script>";
        exit;
    }
}

// Save to DB
$sql = "INSERT INTO internship_applications (domain, name, email, phone, resume, message) 
        VALUES ('$domain', '$name', '$email', '$phone', '$resumePath', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Application submitted successfully!'); window.location='intern-opportunities.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
