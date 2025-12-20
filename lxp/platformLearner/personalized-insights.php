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
                      <li class="breadcrumb-item active">Personalized Insights</li>
                    </ol>
                  </nav>
                  <!--/ Custom style1 Breadcrumb -->



<div class="accordion mt-3 card accordion-item">
  <h2 class="accordion-header" id="heading3">
    <button
      type="button"
      class="accordion-button bg-label-primary"
      data-bs-toggle="collapse"
      data-bs-target="#accordion3"
      aria-expanded="true"
      aria-controls="accordion3"
    >
      <i class="bx bx-line-chart"></i> &nbsp;&nbsp; Learning Path Insights Overview
    </button>
  </h2>
  <div
    id="accordion3"
    class="accordion-collapse show"
    aria-labelledby="heading3"
    data-bs-parent="#accordionExample"
  >
    <div class="accordion-body">
      <div class="table-responsive">
   
 
 <table class="table table-bordered m-0">
  <thead class="table-light">
    <tr>
      <th class="text-center align-middle">#</th>
      <th class="text-center align-middle">Parameter</th>
      <th class="text-center align-middle">Purpose</th>
      <th class="text-center align-middle">Target </th>
      <th colspan="2" class="text-center align-middle">Actions</th>
    </tr>
  </thead>
  <tbody>
    <!-- Row 1 -->
    <tr>
      <td>1</td>
      <td>learning_goals</td>
      <td>Define what the learner wants to achieve.</td>
      <td>"Master Python programming in 6 months"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Edit Learning Goals" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-edit"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Recommendations" href="#" class="btn btn-sm btn-outline-success">
          <i class="bx bx-bulb"></i>
        </a>
      </td>
    </tr>
    <!-- Row 2 -->
    <tr>
      <td>2</td>
      <td>current_skills</td>
      <td>Captures skills already mastered.</td>
      <td>"Basic Python, Excel Analysis"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Update Skills" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-edit"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Skill Assessment" href="#" class="btn btn-sm btn-outline-info">
          <i class="bx bx-list-check"></i>
        </a>
      </td>
    </tr>
    <!-- Row 3 -->
    <tr>
      <td>3</td>
      <td>completed_courses</td>
      <td>Tracks progress and informs next steps.</td>
      <td>"Introduction to Data Science, SQL Basics"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Completed Courses" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-book-open"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Download Certificate" href="#" class="btn btn-sm btn-outline-success">
          <i class="bx bx-download"></i>
        </a>
      </td>
    </tr>
    <!-- Row 4 -->
    <tr>
      <td>4</td>
      <td>quiz_performance</td>
      <td>Assesses knowledge retention and gaps.</td>
      <td>"85% on Python Basics Quiz"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Retake Quiz" href="#" class="btn btn-sm btn-outline-warning">
          <i class="bx bx-refresh"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Quiz Report" href="#" class="btn btn-sm btn-outline-info">
          <i class="bx bx-bar-chart"></i>
        </a>
      </td>
    </tr>
    <!-- Row 5 -->
    <tr>
      <td>5</td>
      <td>recommended_topics</td>
      <td>Dynamically suggests areas for improvement.</td>
      <td>"Advanced SQL Queries, Data Visualization Basics"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Explore Topics" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-search"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Details" href="#" class="btn btn-sm btn-outline-success">
          <i class="bx bx-detail"></i>
        </a>
      </td>
    </tr>
    <!-- Row 6 -->
    <tr>
      <td>6</td>
      <td>engagement_metrics</td>
      <td>Tracks interaction levels to assess focus and interest.</td>
      <td>"Active for 8 hours this week"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Engagement Report" href="#" class="btn btn-sm btn-outline-info">
          <i class="bx bx-bar-chart-alt"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Set Engagement Goal" href="#" class="btn btn-sm btn-outline-warning">
          <i class="bx bx-target-lock"></i>
        </a>
      </td>
    </tr>
    <!-- Row 7 -->
    <tr>
      <td>7</td>
      <td>course_difficulty</td>
      <td>Matches learning materials to the learner’s competency level.</td>
      <td>"Intermediate"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Change Difficulty Level" href="#" class="btn btn-sm btn-outline-secondary">
          <i class="bx bx-slider"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Difficulty Suggestions" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-help-circle"></i>
        </a>
      </td>
    </tr>
    <!-- Row 8 -->
    <tr>
      <td>8</td>
      <td>peer_progress</td>
      <td>Provides benchmarking against others.</td>
      <td>"Top 20% of learners in cohort"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Peer Stats" href="#" class="btn btn-sm btn-outline-info">
          <i class="bx bx-group"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Compare Progress" href="#" class="btn btn-sm btn-outline-warning">
          <i class="bx bx-line-chart"></i>
        </a>
      </td>
    </tr>
    <!-- Row 9 -->
    <tr>
      <td>9</td>
      <td>certifications_earned</td>
      <td>Tracks credentialing and accomplishments.</td>
      <td>"Python Beginner Certificate"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Certification" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-award"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Download Certification" href="#" class="btn btn-sm btn-outline-success">
          <i class="bx bx-download"></i>
        </a>
      </td>
    </tr>
    <!-- Row 10 -->
    <tr>
      <td>10</td>
      <td>learning_preferences</td>
      <td>Tracks preferred learning style and format.</td>
      <td>"Visual Learning, Short Videos"</td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Edit Preferences" href="#" class="btn btn-sm btn-outline-primary">
          <i class="bx bx-edit"></i>
        </a>
      </td>
      <td class="text-center">
        <a data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Suggested Formats" href="#" class="btn btn-sm btn-outline-info">
          <i class="bx bx-layout"></i>
        </a>
      </td>
    </tr>
  </tbody>
</table>

 
 
 

  
  
  
  
  
  



      </div>
      <!-- /.table-responsive -->
    </div>
  </div>
</div>


	  
  
</div>
</div>
 <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   