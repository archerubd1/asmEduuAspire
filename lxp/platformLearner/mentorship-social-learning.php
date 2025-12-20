<?php
/**
 * Astraal LXP - Learner Coding Ground
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

$page = "mentorSocialLearning";
require_once('learnerHead_Nav2.php');
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
        

     <!-- Accordion for Mentorship and Social Learning Opportunities -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for Mentorship & Social Learning -->
  <div class="accordion-item">
    <h2 class="accordion-header" id="heading3">
      <button
        type="button"
        class="accordion-button bg-label-primary"
        data-bs-toggle="collapse"
        data-bs-target="#accordion1"
        aria-expanded="true"
        aria-controls="accordion1"
      >
        <i class="bx bx-brain"></i> &nbsp;&nbsp; The Hidden Masters
      </button>
    </h2>
    <div
      id="accordion1"
      class="accordion-collapse collapse"
      aria-labelledby="heading3"
      data-bs-parent="#accordionExample"
    >
      <div class="accordion-body">
        <div class="table-responsive">
          <table class="table table-bordered m-0">
            <thead class="table-light">
              <tr>
                <th class="text-center align-middle">Attain</th>
                <th class="text-center align-middle">Description</th>
                <th class="text-center align-middle"></th>
              </tr>
            </thead>
            <tbody>
              <!-- Dao Row -->
              <tr>
                <td>Dao</td>
                <td>In harmony of the unknown yet seeking beyond the known...</td>
                <td >
                  <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Explore NOW">
                    <i class="bx bx-book-open"></i>
                  </a>
                </td>
              </tr>
              <!-- Tao Row -->
              <tr>
                <td>Tao</td>
                <td>Knowing the path yet unlearning the walk ....</td>
                <td >
                  <a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="The Long Walk">
                    <i class="bx bx-heart"></i>
                  </a>
                </td>
              </tr>
              <!-- Zen Row -->
              <tr>
                <td>Zen</td>
                <td>'Discovery' yet unquestioning in silence...</td>
                <td >
                  <a href="#" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Explore the Zenith">
                    <i class="bx bx-brain"></i>
                  </a>
                </td>
              </tr>
              <!-- Aham Brahmāsmi Row -->
              <tr>
                <td>Aham Brahmāsmi</td>
                <td>You ARE the seeker, you are the way ..........</td>
                <td >
                  <a href="#" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Reflect Beyond">
                    <i class="bx bx-bulb"></i>
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
  <!-- End Accordion Item -->
</div>
<!-- End Accordion -->

		
		
		
		<div class="col-lg-12 mb-4 order-0">

        <div class="accordion mt-3 card accordion-item">
          <h2 class="accordion-header" id="heading3">
            <button
              type="button"
              class="accordion-button bg-label-info"
              data-bs-toggle="collapse"
              data-bs-target="#accordion3"
              aria-expanded="true"
              aria-controls="accordion3"
            >
              <i class="bx bx-group"></i> &nbsp;&nbsp; Mentorship Programs & Social Learning Opportunities
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
            <th class="text-center align-middle">Program Type</th>
            <th class="text-center align-middle">Program Name</th>
            <th class="text-center align-middle">Focus Area</th>
            <th class="text-center align-middle">Format</th>
            <th class="text-center align-middle">Upcoming Sessions</th>
            <th class="text-center align-middle">Engagement</th>
          </tr>
        </thead>
       <tbody>

  <!-- Mentorship Program 1 -->
  <tr>
    <td class="text-center">
      <i class="fas fa-user-tie" title="Mentor-Led Program"></i><br>
      Mentorship
    </td>
    <td>Tech Leadership Program</td>
    <td>Leadership in technology, project management, and innovation</td>
    <td>One-on-one sessions, industry insights, group mentoring</td>
    <td>Session 1: January 30, 2025</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-primary">
        <i class="bx bx-user"></i> Join
      </a>
    </td>
  </tr>

  <!-- Social Learning Opportunity 1 -->
  <tr>
    <td class="text-center">
      <i class="fas fa-users" title="Collaborative Learning"></i><br>
      Social Learning
    </td>
    <td>AI and Data Science Webinars</td>
    <td>Introduction to AI concepts, data analysis techniques</td>
    <td>Live webinars, interactive Q&A sessions</td>
    <td>Webinar: February 5, 2025</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-info">
        <i class="bx bx-video"></i> Webinar
      </a>
    </td>
  </tr>

  <!-- Mentorship Program 2 -->
  <tr>
    <td class="text-center">
      <i class="fas fa-user-tie" title="Mentor-Led Program"></i><br>
      Mentorship
    </td>
    <td>Entrepreneurship Accelerator</td>
    <td>Business strategy, fundraising, market penetration</td>
    <td>Monthly group discussions, mentorship sessions with founders</td>
    <td>Session 2: February 12, 2025</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-success">
        <i class="bx bx-briefcase"></i> Join
      </a>
    </td>
  </tr>

  <!-- Social Learning Opportunity 2 -->
  <tr>
    <td class="text-center">
      <i class="fas fa-users" title="Collaborative Learning"></i><br>
      Social Learning
    </td>
    <td>Code-a-thon Challenge</td>
    <td>Problem-solving using coding skills in a competitive environment</td>
    <td>Online competition, group collaboration, coding sprints</td>
    <td>Event: February 20, 2025</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-warning">
        <i class="bx bx-code"></i> Register
      </a>
    </td>
  </tr>

  <!-- Mentorship Program 3 -->
  <tr>
    <td class="text-center">
      <i class="fas fa-user-tie" title="Mentor-Led Program"></i><br>
      Mentorship
    </td>
    <td>Marketing Strategy Masterclass</td>
    <td>Effective marketing strategies, social media campaigns, branding</td>
    <td>Interactive workshops, one-on-one mentoring</td>
    <td>Session 3: March 1, 2025</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-danger">
        <i class="bx bx-speaker"></i> Request
      </a>
    </td>
  </tr>

  <!-- Social Learning Opportunity 3 -->
  <tr>
    <td class="text-center">
      <i class="fas fa-users" title="Collaborative Learning"></i><br>
      Social Learning
    </td>
    <td>Cloud Computing Hackathon</td>
    <td>Developing cloud-based solutions, cloud infrastructure</td>
    <td>Hackathon, team-based problem-solving</td>
    <td>Event: March 10, 2025</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-success">
        <i class="bx bx-cloud"></i> Register
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
  </div>
  <!-- / Content -->
</div>







<?php 
require_once('../platformFooter.php');
?>
   