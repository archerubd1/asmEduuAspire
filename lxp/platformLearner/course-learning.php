<?php 

// Start session before ANY output
session_start();
require_once('../../config.php');

// Ensure session variables exist
if (!isset($_SESSION['user_type']) || !isset($_SESSION['user_name'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$page="learningPath";
require_once('learnerHead_Nav2.php');


$user_type = $_SESSION['user_type']; // Retrieve user type
$user_name = $_SESSION['user_name']; // Retrieve user name

if (isset($_POST['cid']) && is_numeric($_POST['cid'])) {
    $cid = $_POST['cid'];
} else {
    die("Invalid Course ID received.");
}


$getall2 = "SELECT
    c.cou_code,
    c.name AS course_name,
    c.info AS course_meta,
    u.name AS creator_name,
    u.surname AS creator_surname,
    u.short_description AS creator_description,
    GROUP_CONCAT(DISTINCT IFNULL(l.name, '') ORDER BY l.id) AS lesson_names,
    GROUP_CONCAT(DISTINCT l.id) AS lesson_ids,
    COALESCE(total_lessons, 0) AS total_lessons,
    COUNT(DISTINCT cu.id) AS total_units,
    COALESCE(total_tests, 0) AS total_tests,
    COALESCE(total_projects, 0) AS total_projects
FROM
    courses c
JOIN
    lessons_to_courses ltc ON c.id = ltc.courses_id
JOIN
    lessons l ON l.id = ltc.lessons_id
LEFT JOIN
    content cu ON l.id = cu.lessons_id
LEFT JOIN
    tests t ON cu.id = t.content_id AND l.id = t.lessons_id
LEFT JOIN
    projects p ON l.id = p.lessons_id
LEFT JOIN
    users u ON c.supervisor_LOGIN = u.login
LEFT JOIN (
    SELECT ltc.courses_id, COUNT(DISTINCT ltc.lessons_id) AS total_lessons
    FROM lessons_to_courses ltc
    GROUP BY ltc.courses_id
) ltc_counts ON c.id = ltc_counts.courses_id
LEFT JOIN (
    SELECT t.lessons_id, COUNT(DISTINCT t.id) AS total_tests
    FROM tests t
    GROUP BY t.lessons_id
) t_counts ON l.id = t_counts.lessons_id
LEFT JOIN (
    SELECT p.lessons_id, COUNT(DISTINCT p.id) AS total_projects
    FROM projects p
    GROUP BY p.lessons_id
) p_counts ON l.id = p_counts.lessons_id
WHERE
    c.id = $cid
GROUP BY
    c.id, c.cou_code, c.name, u.name, u.surname, u.short_description, total_lessons";

$result = mysqli_query($coni, $getall2);

if (!$result) {
    // Query failed
    $status = "Query failed: " . mysqli_error($coni);
    $courseName = mysqli_fetch_array(mysqli_query($coni, "SELECT name FROM courses WHERE id = '$cid'"));
    $course_name = $courseName['name'];
} else {
    $status = "Query successful";

    // Fetch metadata
    $getmeta = mysqli_fetch_array($result);

    if (!$getmeta) {
        // No rows returned
        $status = "No rows returned for the specified course ID.";
    } else {
        // Rows returned, process data
        $tests = $getmeta['total_tests'];
        $units = $getmeta['total_units'];
        $cname = $getmeta['course_name'];
        $tlessons = $getmeta['total_lessons'];
        $projects = $getmeta['total_projects'];
        $author = $getmeta['creator_name'] . ' ' . $getmeta['creator_surname'];
        $author_bio = $getmeta['creator_description'];

        $info = $getmeta['course_meta'];
        // Attempt to decode as JSON
        $courseInfo = json_decode($info, true);

        if ($courseInfo === null && json_last_error() !== JSON_ERROR_NONE) {
            // JSON decoding failed, fallback to unserialize
            $courseInfo = unserialize($info);
        }

        // Access individual values with isset() and display 'Not Defined Yet'
        $generalDescription = isset($courseInfo['general_description']) ? $courseInfo['general_description'] : 'Not Defined Yet';
        $objectives = isset($courseInfo['objectives']) ? $courseInfo['objectives'] : 'Not Defined Yet';
        $outcomes = isset($courseInfo['other_info']) ? $courseInfo['other_info'] : 'Not Defined Yet';
        $skills = isset($courseInfo['learning_method']) ? $courseInfo['learning_method'] : 'Not Defined Yet';
        $target = isset($courseInfo['assessment']) ? $courseInfo['assessment'] : 'Not Defined Yet';
        $prerequisites = isset($courseInfo['prerequisites']) ? $courseInfo['prerequisites'] : 'None Defined Yet';
        $resource = isset($courseInfo['resources']) ? $courseInfo['resources'] : 'Not Defined Yet';

        // Assuming $row is the result of your SQL query
        $lessonNames = explode(',', $getmeta['lesson_names']);
        $lessonNames = array_map('trim', $lessonNames); // Trim whitespaces

        // Retrieve lesson IDs directly from the database
        $lessonIdsQuery = "SELECT DISTINCT l.id FROM lessons_to_courses ltc
                          JOIN lessons l ON l.id = ltc.lessons_id
                          WHERE ltc.courses_id = '$cid'";

        $lessonIdsResult = mysqli_query($coni, $lessonIdsQuery);

        $lessonIds = array();
        while ($lessonIdRow = mysqli_fetch_assoc($lessonIdsResult)) {
            $lessonIds[] = $lessonIdRow['id'];
        }

        // Combine lesson names and IDs into an associative array
        $combinedArray = array_combine($lessonIds, $lessonNames);

        // Sort the associative array by lesson IDs
        ksort($combinedArray);

        $twq = "SELECT name, start_date, cou_code,
            CEIL((end_date - start_date) / 604800) AS total_weeks
        FROM
            courses WHERE id = '$cid'";
        $gettw = mysqli_fetch_array(mysqli_query($coni, $twq));
        $cweeks = $gettw['total_weeks'];
        $course_name = $gettw['name'];
		$course_code = $gettw['cou_code'];

        $_SESSION['$cid'] = $cid;
    }
}




?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('learnersNav.php');   ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
			  
   <div class="col-lg-12 mb-4 order-0">
   
				<!-- Custom style1 Breadcrumb -->
                  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
                    <ol class="breadcrumb breadcrumb-style1">
                      
                      <li class="breadcrumb-item">
                        <a href="learning-path.php">Learning Path</a>
                      </li>
                      <li class="breadcrumb-item active">Course Learning</li>
                    </ol>
                  </nav>
                  <!--/ Custom style1 Breadcrumb -->

 <!-- Accordion for course description -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for course description -->
  <div class="accordion-item">
    <h4 class="accordion-header" id="heading3">
      <button
        type="button"
        class="accordion-button bg-label-info"
        data-bs-toggle="collapse"
        data-bs-target="#accordion1"
        aria-expanded="true"
        aria-controls="accordion1"
      >
        <i class="bx bx-book"></i> &nbsp;&nbsp; The Course Table of Contents
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show"  <!-- Added "show" class -->
     
      <div class="accordion-body">
        
          <div class="d-flex align-items-end row">
            <div class="col-sm-9">
              <div class="card-body">
                <p>
                  The course Table of Content  plays a vital role in helping you navigate the content effectively. You will Navigate externally for your  Learning Journey.
                </p>
              </div>
            </div>
            <div class="col-sm-3 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img
                  src="../assets/img/illustrations/coursetoc2.jpg"
                  height="140"
                  alt="Learning Path Progress"
                  data-app-dark-img="illustrations/coursetoc2.jpg"
                  data-app-light-img="illustrations/coursetoc2.jpg"
                />
              </div>
            </div>
          </div>
        
      </div> 
    </div>
  </div>
  <!-- End Accordion Item -->
</div>
<!-- End Accordion -->




<div class="row mt-4">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body text-center">
        <a href="https://lxp.raunakeducares.com/www/index.php?autologin=27005d0d021b8aff4b63b776505a56e1" 
           target="_blank" 
           class="btn btn-primary"><i class="bx bx-book-open me-2"></i>
          Start Learning
        </a>
      </div>
    </div>
  </div>
</div>


	  
  
</div>
</div>
 <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   