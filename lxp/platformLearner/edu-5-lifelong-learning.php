<?php
/**
 * Astraal LXP  - Learner Edu 5.0 Lifelong Learning
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

$page = "edu5.0";
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
  <p class="mb-4"> Leverage Edu 5.0 to enhance your career journey with practical learning experiences that equip you with essential skills. Gain firsthand exposure to professional environments and apply your knowledge in real-world situations. 
  
      </p>
  <ul>
    <li><span class="fw-bold">2 innovative career development frameworks</span> designed to simulate real-world workplace challenges, foster critical thinking, and refine your problem-solving skills in collaborative settings.</li>
  </ul>
  
  <div class="d-flex gap-2">
  <a href="#career-pathways.php" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Discover Career Pathways and Skill Building Frameworks">
    <i class="bx bx-rocket"></i> Explore Pathways
  </a>
  <a href="#career-insights.php" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Access Expert Insights for Career Advancement">
    <i class="bx bx-brain"></i> Unlock Insights
  </a>
</div>

</div>



      </div>
      <div class="col-sm-5 text-center text-sm-left">
        <div class="card-body pb-0 px-0 px-md-4">
          <img
            src="../assets/img/illustrations/edu5-light.jpg"
            height="140"
            alt="Edu 5.0"
            data-app-dark-img="illustrations/edu5-dark.jpg"
            data-app-light-img="illustrations/edu5-light.jpg"
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
              <i class="bx bx-compass"></i> &nbsp;&nbsp; The metamagical Themas
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
                     
					  <th class="text-center">Personalized Activities</th>
                      <th class="text-center"colspan="2">Description</th>
                     
                     
                    
                    </tr>
                    </thead>
     <tbody>
  <tr>
    <td><a href="#">Genius Hours</a></td>
    <td colspan="2">Choose Your Own Learning Topic, Content, and Methodology.</td>
    <td><a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Customize Your Learning Path and Content">
      <i class="bx bx-brain"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">Passion Projects</a></td>
    <td colspan="2">Follow your passions and develop them into a project that meets and aligns with academic/work/life skills and outcomes.</td>
    <td><a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Transform Passions into Projects with Real-world Impact">
      <i class="bx bx-heart"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">The Peripheral</a></td>
    <td colspan="2">Beyond the mundane AI/ML/VR/AR/MR.</td>
    <td><a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Explore Cutting-edge Technologies and Innovations">
      <i class="bx bx-plug"></i>
    </a></td>
  </tr>
  <tr>
    <td><a href="#">The Minds' I</a></td>
    <td colspan="2">Challenge the notions of self and identity, Mind as Program to The Inner Eye to ...</td>
    <td><a href="#" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Explore Freelance Opportunities for Creative Minds">
      <i class="bx bx-pen"></i>
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
   