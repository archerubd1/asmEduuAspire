<!DOCTYPE html>
<html lang="en">

<?php 
include_once '../config.php';

$page="deptAdmin";
//include_once('stu_head_header.php');





$user_type = $_SESSION['user_type']; // Retrieve user type
$user_name = $_SESSION['user_name']; // Retrieve user name


include_once('admin_left_nav.php');


// SQL query
$sql = "
    SELECT
        u1.generatedusername AS creator_username,
        COUNT(DISTINCT u2.generatedusername) + COUNT(DISTINCT u2.created_by) AS user_count
    FROM
        (
            SELECT generatedusername
            FROM geequsers
            WHERE created_by = 'ins@ins.com'
            UNION
            SELECT 'ins@ins.com' AS generatedusername
        ) u1
    LEFT JOIN
        geequsers u2 ON u1.generatedusername = u2.created_by
    GROUP BY
        u1.generatedusername";

// Execute the query
$result = $coni->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error: " . $mysqli->error);
}

$row = $result->fetch_assoc();

// Fetch all rows
$rows = $result->fetch_all(MYSQLI_ASSOC);

// Access the first row (assuming it's not an empty array)
$row = reset($rows);


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
                          class="me-1 mdi mdi-view-dashboard  fs-4"
                          aria-hidden="true"
                        ></i> Admin : Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                     Dashboard
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
   
			  
			  
			  
	<!-- Analytics Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-account-multiple-plus"></i>
                  </h1>
                  <h6 class="text-white">Users</h6>
							  <p>Total: <?php echo $row['user_count']; ?></p>
				
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-book-open-page-variant"></i>
                  </h1>
                  <h6 class="text-white">Courses </h6>
				  <p>Published: 0</p>
     
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-collage"></i>
                  </h1>
                  <h6 class="text-white">Assessments</h6><p>Completed: 0</p>
      <p>Score: 0%</p>
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-border-outside"></i>
                  </h1>
                  <h6 class="text-white">Skills</h6><p>Skills Evaluated: 0</p>
      <p>Skills to Verify: 0</p>
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-arrow-all"></i>
                  </h1>
                  <h6 class="text-white">Collaboration</h6> <p>Group Projects: 0</p>
      <p> Completed Tasks: 0</p>
                </div>
              </div>
            </div>
            <!-- Column -->
            <!-- Column 
            <div class="col-md-6 col-lg-4 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-receipt"></i>
                  </h1>
                  <h6 class="text-white">Leaderboards</h6><p>for Courses: 0</p>
      <p>for Collaboration: 0</p>
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-relative-scale"></i>
                  </h1>
                  <h6 class="text-white">Badges</h6> <p>Approved: 0</p>
      <p>In Progress: 0</p>
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-pencil"></i>
                  </h1>
                  <h6 class="text-white">Industry Experience</h6> <p>for Internships: 0</p>
      <p>for Projects: 0</p>
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-calendar-check"></i>
                  </h1>
                  <h6 class="text-white">Achievements</h6><p>Checked Levels: 0</p>
      <p>Checked Challenges: 0</p>
                </div>
              </div>
            </div>
            <!-- Column 
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-alert"></i>
                  </h1>
                  <h6 class="text-white">360-degree Profiling</h6><p>View Stats <p> Completeness: 40%</p>
                </div>
              </div>
            </div>
            <!-- Column -->
          </div>
		  
			 
   		
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