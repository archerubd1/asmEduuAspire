<?php
/**
 *  Astraal LXP - Learner Coding Ground
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

$page = "projectManagement";
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
    <h4 class="accordion-header" id="heading3">
      <button
        type="button"
        class="accordion-button bg-label-primary"
        data-bs-toggle="collapse"
        data-bs-target="#accordion1"
        aria-expanded="true"
        aria-controls="accordion1"
      >
        <i class="bx bx-cube"></i> &nbsp;&nbsp; The 7th Dimensions - Project Based Learning

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
        The 7th Dimensions is a project-based learning initiative designed to empower learners by focusing on real-world problems and multidimensional solutions. The framework emphasizes creativity, collaboration, critical thinking, and adaptability, fostering both technical and soft skills.
    </p>

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
          You’ve made remarkable progress in building your <span class="fw-bold">project execution skills</span>. Currently, you have access to:
        </p>
        <ul>
          <li><span class="fw-bold">3 new project frameworks</span> designed to enhance your creativity, teamwork, and problem-solving capabilities.</li>
          <li><span class="fw-bold">2 expert-guided insights</span> to help you implement innovative solutions to real-world challenges.</li>
        </ul>
      
        <div class="d-flex gap-2">
          <a href="#project-frameworks.php" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Browse Project Frameworks">
            <i class="bx bx-task"></i> View Frameworks
          </a>
          <a href="#expert-insights.php" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Learn from Expert Insights">
            <i class="bx bx-bulb"></i> Explore Insights
          </a>
          </div>
        </div>
      </div>
      <div class="col-sm-5 text-center text-sm-left">
        <div class="card-body pb-0 px-0 px-md-4">
          <img
            src="../assets/img/illustrations/project-management-light.jpg"
            height="140"
            alt="Project Management"
            data-app-dark-img="illustrations/project-management-dark.jpg"
            data-app-light-img="illustrations/project-management-light.jpg"
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
              <i class="bx bx-bulb"></i> &nbsp;&nbsp; Ideate & Implement these Projects.
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
                      <td>Science</a></td>
					  <td colspan="2"> How can we keep our communities safe in the face of natural hazards?  </td>
                      <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"  data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Problem Statement & Submit Solutions">
                  <i class="bx bx-play-circle"></i>
                </a></td>
                     
                    </tr>
                    <tr>
                       <td>Science</td>
					  <td colspan="2">How can we help people survive in extreme temperatures?   </td>
                      <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Problem Statement & Submit Solutions">
                  <i class="bx bx-play-circle"></i>
                </a></td>
                      
                    </tr>
                    <tr>
                      <td>Engineering</td>
					 <td colspan="2"> How can we redesign a product’s packaging to make it more environmentally friendly? </td>
                      <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="View Problem Statement & Submit Solutions">
                  <i class="bx bx-play-circle"></i>
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
   