<?php
/**
 * Astraal LXP - Learner Collaborative learning 
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

$page = "collaborativeLearning";
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
        

   <!-- Accordion for Mentorship and Collaborative Learning Opportunities -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for Mentorship & Collaborative Learning -->
  <div class="accordion-item">
    <h4 class="accordion-header" id="heading3">
      <button
        type="button"
        class="accordion-button bg-label-primary"
        data-bs-toggle="collapse"
        data-bs-target="#accordion1"
        aria-expanded="true"
        aria-controls="accordion1"
      >
        <i class="bx bx-group"></i> &nbsp;&nbsp; Collaborative Learning and Mentorship Opportunities
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse"
      aria-labelledby="heading3"
      data-bs-parent="#accordionExample"
    >
      <div class="accordion-body">
        <strong>Overview:</strong><br>
        The Collaborative Learning framework is designed to enhance teamwork and mentorship-driven growth. By tackling real-world challenges together, learners engage in a process that emphasizes communication, problem-solving, and the power of collective intelligence. This approach fosters adaptability, leadership, and interpersonal skills essential for dynamic environments.
        <br><br>
        <strong>Key Features:</strong>
        <ul>
          <li><strong>Mentorship:</strong> Work alongside industry experts and experienced educators who guide learners at every stage, ensuring meaningful progress and professional insights.</li>
          <li><strong>Team Collaboration:</strong> Engage in projects that require brainstorming, delegation, and collective problem-solving, reflecting real-world organizational dynamics.</li>
          <li><strong>Social Learning:</strong> Share knowledge, provide feedback, and grow through peer-to-peer interactions, building a strong network of collaborative thinkers.</li>
          <li><strong>Interactive Projects:</strong> Solve multidimensional problems by leveraging diverse perspectives and skillsets within the group.</li>
        </ul>
        <strong>Benefits:</strong>
        <ul>
          <li>Improved communication and teamwork skills.</li>
          <li>Hands-on experience in real-world collaboration scenarios.</li>
          <li>Exposure to diverse ideas and innovative approaches to challenges.</li>
          <li>A supportive learning environment fostering mutual growth.</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- End Accordion Item -->
</div>
<!-- End Accordion -->


	

  <div class="card mt-3">
    <div class="d-flex align-items-end row">
      <div class="col-sm-7">
        <div class="card-body">
  <p class="mb-4">
    Your <span class="fw-bold">collaborative learning journey</span> is progressing exceptionally well. You now have access to resources that will help you achieve even greater success:
  </p>
  <ul>
    <li><span class="fw-bold">3 comprehensive project-based frameworks</span> that emphasize teamwork, creativity, and practical problem-solving, enabling you to thrive in collaborative environments.</li>
      </ul>
  
  <div class="d-flex gap-2">
    <a href="#project-frameworks.php" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Discover Project Frameworks">
      <i class="bx bx-cube"></i> Explore Frameworks
    </a>
    <a href="#expert-insights.php" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Gain Expert Insights">
      <i class="bx bx-user-voice"></i> Access Insights
    </a>
  </div>
</div>

      </div>
      <div class="col-sm-5 text-center text-sm-left">
        <div class="card-body pb-0 px-0 px-md-4">
          <img
            src="../assets/img/illustrations/collab-light.jpg"
            height="140"
            alt="Collaborative Learning"
            data-app-dark-img="illustrations/collab-dark.jpg"
            data-app-light-img="illustrations/collab-light.jpg"
          />
        </div>
      </div>
    </div>
  </div>


	
		
		
		<div class="col-lg-12 mb-4 order-0">

        <div class="accordion mt-3 card accordion-item">
          <h4 class="accordion-header" id="heading3">
            <button
              type="button"
              class="accordion-button bg-label-info"
              data-bs-toggle="collapse"
              data-bs-target="#accordion3"
              aria-expanded="true"
              aria-controls="accordion3"
            >
              <i class="bx bx-world"></i> &nbsp;&nbsp; You are Gen-Z Digital Natives
            </button>
          </h4>
          <div
            id="accordion3"
            class="accordion-collapse show"
            aria-labelledby="heading3"
            data-bs-parent="#accordionExample"
          >
            <div class="accordion-body">
             <div class="table-responsive">
                  <table class="table m-0 table-bordered">
                    <thead>
                    <tr>
                     
					  <th class="text-center">Category</th>
                      <th class="text-center"colspan="2">Description</th>
                     
                    
                    </tr>
                    </thead>
                   <tbody>
  <tr>
    <td><a href="#">Tasks</a></td>
    <td colspan="2">These are collaborative learning activities that can be taken up individually and/or as a group.</td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Problem Statement & Take Up the Tasks">
      <i class="bx bx-task"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Engage Peers</a></td>
    <td colspan="2">Build your own team to discuss and solve various tasks/problems at hand.</td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Problem Statement & Invite Your Peers">
      <i class="bx bx-group"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Mentor Peers</a></td>
    <td colspan="2">Lead your peers and mentor them with the insights and skills that you have gained.</td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Mentees' Request & Accept">
      <i class="bx bx-user-check"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Team Building Assessments</a></td>
    <td colspan="2">These assessments help you develop the skills needed to succeed.</td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Description & Submit Solutions">
      <i class="bx bx-bar-chart-alt-2"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Self Assessments</a></td>
    <td colspan="2">These assessments help you evaluate your skills and aptitude to succeed through self-reflection techniques.</td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Problem Statement & Submit Solutions">
      <i class="bx bx-task-x"></i>
    </a></td>
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
   