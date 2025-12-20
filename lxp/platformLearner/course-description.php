<?php 
/**
 *  Astraal LXP - Learner Learning Paths
 * Refactored for new session guard architecture
 * PHP 5.4 compatible (UwAmp / GoDaddy)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified session management

$page = "learningPath";
require_once('learnerHead_Nav2.php');


// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];


if (isset($_GET['cid']) && is_numeric($_GET['cid'])) {
    $cid = $_GET['cid'];
} else {
    die("Invalid Course ID received.");
}



$getall2 = "SELECT
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
    c.id, c.name, u.name, u.surname, u.short_description, total_lessons";

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

       $twq = "SELECT name, start_date, 
            CEIL((end_date - start_date) / 604800) AS total_weeks 
        FROM courses 
        WHERE id = '$cid'";

        $gettw = mysqli_fetch_array(mysqli_query($coni, $twq));
        $cweeks = $gettw['total_weeks'];
        $course_name = $gettw['name'];
		

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
              <div class="row" style="padding-bottom: 120px;">
			  
   <div class="col-lg-12 mb-4 order-0">
   
				<!-- Custom style1 Breadcrumb -->
                  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
                    <ol class="breadcrumb breadcrumb-style1">
                      
                      <li class="breadcrumb-item">
                        <a href="learning-path.php">Learning Path</a>    
                      </li>
                      <li class="breadcrumb-item active">Course Description </li>
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
        <i class="bx bx-book-open"></i> &nbsp;&nbsp;    <?php echo $_SESSION['fname']; ?>   Check Importance of Course Description for <?php echo $course_name; ?>

      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse"
      aria-labelledby="heading3"
      data-bs-parent="#accordionExample"
    >
      <div class="accordion-body">
	  
        <div class="card">
  <div class="d-flex align-items-end row">
    <div class="col-sm-9">
      <div class="card-body">
       
        <p>
          The course description plays a vital role in helping you navigate the content effectively. It outlines the objectives, key skills, and expected outcomes of the course, allowing you to:
        </p>
        <ul>
          <li><span class="fw-bold">Set clear expectations</span> by understanding the scope and objectives of your learning experience.</li>
          <li><span class="fw-bold">Track your progress</span> by identifying the key skills and concepts that will be developed.</li>
          <li><span class="fw-bold">Focus your efforts</span> on areas that are directly aligned with your personal and professional goals.</li>
          <li><span class="fw-bold">Maximize your learning potential</span> by using the course description to prioritize topics and manage your time effectively.</li>
        </ul>

      </div>
    </div>
    <div class="col-sm-3 text-center text-sm-left">
      <div class="card-body pb-0 px-0 px-md-4">
        <img
          src="../assets/img/illustrations/learning-path-light.jpg"
          height="140"
          alt="Learning Path Progress"
          data-app-dark-img="illustrations/learning-path-dark.jpg"
          data-app-light-img="illustrations/learning-path-light.jpg"
        />
      </div>
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
	
	
     <div class="card-header">
  <ul class="nav nav-pills mb-0" role="tablist">
    <!-- Resources Tab -->
    <li class="nav-item" style="margin-right: 6px;">
      <a href="#tab-prerequisites" class="nav-link active" data-bs-toggle="pill" role="tab" aria-selected="true">
        <i class="bx bx-clipboard me-2"></i>Prerequisites
      </a>
    </li>
    <!-- Course Description Tab -->
    <li class="nav-item" style="margin-right: 8px;">
      <a href="#tab-course-description" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-book-open me-2"></i>Course Description
      </a>
    </li>
    <!-- Learning Objectives Tab -->
    <li class="nav-item" style="margin-right: 8px;">
      <a href="#tab-learning-objectives" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-target-lock me-2"></i>Learning Objectives
      </a>
    </li>
    <!-- Learning Outcomes Tab -->
    <li class="nav-item" style="margin-right: 8px;">
      <a href="#tab-learning-outcomes" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-trophy me-2"></i>Learning Outcomes
      </a>
    </li>
    <!-- Key Skills Gained Tab -->
    <li class="nav-item" style="margin-right: 8px;">
      <a href="#tab-key-skills" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
        <i class="bx bx-briefcase-alt me-2"></i>Key Skills Gained
      </a>
    </li>
	
  </ul>
</div>

<div class="card-body">
        <div class="tab-content">
     <div class="tab-pane fade  show active" id="tab-prerequisites" role="tabpanel">
    
	<h5 class="card-title text-info">Course Prerequisites</h5>
	<?php echo $resource; ?>
	
   
</div>


<div class="tab-pane fade" id="tab-course-description" role="tabpanel">
    <h5 class="card-title text-success">Course Overview</h5>
    <?php echo $generalDescription; ?>
    
</div>


<div class="tab-pane fade" id="tab-learning-outcomes" role="tabpanel">
    <h5 class="card-title text-warning">Learning Outcomes</h5>
    <p>Upon successful completion of this course, you will be able to:</p>
    <?php echo $outcomes; ?>
   
</div>


<div class="tab-pane fade" id="tab-learning-objectives" role="tabpanel">
    <h5 class="card-title text-info">Learning Objectives</h5>
    <p>The learning objectives for this course are designed to guide you through the key areas of the program and ensure that you gain both theoretical knowledge and practical skills. By the end of the course, you should be able to:</p>
    <?php echo $objectives; ?>
 
</div>


<div class="tab-pane fade" id="tab-key-skills" role="tabpanel">
    <h5 class="card-title text-primary">Key Skills You Will Gain</h5>
    <p>Throughout this course, you will develop and strengthen essential skills that are crucial for success. These skills will not only help you in this course but will also set you up for long-term success in your career:</p>
    <?php echo $skills; ?>
    
</div>

	  
	  
    </div> 
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
   