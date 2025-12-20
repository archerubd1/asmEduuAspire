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
                        ></i> Enable Access & Platform Usage</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                     User Management
                    </li>
					<li class="breadcrumb-item active" aria-current="page">
                     Enable Access
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

    <div class="card-body">
        <h5 class="card-title"><i class="me-1 mdi mdi-format-list-bulleted-type fs-4" aria-hidden="true"></i> Choose User Role to Enable Access:</h5>
       <div class="row">
            <div class="col-md-6">
                <select id="userRoleSelect" class="form-select shadow-none" style="width: 100%; height: 36px">
                    <option>Select userRole</option>
                    <optgroup label="College/Institute">
                        <option value="college_students">Students</option>
                        <!-- Add other role options as needed -->
                    </optgroup>
                </select>
            </div>
        <!-- Second dropdown for university names -->
       
            <div class="col-md-6">
                <select id="universitySelect" class="form-select shadow-none" style="width: 100%; height: 36px" disabled>
                    <option>Select University</option>
                    <!-- Options will be dynamically populated using JavaScript -->
                </select>
            </div>
       
    </div>
</div>

<script>
    // When userRoleSelect changes, fetch and populate university names
    document.getElementById('userRoleSelect').addEventListener('change', function () {
        // Get selected userRole
        var userRole = this.value;

        // Disable universitySelect initially
        document.getElementById('universitySelect').disabled = true;

        // Check if a valid userRole is selected
        if (userRole !== 'Select') {
            // Fetch university names based on userRole
            fetchUniversityNames(userRole);
        }
    });

    // Function to fetch and populate university names
    function fetchUniversityNames(userRole) {
        // Fetch data using Fetch API
        fetch('fetchUniversityNames', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'userRole=' + userRole,
        })
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            // Check for success and update the universitySelect options
            if (data.success) {
                var universitySelect = document.getElementById('universitySelect');
                universitySelect.innerHTML = '<option>Select</option>';

                // Populate university options
                data.data.forEach(function (university) {
                    var option = document.createElement('option');
                    option.value = university;
                    option.text = university;
                    universitySelect.appendChild(option);
                });

                // Enable universitySelect
                universitySelect.disabled = false;
            } else {
                console.error('Error fetching university names:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching university names:', error);
        });
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

</body>
</html>