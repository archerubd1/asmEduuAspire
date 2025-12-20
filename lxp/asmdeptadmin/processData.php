<<?php

// Include your configuration file
include_once '../config.php';

// Start the session (if not already started)
session_start();

$user_type = $_SESSION['user_type']; // Retrieve user type
$user_name = $_SESSION['user_name']; // Retrieve user name


try {
    // Check if the CSV data and user role are sent in the request
    if (isset($_POST['csvData']) && isset($_POST['userRole'])) {
        $csvData = $_POST['csvData'];
        $userRole = $_POST['userRole'];

        // Process and store the CSV data in the corresponding table
        try {
            // Define your database connection variable
            // Replace $coni with your actual database connection variable
            if (!$coni) {
                echo json_encode(['success' => false, 'error' => 'Database connection error: ' . mysqli_connect_error()]);
                exit;
            }

            // Process data based on user role
            switch ($userRole) {
                case 'college_students':
                    processStudentData($csvData, $coni);
                    break;

                case 'college_faculty':
                    processFacultyData($csvData, $coni);
                    break;

                case 'college_ofStaff':
                    processOfficeStaffData($csvData, $coni);
                    break;

                case 'college_rPersons':
                    processResourcePersonsData($csvData, $coni);
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

// Function to process faculty data
function processFacultyData($csvData, $coni) {
    // Define your SQL statement for faculty data
    $sql = "INSERT INTO faculty_table (instituteID, instituteName, employeeId, name, email, mobile, designation, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($coni, $sql);

    // Check if the statement is prepared successfully
    if ($stmt === false) {
        die(mysqli_error($coni));
    }

    // Bind parameters and execute the prepared statement
    mysqli_stmt_bind_param($stmt, 'isssssss', $instituteID, $instituteName, $employeeId, $name, $email, $mobile, $designation, $department);

    // Process each CSV row, starting from the second row
    $rows = explode("\n", $csvData);
    foreach ($rows as $key => $row) {
        // Skip the first row (header)
        if ($key === 0) {
            continue;
        }

        $data = str_getcsv($row);
        if (count($data) === 6) { // Assuming 7 columns in CSV
            list($employeeId, $name, $email, $mobile, $designation, $department) = $data;

            // Add instituteID and instituteName to data (replace these with actual values)
            $instituteID = null;
            $instituteName = 'your_institute_name';

            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                die(mysqli_stmt_error($stmt));
            }
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Return success message
    return json_encode(array("success" => true, "message" => "Data stored successfully"));
}


// Function to process office staff data
function processOfficeStaffData($csvData, $coni) {
    // Define your SQL statement for office staff data
    $sql = "INSERT INTO office_staff_table (instituteID, instituteName, employeeId, name, email, mobile, designation, officeRole) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($coni, $sql);

    // Check if the statement is prepared successfully
    if ($stmt === false) {
        die(mysqli_error($coni));
    }

    // Bind parameters and execute the prepared statement
    mysqli_stmt_bind_param($stmt, 'isssssss', $instituteID, $instituteName, $employeeId, $name, $email, $mobile, $designation, $officeRole);

    // Process each CSV row, starting from the second row
    $rows = explode("\n", $csvData);
    foreach ($rows as $key => $row) {
        // Skip the first row (header)
        if ($key === 0) {
            continue;
        }

        $data = str_getcsv($row);
        if (count($data) === 6) { // Assuming 7 columns in CSV
            list($employeeId, $name, $email, $mobile, $designation, $officeRole) = $data;

            // Add instituteID and instituteName to data (replace these with actual values)
            $instituteID = null;
            $instituteName = 'your_institute_name';

            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                die(mysqli_stmt_error($stmt));
            }
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Return success message
    return json_encode(array("success" => true, "message" => "Data stored successfully"));
}


// Function to process resource persons data
function processResourcePersonsData($csvData, $coni) {
    // Define your SQL statement for resource persons data
    $sql = "INSERT INTO resource_persons_table (instituteID, instituteName, name, email, mobile, domain, technology, seminar_or_workshops_or_visiting_faculty) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($coni, $sql);

    // Bind parameters and execute the prepared statement
    mysqli_stmt_bind_param($stmt, 'isssssss', $instituteID, $instituteName, $name, $email, $mobile, $domain, $technology, $seminar_or_workshops_or_visiting_faculty);

    // Process each CSV row, starting from the second row
    $rows = explode("\n", $csvData);
    foreach ($rows as $key => $row) {
        // Skip the first row (header)
        if ($key === 0) {
            continue;
        }

        $data = str_getcsv($row);
        if (count($data) === 6) { // Assuming 7 columns in CSV
            list($name, $email, $mobile, $domain, $technology, $seminar_or_workshops_or_visiting_faculty) = $data;

            // Add instituteID and instituteName to data (replace these with actual values)
            $instituteID = null;
            $instituteName = 'your_institute_name';

            mysqli_stmt_execute($stmt);
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}



/////////////////////////////////  15th Jan REWORK for INSTITUTE STUDENTS BULK UPLOAD /////////////////////////////
////////////////////////




// Function to process student data
function processStudentData($csvData, $coni) {
    // Define your SQL statement for student data
    $sql = "INSERT INTO geeqbulkstudents (first_name, last_name, email, enrollment_no, university_name, college_name, program_name, batch_year, department, contact_number, created_by, institute_id, date_of_creation, profile_updated_status, validity_period_from, validity_period_to, login, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($coni, $sql);

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
        if (count($data) === 10) { // Assuming 10 columns in CSV
            list($first_name, $last_name, $email, $enrollment_no, $university_name, $college_name, $program_name, $batch_year, $department, $contact_number) = $data;

            // Add created_by, institute_id, and other default values to data
            $created_by = $_SESSION['user_name'];

            // Get belongs_to from geeqUsers table
            $getBelongsToQuery = "SELECT belongs_to FROM geeqUsers WHERE generatedUsername = ?";
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
            $password = 'GeeqLXP@123'; // Set your default value

            // Bind parameters and execute the prepared statement
            mysqli_stmt_bind_param($stmt, 'ssssssssssssssssss', $first_name, $last_name, $email, $enrollment_no, $university_name, $college_name, $program_name, $batch_year, $department, $contact_number, $created_by, $institute_id, $date_of_creation, $profile_updated_status, $validity_period_from, $validity_period_to, $login, $password);

            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                error_log(mysqli_stmt_error($stmt));
                return json_encode(array("success" => false, "message" => "Error executing SQL statement"));
            }

            // Insert additional data into geequsers table
            $userType = 'learner';
            $clientRole = 'Institute';
            $generatedUsername = $login;
            $password = $password;
            $created_by = $created_by;
            $belongs_to = $institute_id;

            $insertUserQuery = "INSERT INTO geequsers (userType, clientRole, generatedUsername, password, created_by, belongs_to) VALUES (?, ?, ?, ?, ?, ?)";
            $insertUserStmt = mysqli_prepare($coni, $insertUserQuery);

            if ($insertUserStmt === false) {
                error_log(mysqli_error($coni));
                return json_encode(array("success" => false, "message" => "Error preparing insertUser query"));
            }

            mysqli_stmt_bind_param($insertUserStmt, 'ssssss', $userType, $clientRole, $generatedUsername, $password, $created_by, $belongs_to);

            if (!mysqli_stmt_execute($insertUserStmt)) {
                error_log(mysqli_stmt_error($insertUserStmt));
                return json_encode(array("success" => false, "message" => "Error executing insertUser query"));
            }

            mysqli_stmt_close($insertUserStmt);
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Return success message
    return json_encode(array("success" => true, "message" => "Data stored successfully"));
}





?>
