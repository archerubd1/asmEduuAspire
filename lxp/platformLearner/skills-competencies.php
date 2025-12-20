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

$page = "skillsCompetencies";
require_once('learnerHead_Nav2.php');
?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('learnersNav.php');   ?>

 <div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-12 mb-4 order-0">
        <div class="card">
          <div class="card-header">
            <ul class="nav nav-pills mb-3 gap-3" role="tablist">
              <!-- Learning Path Tab -->
              <li class="nav-item">
                <a href="#tab-learning-path" class="nav-link active" data-bs-toggle="pill" role="tab" aria-selected="true">
                  <img src="../assets/img/hardskills1.png" alt="" width="32px" height="32px"> Hard Skills
                </a>
              </li>
              <!-- Problem Solving Tab -->
              <li class="nav-item">
                <a href="#tab-problem-solving" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
                  <img src="../assets/img/softskills1.png" alt="" width="32px" height="32px"> Soft Skills
                </a>
              </li>
              <!-- Coding Ground Tab -->
              <li class="nav-item">
                <a href="#tab-coding-ground" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
                  <img src="../assets/img/life-skills.png" alt="" width="32px" height="32px"> Life Skills
                </a>
              </li>
              <!-- Critical Thinking Tab -->
              <li class="nav-item">
                <a href="#tab-critical-thinking" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
                  <img src="../assets/img/entrskills.png" alt="" width="32px" height="32px" /> Entrepreneurial Skills
                </a>
              </li>
              <!-- Project Management Tab -->
              <li class="nav-item">
                <a href="#tab-project-management" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
                  <img src="../assets/img/digiskills.png" alt="" width="32px" height="32px" /> Digital Skills
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
			
			
              <!-- Learning Path Tab Content -->
              <div class="tab-pane fade show active" id="tab-learning-path" role="tabpanel">
                <p>Technical skills: Programming languages, software proficiency, data analysis, etc...</p>
                <h5>Pick up the Hard Skills Domain for Self Assessment</h5>
                
                <h5>IT Skills</h5>
                <div class="d-flex flex-wrap gap-3 mb-4">
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">SQL</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Python</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Oracle Java</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">AWS</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">JavaScript</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">SAP</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Microsoft Azure</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Microsoft Access</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Linux</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">HTML</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Atlassian JIRA</button>
                </div>

                <h5>Finance</h5>
                <div class="d-flex flex-wrap gap-3 mb-4">
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Financial Analysis</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Budgeting</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Risk Management</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Financial Modeling</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Investment Management</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Accounting</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Auditing</button>
                </div>

                <h5>Marketing</h5>
                <div class="d-flex flex-wrap gap-3 mb-4">
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Market Research</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Digital Marketing</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Social Media Marketing</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Brand Management</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Content Marketing</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">SEO</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">PPC Advertising</button>
                </div>

                <h5>Data Analysis</h5>
                <div class="d-flex flex-wrap gap-3">
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Data Mining</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Data Visualization</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Statistical Analysis</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Machine Learning</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Big Data Analytics</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Predictive Analytics</button>
                  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Data Warehousing</button>
                </div>
              </div>


			<!-- Softskills  Tab Content -->
              <div class="tab-pane fade" id="tab-problem-solving" role="tabpanel">
						
														<p>
							  Communication: Verbal communication, written communication, active listening, presentation skills, interpersonal skills, negotiation skills, conflict resolution, empathy, etc.  
							  Leadership: Decision-making, team building, problem-solving, delegation, motivation, mentoring, conflict resolution, etc.  
							  Emotional Intelligence: Self-awareness, self-regulation, empathy, resilience, adaptability, stress management, motivation, etc.  
							  Creativity: Innovation, critical thinking, brainstorming, design thinking, problem-solving, etc.  
							  Time Management: Prioritization, organization, goal setting, task delegation, meeting deadlines, etc.  
							</p>

							<h5>Soft Skills Domains for Self-Assessment</h5>

							<!-- Communication Skills -->
<h5>Communication Skills</h5>
<div class="d-flex flex-wrap gap-3">
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Verbal Communication</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Written Communication</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Active Listening</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Presentation Skills</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Interpersonal Skills</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Negotiation Skills</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Conflict Resolution</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Empathy</button>
</div>
<p>&nbsp;</p>

<!-- Leadership Skills -->
<h5>Leadership Skills</h5>
<div class="d-flex flex-wrap gap-3">
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Decision-Making</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Team Building</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Problem Solving</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Delegation</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Motivation</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Mentoring</button>
</div>
<p>&nbsp;</p>

<!-- Emotional Intelligence -->
<h5>Emotional Intelligence</h5>
<div class="d-flex flex-wrap gap-3">
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Self-Awareness</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Self-Regulation</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Motivation</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Empathy</button>
  <button type="button" class="btn btn-secondary btn-sm btn-rounded">Adaptability</button>
</div>
<p>&nbsp;</p>


              </div>

			<!-- Life skills  Tab Content -->
              <div class="tab-pane fade" id="tab-coding-ground" role="tabpanel">
                <h5>Life Skills Domains for Self Assessment</h5>

                <!-- Financial Literacy -->
                <h5>Financial Literacy</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Budgeting</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Saving</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Investing</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Debt Management</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Financial Planning</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Taxation</button>
				</div>
<p>&nbsp;</p>
                <!-- Household Management -->
                <h5>Household Management</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Grocery Shopping</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Cleaning</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Home Maintenance</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Laundry</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Organizing</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Meal Planning</button>
				</div>

   <p>&nbsp;</p>             

                <!-- Time Management -->
                <h5>Time Management</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Prioritization</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Goal Setting</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Task Scheduling</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Procrastination Management</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Time Tracking</button>
				</div>
              </div>
			  
			  <!-- Entrepreneuer skills  Tab Content -->
              <div class="tab-pane fade" id="tab-critical-thinking" role="tabpanel">
                
				
				 <p>(Business planning, market research, customer service, budgeting, networking, etc.)</p>
										 
										 
										  <h5>Entrepreneurial Skills Domains for Self Assessment</h5>

                <!-- Business Planning -->
                <h5>Business Planning</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Business Model Canvas</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">SWOT Analysis</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Market Analysis</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Financial Projections</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Strategic Planning</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Market Research -->
                <h5>Market Research</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Primary Research</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Secondary Research</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Competitor Analysis</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Market Segmentation</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Trend Analysis</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Customer Service -->
                <h5>Customer Service</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Effective Communication</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Problem Resolution</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Customer Satisfaction</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Feedback Management</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Service Recovery</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Budgeting -->
                <h5>Budgeting</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Expense Tracking</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Revenue Forecasting</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Cost Control</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Financial Analysis</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Cash Flow Management</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Networking -->
                <h5>Networking</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Building Connections</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Relationship Building</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Industry Events</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Online Networking</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Referral Programs</button>
				</div>
              </div>
			  
			  <!-- Digital skills  Tab Content -->
              <div class="tab-pane fade" id="tab-project-management" role="tabpanel">
                
				
				 <p>(Information literacy, online communication, cybersecurity, digital marketing, social media management, etc.)</p>
										 
										  <h5>Digital Skills Domains for Self Assessment</h5>

                <!-- Information Literacy -->
                <h5>Information Literacy</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Research Skills</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Source Evaluation</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Critical Thinking</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Data Analysis</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Citation Management</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Online Communication -->
                <h5>Online Communication</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Email Etiquette</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Video Conferencing</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Instant Messaging</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Collaboration Tools</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Virtual Teamwork</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Cybersecurity -->
                <h5>Cybersecurity</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Password Management</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Data Protection</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Internet Safety</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Phishing Awareness</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Secure Browsing</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Digital Marketing -->
                <h5>Digital Marketing</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">SEO (Search Engine Optimization)</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">SEM (Search Engine Marketing)</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Email Marketing</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Content Marketing</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Analytics and Reporting</button>
				</div>
 <p>&nbsp;</p> 
                <!-- Social Media Management -->
                <h5>Social Media Management</h5>
				<div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Content Creation</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Community Engagement</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Audience Targeting</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Social Listening</button>
                <button type="button" class="btn btn-secondary btn-sm btn-rounded">Campaign Optimization</button>
				</div>
              </div>
			  
			  


              <!-- Other Tabs Content -->
              <!-- Add similar structure for other tabs -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>







<?php 
require_once('../platformFooter.php');
?>
   