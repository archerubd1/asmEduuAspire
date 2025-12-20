<?php
/**
 * Astraal LXP  - Learner WorkLife Experience
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

$page = "workLifeExperience";
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
        

	

  <div class="card mt-3">
    <div class="d-flex align-items-end row">
      <div class="col-sm-7">
     <div class="card-body">
  <p class="mb-4"> Discover pathways to enrich your work-life experience by gaining practical exposure and developing skills that align with your career goals. 
  
     Leverage the following tools and opportunities to maximize your potential:
  </p>
  <ul>
    <li><span class="fw-bold">3 immersive work-life frameworks</span> designed to simulate real-world challenges, foster collaboration, and sharpen your problem-solving abilities in workplace scenarios.</li>
  </ul>
  
  <div class="d-flex gap-2">
    <a href="#project-frameworks.php" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Explore Work-Life Frameworks">
      <i class="bx bx-briefcase"></i> Explore Frameworks
    </a>
    <a href="#expert-insights.php" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Access Insights from Professionals">
      <i class="bx bx-conversation"></i> Gain Insights
    </a>
  </div>
</div>


      </div>
      <div class="col-sm-5 text-center text-sm-left">
        <div class="card-body pb-0 px-0 px-md-4">
          <img
            src="../assets/img/illustrations/workLife-light.jpg"
            height="140"
            alt="Collaborative Learning"
            data-app-dark-img="illustrations/workLife-dark.jpg"
            data-app-light-img="illustrations/workLife-light.jpg"
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
              <i class="bx bx-compass"></i> &nbsp;&nbsp; The n.e.X.t Steps
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
    <td><a href="#">Internships</a></td>
    <td colspan="2"> An internship is a professional learning experience that offers practical work related to your field of study or career interest and the opportunity for career exploration, development, and networking. </td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Explore Internship Tasks & Opportunities">
      <i class="bx bx-briefcase-alt"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Job Shadowing</a></td>
    <td colspan="2">Job shadowing gives you the experience of following a person/mentor in their job/professional work for a few hours, a day, or a few days. </td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Connect with Mentors & Schedule Shadowing">
     <i class="bx bx-show"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Care for Community</a></td>
    <td colspan="2"> Did you know you can develop valuable work skills when you do small jobs for friends, family, or neighbors? </td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Requests & Offer Your Support">
      <i class="bx bx-link-alt"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Freelancing Work</a></td>
    <td colspan="2"> Sometimes you have to prove that you can do the work before you are hired. Freelancing is a great way to show what you can do. </td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Browse Freelancing Tasks & Submit Work">
      <i class="bx bx-pencil"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Start A Project</a></td>
    <td colspan="2"> Convert your ideas into a business venture. We will pitch in with professional and financial support. Prove your ideas' worth. </td>
    <td><a href="#toc.php" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Pitch Your Idea & Access Resources">
      <i class="bx bx-rocket"></i>
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
   