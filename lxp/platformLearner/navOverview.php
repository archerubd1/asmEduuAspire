<?php 
/**
 * Astraal LXP - Learner Learning Paths (Tests & Quizzes)
 * Debugged for unified session-guard
 * Dynamic AutoLogin fetched from users.autologin column
 * PHP 5.4 compatible | UwAmp / GoDaddy
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // unified session management

$page = "navOverview";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// ✅ Validate Session (Phoenix unified keys)
// -----------------------------------------------------------------------------
if (
    !isset($_SESSION['phx_user_id']) ||
    !isset($_SESSION['phx_user_login'])
) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

// Extract session vars
$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];
$phx_user_name  = isset($_SESSION['phx_user_name']) ? $_SESSION['phx_user_name'] : '';

?>


        <!-- Layout container -->
        <div class="layout-page">
          
		 
          
          <!-- Content wrapper -->
<div class="content-wrapper">
            <!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-pills mb-0" role="tablist">
          <!-- Learning Path Tab -->
          <li class="nav-item" style="margin-right: 6px;">
            <a href="#tab-learning-path" class="nav-link active" data-bs-toggle="pill" role="tab" aria-selected="true">
              <i class="bx bx-flag me-2"></i>Learning Path
            </a>
          </li>
          <!-- Problem Solving Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-problem-solving" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-book me-2"></i>Problem Solving Skills
            </a>
          </li>
          <!-- Coding Ground Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-coding-ground" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-line-chart me-2"></i>Coding Ground
            </a>
          </li>
          <!-- Critical Thinking Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-critical-thinking" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-headphone me-2"></i>Critical Thinking
            </a>
          </li>
          <!-- Project Management Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-project-management" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-briefcase me-2"></i>Project Management
            </a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <!-- Learning Path Tab Content -->
          <div class="tab-pane fade show active" id="tab-learning-path" role="tabpanel">
            <h5 class="card-title text-primary">Welcome PlatformLearner! 🎉</h5>
            <p>It can be tough to know where to start <i>learning</i>. Our handpicked curated Learning Paths combine specific courses and tools into one experience to teach you any given skill from start to finish. 
													<p>Learning Paths are aligned to an individual's knowledge level, to help you and your team develop the right skills in the right order.
													<p>The <b>content is evolving</b> along with everything else around us. Let's make the smaller things learnable so we can make the bigger things possible.	Learning Paths 
													<ul><li>Enhances' the learning <i>when and how you need it</i> <li>Facilitates Personalized Learning <li>Helps Close the Skills Gap <li>Promotes Real Learning <li>Reduces the Forgetting Curve <li>Closes the Salary Gap</ul>
												
			<a href="#learning-path.php" class="btn btn-sm btn-outline-primary" role="button" aria-label="Walk the Path">
				Walk the Path
			  </a>
          </div>
          
          <!-- Problem Solving Skills Tab Content -->
          <div class="tab-pane fade" id="tab-problem-solving" role="tabpanel">
            <h5 class="card-title text-success">Enhance Your Problem-Solving Skills</h5>
            <p>Everybody can benefit from having good problem solving skills as we all encounter problems on a daily basis. Some of these problems are obviously more severe or complex than others.
														<p><b>Problem solving skills are highly sought after by employers as many companies rely on their employees to identify and solve problems.</b>
														<p>To be <i>effective at problem solving</i> you are likely to need some other key skills, which include:
														<ul><li>Creativity <li>Researching Skills <li>Team Working <li>Emotional Intelligence <li>Risk Management <li>Decision Making</ul>
															
            <a href="#problem-solving-courses.php" class="btn btn-sm btn-outline-success">Explore Challenges</a>
          </div>
          
          <!-- Coding Ground Tab Content -->
          <div class="tab-pane fade" id="tab-coding-ground" role="tabpanel">
            <h5 class="card-title text-info">Practice Coding in Our Ground</h5>
            <p>At its heart, <b>coding</b> is expression and problem solving. You can focus on its applications, or on programming languages, but no matter how you practice it, you’ll cultivate these two essential skills, which will help you in all aspects of life.
														<p>Besides existential value, learning to <b>code proficiently</b> will offer you myriad job opportunities, the ability to create your own schedule/work from anywhere, high wages for less hours of labor, eager to please clients that need/search for your help, and much more.
														<p>One of the <b>greatest benefits from coding</b> is consistently entering a state of flow, in which time, distraction and frustration melts away, allowing the coder to form a union with the task at hand.
														<p>Coding casually or professionally can improve your life. So how to begin? We ensure you develop these ten skills that every coder needs.
														<ul><li>Self-Reliance and Programming Language <li>Logic and Attention to Detail <li>Recognition of Stupidity and Abstract Thinking <li>Patience and Strong Memory <li>Scientific Method and Communication and Empathy</ul>
														
            <a href="#coding-ground.php" class="btn btn-sm btn-outline-info">Start Coding</a>
          </div>
          
          <!-- Critical Thinking Tab Content -->
          <div class="tab-pane fade" id="tab-critical-thinking" role="tabpanel">
            <h5 class="card-title text-warning">Sharpen Your Critical Thinking</h5>
           <p>Critical thinking is the ability to think clearly and rationally, understanding the logical connection between ideas.
														<p>Critical Thinking is, in short, self-directed, self-disciplined, self-monitored, and self-corrective thinking.
														<p>The Importance and Benefits of "Critical Thinking Skills":
														<ul><li>Is a Domain-general Thinking Skill <li>Is very important in the New Knowledge Economy <li>Enhances Language and Presentation Skills <li>Promotes Creativity <li>Is Crucial for Self-Reflection <li>Develops the Ability to Engage in Reflective and Independent Thinking</ul>
														<p align="center"><b>In essence, critical thinking requires you to use your ability to reason. It is about being an active learner rather than a passive recipient of information.</b></p>
															
            <a href="#critical-thinking.php" class="btn btn-sm btn-outline-warning">Learn More</a>
          </div>
          
          <!-- Project Management Tab Content -->
          <div class="tab-pane fade" id="tab-project-management" role="tabpanel">
            <h5 class="card-title text-info">Master Project Management</h5>
            <p>By bringing real-life context and technology to the curriculum through a <b>Project Based Learning</b> approach, students are encouraged to become independent workers, critical thinkers, and lifelong learners.  
														<p>Project Based Learning allows participants to gain knowledge and skills by working for an extended period of time to investigate and respond to an authentic, engaging, and complex question, problem, or challenge.
														<p>The benefits of the Projects' in the Learning journey:
														<ul><li>Connects Participants to Real-World Problems <li>Prepares participants to accept and meet challenges in the real world, mirroring what professionals do every day <li>Provides an Opportunity to engage deeply with the target content, bringing about a focus on long-term retention <li>Builds Intrinsic motivation because it centers around an essential central question or problem and a meaningful outcome <li>Enhances technology abilities, team work and problem solving skills</ul>
												
            <a href="#project-management.php" class="btn btn-sm btn-outline-info">Get Started</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Row 2 of Navigation Items -->
<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-pills mb-0" role="tablist">
          <!-- Collaborative Learning Tab -->
          <li class="nav-item" style="margin-right: 6px;">
            <a href="#tab-collaborative-learning" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="true">
              <i class="bx bx-group me-2"></i>Collaborative Learning
            </a>
          </li>
          <!-- Work Life Experience Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-work-life-experience" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-briefcase me-2"></i>Work Life Experience
            </a>
          </li>
          <!-- Edu 5.0 Learning Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-edu-5-0-learning" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-book-open me-2"></i>Edu 5.0 Learning
            </a>
          </li>
          <!-- Skills & Competencies Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-skills-competencies" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-cogs me-2"></i>Skills & Competencies
            </a>
          </li>
          <!-- Mentor & Social Learning Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-mentor-social-learning" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="bx bx-user-circle me-2"></i>Mentor & Social Learning
            </a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <!-- Collaborative Learning Tab Content -->
          <div class="tab-pane fade" id="tab-collaborative-learning" role="tabpanel" aria-labelledby="collaborative-learning-tab">
			  <h5 class="card-title text-primary">Collaborative Learning Opportunities</h5>
			 <p>With Collaborative Learning, skills such as decision making, flexibility and problem-solving come to the fore. 
													<p>Collaborative Learning educational experiences are active, social, contextual, engaging, and student-owned lead to deeper learning.
													<p>The benefits of Collaborative Learning include:
													<ul><li>Development of higher-level thinking, oral communication, self-management, and leadership skills <li>Promotion of interaction among the stakeholders <li>Increase in learning retention, self-esteem, and responsibility <li>Exposure to and an increase in understanding of diverse perspectives <li>Preparation for real life social and employment situations </ul>	
											</p>
			  <a href="#collaborative-learning.php" class="btn btn-sm btn-outline-primary" role="button" aria-label="Connet with Peers">
				Connect with Peers
			  </a>
			</div>

          
          <!-- Work Life Experience Tab Content -->
          <div class="tab-pane fade" id="tab-work-life-experience" role="tabpanel">
            <h5 class="card-title text-success">Leverage Your Work Life Experience</h5>
            <p>Today’s economy requires that you be unique and different.
													<p>Workplace Experience thru Internships, Gig-Works will complement your academic studies by providing another way of learning. It will also provide you with crucial knowledge, skills and personal attributes that employers look for.	
													<p>Types of Work Life Experience that are supported and available on our Platform are as follows:
													<ul><li>Part of a sandwich course with an industrial placement <li>A shorter work placement, which is also part of a course of study <li>Internships including Virtual Internships <li>Work shadowing, when you ‘shadows’ an experienced professional <li>A wide range of other possibilities, including part-time paid work, gig-works' </ul>
								
            </p>
            <a href="#work-life-experience.php" class="btn btn-sm btn-outline-success">Explore Experiences</a>
          </div>
          
          <!-- Edu 5.0 Learning Tab Content -->
          <div class="tab-pane fade" id="tab-edu-5-0-learning" role="tabpanel">
            <h5 class="card-title text-info">Transform with Edu 5.0 Learning</h5>
            <p>Edu 5.0 Lifelong Learning - is the new age education that is beyond boundaries, beyond the educational institution, and is transnational in the real sense of the term.  
													<p>Edu 5.0 Lifelong Learning is a transformational change driven by the use of advanced digital technologies that places the learner at the centre of the teaching-learning process enabling him to govern his own academic development towards his unique goals. 
													<p>Key takewayas of Edu 5.0 Lifelong Learning are as follows:
													<ul><li>At every level you are learning and growing so that you can transform and adapt to an ever-changing world <li>Encourages and Empowers you to explore and implement creative ideas that enable future value delivery <li>Focuses on continuously improvement</ul>

            </p>
            <a href="#edu-5-0-learning.php" class="btn btn-sm btn-outline-info">Explore Edu 5.0</a>
          </div>
          
          <!-- Skills & Competencies Tab Content -->
          <div class="tab-pane fade" id="tab-skills-competencies" role="tabpanel">
            <h5 class="card-title text-warning">Boost Your Skills & Competencies</h5>
           <p>Skills and Competencies: What is the Difference? 
													<p><b>Skills</b> are specific learned abilities. They are what a person can (or cannot) do.
													<p><b>Competencies</b> encompass skills, along with knowledge and behavior. They are how a person performs on the job. 
													<p>Use our skill and competency matrix for:
													<ul><li>Competency Management <li>Close Skills Gap with ReSkilling <li>Unlock the Expertise Knowledge Economy with Upskilling</ul>
										
            </p>
            <a href="#skills-competencies.php" class="btn btn-sm btn-outline-warning">Gain Skills Now</a>
          </div>
          
          <!-- Mentor & Social Learning Tab Content -->
          <div class="tab-pane fade" id="tab-mentor-social-learning" role="tabpanel">
            <h5 class="card-title text-info">Mentorship & Social Learning</h5>
            <p>Partnering to accelerate learning in the digital age​ with Consulting and professional development that is customized, intensive, and impactful​
														<p>At a high level, you will learn that there are five factors that provide a fertile environment for Mentor & Social Learning:
														<ul><li>Purpose <li>Individual inclination and skills <li>Solid interpersonal relationships <li>Appropriate activities <li>Supportive tools </ul>
											
            </p>
            <a href="#mentor-social-learning.php" class="btn btn-sm btn-outline-info">Join Now</a>
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
   