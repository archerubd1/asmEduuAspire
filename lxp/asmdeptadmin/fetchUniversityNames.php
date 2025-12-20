<?php

// Include your configuration file
include_once '../config.php';

// Start the session (if not already started)
session_start();

$user_type = $_SESSION['user_type']; // Retrieve user type
$user_name = $_SESSION['user_name']; // Retrieve user name

// This script fetches university names based on the selected user role
// Fetch university names based on the created_by field

// Assume $coni is your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userRole'])) {
    // Fetch university names based on the created_by field
    $userRole = $_POST['userRole'];
    $createdBy = $user_name; // Assuming user_name is stored in the session

    $query = "SELECT DISTINCT university_name FROM geeqbulkstudents WHERE created_by = ?";
    $stmt = $coni->prepare($query);

    if ($stmt === false) {
        $response = array("success" => false, "message" => "Error preparing query: " . $mysqli->error);
    } else {
        $stmt->bind_param('s', $createdBy);

        if (!$stmt->execute()) {
            $response = array("success" => false, "message" => "Error executing query: " . $stmt->error);
        } else {
            $stmt->bind_result($universityName);

            $universities = array();
            while ($stmt->fetch()) {
                $universities[] = $universityName;
            }

            $response = array("success" => true, "data" => $universities);
        }

        $stmt->close();
    }

    // Return the response as JSON
    echo json_encode($response);
} else {
    // Return an error response if userRole is not provided
    echo json_encode(array("success" => false, "message" => "User role not provided"));
}



?>
