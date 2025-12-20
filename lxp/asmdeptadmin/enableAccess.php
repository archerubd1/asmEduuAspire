<?php 
include_once '../config.php';
session_start();
$page="professor";

$user_type = $_SESSION['user_type']; // Retrieve user type
$user_name = $_SESSION['user_name']; // Retrieve user name


include_once('admin_left_nav.php');




// SQL query to retrieve university wise data 
$query = "SELECT
    gs.university_name,
    COUNT(DISTINCT CASE WHEN gu.userType = 'learner' THEN gs.login END) AS total_learners,
    COUNT(DISTINCT CASE WHEN gu.userType = 'facilitator' THEN gs.login END) AS total_facilitators,
    COUNT(DISTINCT CASE WHEN gu.userType = 'rPersons' THEN gs.login END) AS total_resource_persons,
    MIN(gs.validity_period_from) AS min_validity_from,
    MAX(gs.validity_period_to) AS max_validity_to,
    gs.institute_id,
    MIN(gs.date_of_creation) AS min_date_of_creation
FROM geeqbulkstudents gs
LEFT JOIN geequsers gu ON gs.login = gu.generatedUsername
WHERE gu.created_by = '$user_name'
GROUP BY gs.university_name";
	
// Execute the query
$result2 = mysqli_query($coni, $query);





?> 
<link
      rel="stylesheet"
      type="text/css"
      href="../assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"
    />
<style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
	<style>
    [data-title]:hover:after { 
        opacity: 1; 
        transition: all 0.2s ease 0.6s; 
        visibility: visible; 
    } 
    [data-title]:after { 
        content: attr(data-title); 
        position: absolute;
        bottom: -2px;  /* Adjust this value to change the distance from the bottom */
        left: 80%;
        transform: translateX(-50%);
        padding: 4px 8px 4px 8px; 
        color: #fff;  /* Text color */
        border-radius: 5px;   
        box-shadow: 0px 0px 15px #2255a4;  /* Box shadow color */
        background-color: #2255a4;;  /* Background color */
        opacity: 0;  /* Initially hidden */
        visibility: hidden;
    } 
</style>
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
				<?php
if (mysqli_num_rows($result2) > 0) {
    echo "<table border='1' style='margin: auto;'>
            <tr>
                <th style='text-align: center;'><i class='mdi mdi-account-multiple'></i> &nbsp;&nbsp;Institute ID</th>
                <th style='text-align: center;'><i class='mdi mdi-bank'></i> &nbsp;&nbsp;University Name</th>
                <th style='text-align: center;'><i class='mdi mdi-account-multiple'></i> &nbsp;&nbsp;Learners</th>
                <th style='text-align: center;'><i class='mdi mdi-account-settings-variant'></i> &nbsp;&nbsp; Facilitators</th>
                <th style='text-align: center;'><i class='mdi mdi-account-network'></i> &nbsp;&nbsp;Resource Persons</th>
                <th style='text-align: center;'><i class='mdi mdi-bookmark-remove'></i> &nbsp;&nbsp;Validity From</th>
                <th style='text-align: center;'><i class='mdi mdi-account-key'></i> &nbsp;&nbsp;Validity Upto</th>
                <th style='text-align: center;'><i class='mdi mdi-checkbox-multiple-marked-outline'></i> &nbsp;&nbsp;Status</th>
            </tr>";

    while ($row = mysqli_fetch_array($result2)) {
        ?>
			<tr>
				<td style='text-align: center;'><?php echo $row['institute_id']; ?></td>
				<td style='text-align: center;'><?php echo $row['university_name']; ?></td>
				<td style='text-align: center;'><?php echo ($row['total_learners'] === 0 || is_null($row['total_learners'])) ? 'Not defined' : $row['total_learners']; ?></td>
				<td style='text-align: center;'><?php echo ($row['total_facilitators'] === 0 || is_null($row['total_facilitators'])) ? 'Not defined' : $row['total_facilitators']; ?></td>
				<td style='text-align: center;'><?php echo ($row['total_resource_persons'] === 0 || is_null($row['total_resource_persons'])) ? 'Not defined' : $row['total_resource_persons']; ?></td>
				<td style='text-align: center;'><?php echo is_null($row['min_validity_from']) ? 'Not defined' : $row['min_validity_from']; ?></td>
				<td style='text-align: center;'><?php echo is_null($row['max_validity_to']) ? 'Not defined' : $row['max_validity_to']; ?></td>
										
					<td style='text-align: center;'>
    <?php
    // Assuming $row contains the data for the current row
    $instituteId = $row['institute_id'];
    
    // Check if validity_from and validity_to are set in your database
    $validityFromSet = isset($row['min_validity_from']) && $row['min_validity_from'] !== null;
    $validityToSet = isset($row['max_validity_to']) && $row['max_validity_to'] !== null;

    if ($validityFromSet && $validityToSet) {
        // Both validity_from and validity_to are set, display "Granted" with a disabled button
        echo "<a href='#' class='btn btn-danger btn-sm'> <i class='mdi mdi-view-list'></i> Granted</a>";
    } else {
        // Validity not set, display "Enable" with an enabled button
        echo "<a href='?action=enable&rowid=$instituteId' data-title='Provide Platform Access by Defining Validity' class='btn btn-info btn-sm'>
                <i class='mdi mdi-view-list'></i> Enable
              </a>";
    }
    ?>
</td>
					
					
					
					
											

			</tr>        
			<?php
    }
    echo "</table>";
} else {
    echo "No results found.";
}
?>

		
		
		
		
		
</div>



	<div><br>	<br><br><br> </div>
	
<?php

// Check if the form should be displayed for Updating the validity 

if (isset($_GET['action']) && $_GET['action'] === 'enable' && isset($_GET['rowid'])) {   $rowId = $_GET['rowid'];   ?>


<div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"><i
                          class="me-1 mdi mdi-cloud-check  fs-4"
                          aria-hidden="true"
                        ></i> Allow Acces for  `<?php echo $rowId; ?>`  Institute </h4>
              
            </div>
          </div>
        </div>

 <section id="course-concern" class="course-concern">
            <div class="container">
                <div class="row">
                    <div class="col-12">
							<div class="form-container">
							
							<form method="post" action="?action=processEnable">
							<input type="hidden" name="rowId" value="<?php echo $rowId; ?>">
								<!-- Your form fields for validity from and validity to -->
								<div class="row">
								<div class="col-md-6">
									  <label class="mt-3"><h5><i class="mdi mdi-calendar"></i> Validity From *</h5></label>
									  <div class="input-group">
										<input type="text" class="form-control" id="validFrom" name="validityFrom" placeholder="mm/dd/yyyy" />
										<div class="input-group-append">
										  <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
										</div>
									  </div>
								</div>
								
								<div class="col-md-6">
									  <label class="mt-3"><h5><i class="mdi mdi-calendar"></i> Validity Upto *</h5></label>
									  <div class="input-group">
										<input type="text" class="form-control" id="validUpto" name="validityTo" placeholder="mm/dd/yyyy" />
										<div class="input-group-append">
										  <span class="input-group-text h-100"><i class="mdi mdi-calendar"></i></span>
										</div>
									  </div>
								</div>
								</div>	<br><br>
								<button type="submit" id="saveButtonBasic" name="saveButtonBasic" class="btn btn-info float-end">Update & Grant Access</button>
								
								
							</form>
						</div>
					</div>
				</div>
			</div>
</section>
<?php  }   ?>



<?php
// Check if the form is submitted and processEnable action is triggered
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'processEnable') {
    // Retrieve form data
    $validityFrom = $_POST['validityFrom'];
    $validityTo = $_POST['validityTo'];
    $rowId = $_POST['rowId'];
	
	// Format dates to MySQL format
    $formattedValidityFrom = date('Y-m-d', strtotime($validityFrom));
    $formattedValidityTo = date('Y-m-d', strtotime($validityTo));

    // Perform database updates based on the provided conditions
    $user_name = $_SESSION['user_name'];

    // Update geeqbulkstudents
    $updateStudentsQuery = "UPDATE geeqbulkstudents 
                            SET validity_period_from = ?, validity_period_to = ? 
                            WHERE created_by = ? AND institute_id = ?";
    $updateStudentsStmt = mysqli_prepare($coni, $updateStudentsQuery);
    mysqli_stmt_bind_param($updateStudentsStmt, 'ssss', $formattedValidityFrom, $formattedValidityTo, $user_name, $rowId);
    mysqli_stmt_execute($updateStudentsStmt);
    mysqli_stmt_close($updateStudentsStmt);

    // Update geeqbulkfacilitators
	//	$updateFacilitatorsQuery = "UPDATE geeqbulkfacilitators 
	//							   SET validity_period_from = ?, validity_period_to = ? 
	//							   WHERE created_by = ? AND institute_id = ?";
	//	$updateFacilitatorsStmt = mysqli_prepare($yourConnectionVariable, $updateFacilitatorsQuery);
	//	mysqli_stmt_bind_param($updateFacilitatorsStmt, 'ssss', $validityFrom, $validityTo, $user_name, $rowId);
	//	mysqli_stmt_execute($updateFacilitatorsStmt);
	//	mysqli_stmt_close($updateFacilitatorsStmt);

    // Update geeqbulkrpersons
	//	$updateRPersonsQuery = "UPDATE geeqbulkrpersons 
	//							SET validity_period_from = ?, validity_period_to = ? 
	//							WHERE created_by = ? AND institute_id = ?";
	//	$updateRPersonsStmt = mysqli_prepare($yourConnectionVariable, $updateRPersonsQuery);
	//	mysqli_stmt_bind_param($updateRPersonsStmt, 'ssss', $validityFrom, $validityTo, $user_name, $rowId);
	//	mysqli_stmt_execute($updateRPersonsStmt);
	//	mysqli_stmt_close($updateRPersonsStmt);

    // Update geeqbulkstaff
	//	$updateStaffQuery = "UPDATE geeqbulkstaff 
	//						SET validity_period_from = ?, validity_period_to = ? 
	//						WHERE created_by = ? AND institute_id = ?";
	//	$updateStaffStmt = mysqli_prepare($yourConnectionVariable, $updateStaffQuery);
	//	mysqli_stmt_bind_param($updateStaffStmt, 'ssss', $validityFrom, $validityTo, $user_name, $rowId);
	//	mysqli_stmt_execute($updateStaffStmt);
	//	mysqli_stmt_close($updateStaffStmt);

    // Display a success message or redirect to another page
    //echo $rowId, $validityTo,  $validityFrom. " Form submitted successfully!";
	// Redirect to the enableAccess page
   // header("Location: enableAccess");
   // Reload the page after a delay
    echo "<script>
            setTimeout(function(){
                window.location.href = 'enableAccess.php';
            }, 1000); // 1000 milliseconds (1 seconds) delay before reloading
          </script>";
}
?>



			
			  <div><p>
			  <br><br><br><br>
			  </div>
            </div>
        </div>
    </section>
    <!-- END / COURSE CONCERN -->
	
	<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="../js/library/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="../js/library/bootstrap.min.js"></script>
	<script src="../assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
      /*datwpicker*/
      jQuery(".mydatepicker").datepicker();
	  
      jQuery("#validUpto").datepicker({
        autoclose: true,
        todayHighlight: true,
        todayHighlight: true,
      });
	  
	   jQuery("#validFrom").datepicker({
        autoclose: true,
        todayHighlight: true,
        todayHighlight: true,
      });
	  
</script>

</div>
<!-- END / PAGE WRAP -->

	
   <?php include_once('../glxp_learners/learners_footer.php'); ?>

</body>
</html>