<!DOCTYPE html>
<html lang="en">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>

<!-- jQuery Easing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

<?php 
include_once '../config.php';
session_start();
$page="professor";

$user_type = $_SESSION['user_type']; // Retrieve user type
$user_name = $_SESSION['user_name']; // Retrieve user name


include_once('admin_left_nav.php');
?> 
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"><i
                          class="me-1 mdi mdi-account-multiple-plus  fs-4"
                          aria-hidden="true"
                        ></i> Upload Bulk Users</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                     User Management
                    </li>
					<li class="breadcrumb-item active" aria-current="page">
                     Upload Users
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div><br>
   

    <!-- COURSE CONCERN -->
    <section id="course-concern" class="course-concern">
        <div class="container">
 <div class="row">
    <div class="card-body">
        <h5 class="card-title"><i class="me-1 mdi mdi-format-list-bulleted-type fs-4" aria-hidden="true"></i> Choose User Role for Uploads:</h5>
        <div class="form-group row">
            <div class="col-md-12">
                <select id="userRoleSelect" class="select2 form-select shadow-none" style="width: 100%; height: 36px">
                    <option>Select</option>
                    <optgroup label="Corporate/Organization">
                        <option value="Employees">Employees</option>
                        <option value="Instructor">Instructor</option>
                        
                    </optgroup>
                </select>
            </div>
        </div>
        <br> <br>
        <!-- Download CSV Button -->
        <div id="downloadCSVContainer" style="display: none;">
            <button id="downloadCSVButton" class="btn btn-primary" onclick="downloadCSV()">Download CSV for <span id="userRoleLabelDownload"></span></button>
        </div>
        <br> <br>
        <!-- Upload CSV Input -->
        <div id="uploadCSVContainer" style="display: none;">
            <label for="csvFile">Upload dataPopulated CSV File:</label> <br>
            <input type="file" id="csvFile" class="form-control"> <br>
            <button id="uploadCSVButton" class="btn btn-success" onclick="uploadCSV()">Upload CSV for <span id="userRoleLabelUpload"></span></button>
        </div>
		<br><br>
        <!-- Display CSV Data Table -->
        <div id="tableContainer" style="display: none;">
            <h5 class="mt-4">Uploaded Data:</h5>
        </div>
    </div>
</div>


<script>
    // Function to handle CSV download
    function downloadCSV() {
        var selectedUserRole = document.getElementById('userRoleSelect').value;
        swal.fire('Downloading CSV for ' + selectedUserRole);

        // Dynamically select the appropriate function based on the user role
        switch (selectedUserRole) {
            case 'Employees':
                generateStudentCSV();
                break;

            case 'Instructor':
                generateFacultyCSV();
                break;

            default:
                swal.fire('CSV generation not implemented for ' + selectedUserRole);
        }
    }

    // Function to handle CSV upload
    function uploadCSV() {
        var selectedUserRole = document.getElementById('userRoleSelect').value;
        var csvFileInput = document.getElementById('csvFile');
        var csvFile = csvFileInput.files[0];

        swal.fire('Uploading CSV for ' + selectedUserRole);

        // Implement the logic to handle CSV upload
        // This can include using FileReader to read the contents of the uploaded CSV file

        // Example of using FileReader to read the contents
        var reader = new FileReader();
        reader.onload = function (e) {
            var csvData = e.target.result;
            // Display data in a tabular format
            displayDataInTable(csvData);
            // Ask for admin confirmation to accept and store the data
            var isConfirmed = confirm('Do you want to accept and store this data?');
            if (isConfirmed) {
                acceptData(csvData, selectedUserRole);
            }
        };
        reader.readAsText(csvFile);

        // Disable the upload button after the first click
        document.getElementById('uploadCSVButton').disabled = true;
    }

    // Function to show/hide buttons based on user role selection
    document.getElementById('userRoleSelect').addEventListener('change', function () {
        var selectedUserRole = this.value;
        var downloadContainer = document.getElementById('downloadCSVContainer');
        var uploadContainer = document.getElementById('uploadCSVContainer');
        var tableContainer = document.getElementById('tableContainer');
        var userRoleLabelDownload = document.getElementById('userRoleLabelDownload');
        var userRoleLabelUpload = document.getElementById('userRoleLabelUpload');

        // Show/hide buttons and table based on user role
        if (selectedUserRole === 'college_students' || selectedUserRole === 'college_faculty' || selectedUserRole === 'college_ofStaff' || selectedUserRole === 'college_rPersons') {
            downloadContainer.style.display = 'block';
            uploadContainer.style.display = 'block';
            tableContainer.style.display = 'none';
            userRoleLabelDownload.textContent = selectedUserRole;
            userRoleLabelUpload.textContent = selectedUserRole;
        } else {
            downloadContainer.style.display = 'none';
            uploadContainer.style.display = 'none';
            tableContainer.style.display = 'none';
        }
    });

    // Function to display CSV data in a formatted table
    function displayDataInTable(csvData) {
        var tableContainer = document.getElementById('tableContainer');
        tableContainer.innerHTML = ''; // Clear previous content

        var lines = csvData.split('\n');
        var table = document.createElement('table');
        table.className = 'table table-bordered table-responsive'; // Bootstrap table class for borders

        // Create headers
        var headers = lines[0].split(',');
        var headerRow = document.createElement('tr');
        headers.forEach(function (header) {
            var th = document.createElement('th');
            th.textContent = header;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        // Create data rows
        for (var i = 1; i < lines.length; i++) {
            var data = lines[i].split(',');
            var row = document.createElement('tr');
            data.forEach(function (cell) {
                var td = document.createElement('td');
                td.textContent = cell;
                row.appendChild(td);
            });
            table.appendChild(row);
        }

        tableContainer.appendChild(table);
        tableContainer.style.display = 'block';

        // Add "Accept Data" button
        var acceptButton = document.createElement('button');
        acceptButton.className = 'btn btn-success';
        acceptButton.textContent = 'Data Accepted';
        acceptButton.onclick = function () {
            var selectedUserRole = document.getElementById('userRoleSelect').value;
            console.log('Selected User Role:', selectedUserRole);
            acceptData(csvData, selectedUserRole);
        };

        tableContainer.appendChild(acceptButton);
    }



    // Function to handle accepting data
function acceptData(csvData, userRole) {
    console.log('Accepting data for User Role:', userRole);
    var isConfirmed = confirm('Do you want to accept and store this data?');
    if (isConfirmed) {
        // Create a FormData object to send data to the server
        var formData = new FormData();
        formData.append('csvData', csvData);
        formData.append('userRole', userRole); // Add userRole to FormData

        // Log FormData for debugging
        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }

        // Use Fetch API to send data to the PHP file
        fetch('processData', {  // Correct the file name to processData.php
            method: 'POST',
            body: formData
        })
        .then(response => response.text())  // Parse the text response
        .then(data => {
            console.log('Server Response:', data);
            // You can provide additional feedback or actions as needed
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle network or other errors
        });
    }
}


    // Function to generate and download CSV for students  DISCARD on 15th JAN 
//    function generateStudentCSV() {
        // Sample student data
//        var studentsData = [
//            ["EN123", "John Doe", "john@example.com", "1234567890", "Computer Science", "Engineering", "5", "2019"],
            // Add more student data as needed
 //       ];

        // Add headers
 //       var csvContent = "enrollmentNumber,Name,email,mobile,course,stream,semester,yrofEnrollment\n";

        // Add data rows
    //    studentsData.forEach(function (row) {
     //       csvContent += row.join(",") + "\n";
     //   });

        // Create a Blob containing the CSV data
     //   var blob = new Blob([csvContent], { type: "text/csv;charset=utf-16le" });

        // Create a link element to trigger the download
     //   var link = document.createElement("a");
     //   link.href = URL.createObjectURL(blob);
     //   link.download = "students_data.csv";

        // Trigger the download
      //  link.click();
 //   }


// Function to generate and download CSV for students  MODIFIED on 15th Jan 

// Function to generate and download CSV for students
function generateStudentCSV() {
    // Sample student data
    var studentsData = [
        ["John", "Doe", "john@example.com", "EN123", "UniversityName", "CollegeName", "ProgramName", "Batch", "Department", "1234567890"],
        // Add more student data as needed
    ];

    // Add headers
    var csvContent = "FirstName,LastName,EmailID,EnrollmentNo,UniversityName,CollegeName,ProgramName,Batch,Department,ContactNumber\n";

    // Add data rows
    studentsData.forEach(function (row) {
        csvContent += row.join(",") + "\n";
    });

    // Create a Blob containing the CSV data
    var blob = new Blob([csvContent], { type: "text/csv;charset=utf-16le" });

    // Create a link element to trigger the download
    var link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "students_data.csv";

    // Trigger the download
    link.click();
}








    // Function to generate and download CSV for faculty
    function generateFacultyCSV() {
        // Sample faculty data
        var facultyData = [
            ["FAC456", "Jane Smith", "jane@example.com", "9876543210", "Professor", "Computer Science"],
            // Add more faculty data as needed
        ];

        // Add headers
        var csvContent = "employeeId,name,email,mobile,designation,department\n";

        // Add data rows
        facultyData.forEach(function (row) {
            csvContent += row.join(",") + "\n";
        });

        // Create a Blob containing the CSV data
        var blob = new Blob([csvContent], { type: "text/csv;charset=utf-16le" });

        // Create a link element to trigger the download
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "faculty_data.csv";

        // Trigger the download
        link.click();
    }

    // Function to generate and download CSV for office staff
    function generateOfficeStaffCSV() {
        // Sample office staff data
        var officeStaffData = [
            ["OF789", "Alice Johnson", "alice@example.com", "5556667777", "Administrative Assistant", "HR"],
            // Add more office staff data as needed
        ];

        // Add headers
        var csvContent = "employeeId,name,email,mobile,designation,officeRole\n";

        // Add data rows
        officeStaffData.forEach(function (row) {
            csvContent += row.join(",") + "\n";
        });

        // Create a Blob containing the CSV data
        var blob = new Blob([csvContent], { type: "text/csv;charset=utf-16le" });

        // Create a link element to trigger the download
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "office_staff_data.csv";

        // Trigger the download
        link.click();
    }

    // Function to generate and download CSV for resource persons
    function generateResourcePersonsCSV() {
        // Sample resource persons data
        var resourcePersonsData = [
            ["Bob Thompson", "bob@example.com", "4443332222", "Data Science", "Machine Learning", "Workshops"],
            // Add more resource persons data as needed
        ];

        // Add headers
        var csvContent = "name,email,mobile,domain,technology,seminar_or_workshops_or_visiting_faculty\n";

        // Add data rows
        resourcePersonsData.forEach(function (row) {
            csvContent += row.join(",") + "\n";
        });

        // Create a Blob containing the CSV data
        var blob = new Blob([csvContent], { type: "text/csv;charset=utf-16le" });

        // Create a link element to trigger the download
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "resource_persons_data.csv";

        // Trigger the download
        link.click();
    }
</script>



	<br>		
	
			
			  <div><p>
			  <br><br><br><br>
			  </div>
            </div>
        </div>
    </section>
    <!-- END / COURSE CONCERN -->

 



</div>
<!-- END / PAGE WRAP -->

	
   <?php include_once('../glxp_learners/learners_footer.php'); ?>
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>