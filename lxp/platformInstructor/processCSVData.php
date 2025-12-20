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



try {
    // Check if the CSV data and user role are sent in the request
    if (isset($_POST['csvData']) && isset($_POST['userRole'])) {
        $csvData = $_POST['csvData'];
        $userRole = $_POST['userRole'];



        // Process and store the CSV data in the corresponding table
        try {
            if (!$coni) {
                echo json_encode(['success' => false, 'error' => 'Database connection error: ' . mysqli_connect_error()]);
                exit;
            }

            // Process data based on user role
            switch ($userRole) {
                case 'students':
                    processStudentData($csvData, $coni);
                    break;

                default:
                    echo json_encode(['success' => false, 'error' => 'Unrecognized user role']);
                    exit;
            }

            // Send a success response
            echo json_encode(['success' => true, 'message' => 'Data stored successfully']);
        } catch (Exception $e) {
            // Send an error response
            echo json_encode(['success' => false, 'error' => 'Failed to store data: ' . $e->getMessage()]);
        }
    } else {
        // Send an error response if CSV data or user role is not provided
        echo json_encode(['success' => false, 'error' => 'CSV data or user role not provided']);
    }
} catch (Exception $e) {
    // Handle general exceptions
    echo json_encode(['success' => false, 'error' => 'An unexpected error occurred: ' . $e->getMessage()]);
    die();
}




/////////////////////////////////  15th Jan REWORK for INSTITUTE STUDENTS BULK UPLOAD /////////////////////////////
//////////////////////// 18th JAN DUPLICATES & COUNTS 

// Function to process student data
function processStudentData($csvData, $coni) {
    // Define your SQL statement for student data
    $sql = "INSERT INTO geeqstudent (first_name, last_name, email, enrollment_no, university_name, college_name, programCode, program_name, batch_year, department, contact_number, created_by, institute_id, date_of_creation, profile_updated_status, validity_period_from, validity_period_to, login, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($coni, $sql);

	// Initialize counts
    $recordsRead = 0;
    $duplicatesFound = 0;
    $successfulInsertions = 0;
	
    // Check if the statement is prepared successfully
    if ($stmt === false) {
        error_log(mysqli_error($coni));
        return json_encode(array("success" => false, "message" => "Error preparing SQL statement"));
    }

			// Process each CSV row, starting from the second row
		$rows = explode("\n", $csvData);
		foreach ($rows as $key => $row) {
			// Skip the first row (header)
			if ($key === 0) {
				continue;
			}

			// Debug information
			error_log("Processing Row: $key");

			$data = str_getcsv($row);
			if (count($data) === 11) { // Assuming 11 columns in CSV
				$recordsRead++;

        list($first_name, $last_name, $email, $enrollment_no, $university_name, $college_name, $programCode, $program_name, $batch_year, $department, $contact_number) = $data;

        // Add created_by, institute_id, and other default values to data
        $created_by = $_SESSION['user_name'];

			// Check for duplicates in geeqstudent table
			$checkStudentDuplicateQuery = "SELECT COUNT(*) FROM geeqstudent WHERE enrollment_no = ?";
			$checkStudentDuplicateStmt = mysqli_prepare($coni, $checkStudentDuplicateQuery);
			mysqli_stmt_bind_param($checkStudentDuplicateStmt, 's', $enrollment_no);
			mysqli_stmt_execute($checkStudentDuplicateStmt);
			mysqli_stmt_bind_result($checkStudentDuplicateStmt, $studentDuplicateCount);
			mysqli_stmt_fetch($checkStudentDuplicateStmt);
			mysqli_stmt_close($checkStudentDuplicateStmt);

			if ($studentDuplicateCount > 0) {
				// Duplicate found in geeqstudent, insert into geeqStudentDuplicates
				$insertDuplicateQuery = "INSERT INTO geeqduplicates (enrollment_no, first_name, last_name, email, contact_number, institute_id, attempted_by, date_time, usertype) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'learner')";
				$insertDuplicateStmt = mysqli_prepare($coni, $insertDuplicateQuery);

				if ($insertDuplicateStmt === false) {
					error_log(mysqli_error($coni));
					return json_encode(array("success" => false, "message" => "Error preparing insertDuplicate query"));
				}

				// Get additional data for duplicate entry
				$attempted_by = $_SESSION['user_name'];
				$institute_id = $belongs_to;

				mysqli_stmt_bind_param($insertDuplicateStmt, 'sssssss', $enrollment_no, $first_name, $last_name, $email, $contact_number, $institute_id, $attempted_by);

				if (!mysqli_stmt_execute($insertDuplicateStmt)) {
					error_log(mysqli_stmt_error($insertDuplicateStmt));
					return json_encode(array("success" => false, "message" => "Error executing insertDuplicate query"));
				}

				mysqli_stmt_close($insertDuplicateStmt);

				// Skip insertion into geeqstudent
				$duplicatesFound++;
				continue;
			}

            // Add created_by, institute_id, and other default values to data
            $created_by = $_SESSION['user_name'];

            // Get belongs_to from geequsers table
            $getBelongsToQuery = "SELECT belongs_to FROM geequsers WHERE generatedUsername = ?";
            $getBelongsToStmt = mysqli_prepare($coni, $getBelongsToQuery);

            if ($getBelongsToStmt === false) {
                error_log(mysqli_error($coni));
                return json_encode(array("success" => false, "message" => "Error preparing belongs_to query"));
            }

            mysqli_stmt_bind_param($getBelongsToStmt, 's', $created_by);

            if (!mysqli_stmt_execute($getBelongsToStmt)) {
                error_log(mysqli_stmt_error($getBelongsToStmt));
                return json_encode(array("success" => false, "message" => "Error executing belongs_to query"));
            }

            mysqli_stmt_bind_result($getBelongsToStmt, $belongs_to);

            // Fetch the result
            mysqli_stmt_fetch($getBelongsToStmt);

            // Close the statement
            mysqli_stmt_close($getBelongsToStmt);

            // Now you have $belongs_to, use it to populate institute_id
            $institute_id = $belongs_to;

            $date_of_creation = date("Y-m-d H:i:s");
            $profile_updated_status = 0; // Set your default value
            $validity_period_from = null; // Set your default value
            $validity_period_to = null; // Set your default value
            $login = $enrollment_no; // Set login to the enrollment number
            $password = 'geeQlxp@123'; // Set your default value

			// Extract additional fields
            $fname = $first_name;
            $lname = $last_name;
            $mobile = $contact_number;
            $username = $enrollment_no; 


				
            // Bind parameters and execute the prepared statement
            mysqli_stmt_bind_param($stmt, 'sssssssssssssssssss', $first_name, $last_name, $email, $enrollment_no, $university_name, $college_name, $programCode, $program_name, $batch_year, $department, $contact_number, $created_by, $institute_id, $date_of_creation, $profile_updated_status, $validity_period_from, $validity_period_to, $login, $password);

            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                error_log(mysqli_stmt_error($stmt));
                return json_encode(array("success" => false, "message" => "Error executing SQL statement"));
            }

			$successfulInsertions++;
			
			
			  // Check for duplicates in geequsers table
                $checkUserDuplicateQuery = "SELECT COUNT(*) FROM geequsers WHERE generatedUsername = ?";
                $checkUserDuplicateStmt = mysqli_prepare($coni, $checkUserDuplicateQuery);
                mysqli_stmt_bind_param($checkUserDuplicateStmt, 's', $login);
                mysqli_stmt_execute($checkUserDuplicateStmt);
                mysqli_stmt_bind_result($checkUserDuplicateStmt, $userDuplicateCount);
                mysqli_stmt_fetch($checkUserDuplicateStmt);
                mysqli_stmt_close($checkUserDuplicateStmt);


                if ($userDuplicateCount > 0) {
                    // Duplicate found in geequsers, skip insertion
                    continue;
                }
				
            // Insert additional data into geequsers table
            $userType = 'learner';
            $clientRole = 'Institute';
            $generatedUsername = $login;
            $password = $password;
            $created_by = $created_by;
            $belongs_to = $institute_id;

            // Define your SQL statement for geequser data
            $insertUserQuery = "INSERT INTO geequsers (userType, clientRole, generatedUsername, password, created_by, belongs_to, fname, lname, email, mobile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertUserStmt = mysqli_prepare($coni, $insertUserQuery);

            // Prepare the statement for geequsers
            if ($insertUserStmt === false) {
                error_log(mysqli_error($coni));
                return json_encode(array("success" => false, "message" => "Error preparing insertUser query for geequsers"));
            }

            // Bind parameters for geequsers
            mysqli_stmt_bind_param($insertUserStmt, 'ssssssssss', $userType, $clientRole, $generatedUsername, $password, $created_by, $belongs_to, $fname, $lname, $email, $mobile);

            // Execute the statement for geequsers
            if (!mysqli_stmt_execute($insertUserStmt)) {
                error_log(mysqli_stmt_error($insertUserStmt));
                return json_encode(array("success" => false, "message" => "Error executing insertUser query for geequsers"));
            }

            mysqli_stmt_close($insertUserStmt);
			
				
        }
    }
	
    // Close the statement
    mysqli_stmt_close($stmt);
	
   // Return counts in the response
    $response = array(
        "success" => true,
        "recordsRead" => $recordsRead,
        "duplicatesFound" => $duplicatesFound,
        "successfulInsertions" => $successfulInsertions,
		"message" => "Student Data Processed successfully!!",
    );

    // Clean the output buffer before sending the JSON response
    ob_clean();

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Terminate the script after sending the response
}



?>
