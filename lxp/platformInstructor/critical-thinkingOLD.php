<?php
/**
 * Astraal LXP - Instructor Critical Thinking 
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

$page = "ganification";
require_once('instructorHead_Nav2.php');
?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('instructorNav.php');   ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
			    
<?php
// Check for success or informational message and display SweetAlert if exists
if (isset($_REQUEST['msg'])) {
    $successMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("Successful!", "' . $successMessage . '", "success");
                // Remove the message from the URL without reloading the page
                var urlWithoutMsg = window.location.origin + window.location.pathname;
                history.replaceState({}, document.title, urlWithoutMsg);
            });
          </script>';
}

// Check for error message and display SweetAlert if exists
if (isset($_REQUEST['error'])) {
    $errorMessage = base64_decode(urldecode($_GET['error']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("Invalid Registration!!", "' . $errorMessage . '", "error");
                // Remove the message from the URL without reloading the page
                var urlWithoutError = window.location.origin + window.location.pathname;
                history.replaceState({}, document.title, urlWithoutError);
            });
          </script>';
}				
?>
<style>
       
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
       
        
        .icon {
            margin-right: 8px;
            font-size: 20px;
            color: #007bff;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .hidden {
            display: none;
        }

    </style>
	
<div class="col-lg-12 mb-4 order-0">
 
 <!-- Accordion for course description -->
<div class="accordion mt-3" id="accordionExample">
  <!-- Accordion Item for course description -->
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
         Manage Learning Journey &nbsp;&nbsp;  <i class="bx bx-bulb"></i> &nbsp;&nbsp;  Critical Thinking Assignments
      </button>
    </h4>
    <div
      id="accordion1"
      class="accordion-collapse collapse show"  <!-- Added "show" class -->
     
      <div class="accordion-body">
        
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
               
					<!-- Assignment Type Selection -->
					<div class="mb-3">
						<label for="learning_category" class="form-label">
							<i class="bx bx-list-ul icon"></i> Choose Your Critical Thinking Assignment to Create
						</label>
						   <select id="assignmentType" name="assignmentType" onchange="showForm()" required>
							<option value="">-- Select --</option>
							<option value="factOpinion">Fact or Opinion</option>
							<option value="coffeeChat">Coffee House Chat</option>
							<option value="worldlyWords">Worldly Words</option>
							<option value="alienGuide">Alien Travel Guide</option>
							<option value="talkItOut">Talk It Out</option>
							<option value="elevatorPitch">Elevator Pitch</option>
							</select>
					</div>
					

				<script>
					function showForm() {
						// Hide all forms initially
						let forms = document.querySelectorAll(".hidden");
						forms.forEach(form => form.style.display = "none");

						// Get selected value
						let selectedValue = document.getElementById("assignmentType").value;

						// Show relevant form based on selection
						if (selectedValue === "factOpinion") {
							document.getElementById("factOpinionForm").style.display = "block";
						} else if (selectedValue === "coffeeChat") {
							document.getElementById("coffeeChatForm").style.display = "block";
						} else if (selectedValue === "worldlyWords") {
							document.getElementById("worldlyWordsForm").style.display = "block";
						} else if (selectedValue === "alienGuide") {
							document.getElementById("alienGuideForm").style.display = "block";
						} else if (selectedValue === "talkItOut") {
							document.getElementById("talkItOutForm").style.display = "block";
						} else if (selectedValue === "elevatorPitch") {
							document.getElementById("elevatorPitchForm").style.display = "block";
						}
					}
				</script>  
			   
              </div>
            </div>
		</div>
		  
		  
		  
		 <!-- Fact or Opinion Form -->
			<div id="factOpinionForm" class="hidden">
			<div class="d-flex align-items-end row">
            <div class="col-sm-12">
			<div class="card-body">
                
				<label for="factTopic"> <h4>📌 Fact or Opinion Assignment Creation</h4></label>
				 <hr class="m-0" />
				 <p><br>
				<form>
    <!-- Row 1 -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="title">
                <i class="bx bx-edit icon" style="color: #ff5733;"></i> Assignment Title
            </label>
            <select id="title" name="title" required>
                <option value="Fact vs. Opinion Challenge">Fact vs. Opinion Challenge</option>
                <option value="Critical Thinking: Fact or Opinion">Critical Thinking: Fact or Opinion</option>
                <option value="Fact vs. Opinion Quiz">Fact vs. Opinion Quiz</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="description">
                <i class="bx bx-book icon" style="color: #28a745;"></i> Assignment Description
            </label>
            <select id="description" name="description" required>
                <option value="Analyze and classify statements as fact or opinion.">Analyze and classify statements as fact or opinion.</option>
                <option value="Develop critical thinking skills by identifying factual and opinion-based statements.">Develop critical thinking skills by identifying factual and opinion-based statements.</option>
                <option value="Differentiate between verifiable facts and personal viewpoints.">Differentiate between verifiable facts and personal viewpoints.</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="objectives">
                <i class="bx bx-target-lock icon" style="color: #dc3545;"></i> Learning Objectives
            </label>
            <select id="objectives" name="objectives" required>
                <option value="Identify the difference between facts and opinions.">Identify the difference between facts and opinions.</option>
                <option value="Develop analytical thinking for evaluating statements.">Develop analytical thinking for evaluating statements.</option>
                <option value="Strengthen reasoning and justification skills.">Strengthen reasoning and justification skills.</option>
            </select>
        </div>
    </div>

    <!-- Row 2 -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="instructions">
                <i class="bx bx-list-check icon" style="color: #17a2b8;"></i> Instructions
            </label>
            <select id="instructions" name="instructions" required>
                <option value="Classify each given statement as a Fact or Opinion.">Classify each given statement as a Fact or Opinion.</option>
                <option value="Provide reasoning for each classification.">Provide reasoning for each classification.</option>
                <option value="Submit your answers via text entry or file upload.">Submit your answers via text entry or file upload.</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="example">
                <i class="bx bx-message-square-detail icon" style="color: #ffc107;"></i> Example Statements
            </label>
            <select id="example" name="example" required>
                <option value="The Earth revolves around the Sun. (Fact)">The Earth revolves around the Sun. (Fact)</option>
                <option value="Chocolate is the best ice cream flavor. (Opinion)">Chocolate is the best ice cream flavor. (Opinion)</option>
                <option value="Water boils at 100°C at sea level. (Fact)">Water boils at 100°C at sea level. (Fact)</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label>
                <i class="bx bx-bar-chart icon" style="color: #6c757d;"></i> Difficulty Level
            </label>
            <select name="difficulty" required>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
        </div>
    </div>

    <!-- Row 3 -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>
                <i class="bx bx-upload icon" style="color: #6610f2;"></i> Submission Format
            </label>
            <select name="submission_format" required>
                <option value="Online Text Entry">Online Text Entry</option>
                <option value="File Upload">File Upload (Word/PDF)</option>
                <option value="Discussion Post">Discussion Post</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="deadline">
                <i class="bx bx-calendar icon" style="color: #fd7e14;"></i> Submission Deadline
            </label>
            <input type="datetime-local" id="deadline" name="deadline" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="criteria">
                <i class="bx bx-trophy icon" style="color: #e83e8c;"></i> Evaluation Criteria
            </label>
            <select id="criteria" name="criteria" required>
                <option value="Correct Classification (50%), Justification (30%), Clarity (20%)">Correct Classification (50%), Justification (30%), Clarity (20%)</option>
                <option value="Accuracy (40%), Explanation (40%), Structure (20%)">Accuracy (40%), Explanation (40%), Structure (20%)</option>
            </select>
        </div>
    </div>

    <!-- Row 4 -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <label for="outcome">
                <i class="bx bx-brain icon" style="color: #20c997;"></i> Expected Learning Outcome
            </label>
            <select id="outcome" name="outcome" required>
                <option value="Analyze information critically and identify bias.">Analyze information critically and identify bias.</option>
                <option value="Improve logical reasoning and decision-making skills.">Improve logical reasoning and decision-making skills.</option>
                <option value="Strengthen media literacy and critical evaluation abilities.">Strengthen media literacy and critical evaluation abilities.</option>
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-success">
        <i class="bx bx-check-circle" style="color: #198754;"></i> Create Assignment
    </button>
</form>	 
			</div>
			</div>
			</div>
			</div>
	

 <!-- Coffee House Chat Form -->
    <div id="coffeeChatForm" class="hidden">
	
	<div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="chatTopic"> <h4>☕ Coffee House Role-Playing Assignment</h4></label>
            <hr class="m-0" />
            <p><br>
            <form>
                <!-- Row 1 -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="scenario">
                            <i class="bx bx-conversation icon" style="color: #ff5733;"></i> Role-Playing Scenario
                        </label>
                        <select id="scenario" name="scenario" required>
                            <option value="Ordering Coffee">Ordering Coffee</option>
                            <option value="Casual Small Talk">Casual Small Talk</option>
                            <option value="Handling a Customer Complaint">Handling a Customer Complaint</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="description">
                            <i class="bx bx-comment-detail icon" style="color: #28a745;"></i> Assignment Description
                        </label>
                        <select id="description" name="description" required>
                            <option value="Practice conversational skills in a coffee house setting.">Practice conversational skills in a coffee house setting.</option>
                            <option value="Engage in active listening and appropriate responses.">Engage in active listening and appropriate responses.</option>
                            <option value="Improve interpersonal communication techniques.">Improve interpersonal communication techniques.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="objectives">
                            <i class="bx bx-brain icon" style="color: #dc3545;"></i> Learning Objectives
                        </label>
                        <select id="objectives" name="objectives" required>
                            <option value="Develop conversational fluency and confidence.">Develop conversational fluency and confidence.</option>
                            <option value="Enhance active listening and response skills.">Enhance active listening and response skills.</option>
                            <option value="Learn how to handle different social interactions.">Learn how to handle different social interactions.</option>
                        </select>
                    </div>
                </div>
                
                <!-- Row 2 -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="instructions">
                            <i class="bx bx-list-check icon" style="color: #17a2b8;"></i> Instructions
                        </label>
                        <select id="instructions" name="instructions" required>
                            <option value="Role-play a given scenario with a partner.">Role-play a given scenario with a partner.</option>
                            <option value="Use appropriate tone, language, and expressions.">Use appropriate tone, language, and expressions.</option>
                            <option value="Record and submit your conversation.">Record and submit your conversation.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="example">
                            <i class="bx bx-coffee-togo icon" style="color: #ffc107;"></i> Example Dialogues
                        </label>
                        <select id="example" name="example" required>
                            <option value="Can I get a cappuccino, please?">Can I get a cappuccino, please?</option>
                            <option value="How's your day going so far?">How's your day going so far?</option>
                            <option value="I'm sorry, but my order is incorrect.">I'm sorry, but my order is incorrect.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>
                            <i class="bx bx-bar-chart icon" style="color: #6c757d;"></i> Difficulty Level
                        </label>
                        <select name="difficulty" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>
                            <i class="bx bx-upload icon" style="color: #6610f2;"></i> Submission Format
                        </label>
                        <select name="submission_format" required>
                            <option value="Audio Recording">Audio Recording</option>
                            <option value="Video Recording">Video Recording</option>
                            <option value="Text Transcript">Text Transcript</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="deadline">
                            <i class="bx bx-calendar icon" style="color: #fd7e14;"></i> Submission Deadline
                        </label>
                        <input type="datetime-local" id="deadline" name="deadline" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="criteria">
                            <i class="bx bx-trophy icon" style="color: #e83e8c;"></i> Evaluation Criteria
                        </label>
                        <select id="criteria" name="criteria" required>
                            <option value="Fluency (40%), Engagement (30%), Accuracy (30%)">Fluency (40%), Engagement (30%), Accuracy (30%)</option>
                            <option value="Clarity (35%), Expression (35%), Relevance (30%)">Clarity (35%), Expression (35%), Relevance (30%)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="outcome">
                            <i class="bx bx-brain icon" style="color: #20c997;"></i> Expected Learning Outcome
                        </label>
                        <select id="outcome" name="outcome" required>
                            <option value="Enhance confidence in real-life conversations.">Enhance confidence in real-life conversations.</option>
                            <option value="Improve verbal and non-verbal communication skills.">Improve verbal and non-verbal communication skills.</option>
                            <option value="Develop interpersonal and social skills.">Develop interpersonal and social skills.</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-check-circle" style="color: #198754;"></i> Create Assignment
                </button>
            </form>
        </div>
    </div>
</div>
    </div>
	
	
	
	<!-- Worldly Words Form -->
    <div id="worldlyWordsForm" class="hidden">
      <div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="worldlyWords"><h4>🌍 Worldly Words Assignment</h4></label>
            <hr class="m-0" />
            <p><br>
            <form>
                <!-- Row 1: Word Selection -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="words">
                            <i class="bx bx-font icon" style="color: #ff5733;"></i> Choose Your 10 Words
                        </label>
                        <input type="text" id="words" name="words" placeholder="Enter 10 words, separated by commas" required>
                    </div>
                </div>

                <!-- Row 2: Scenario Selection -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="scenario">
                            <i class="bx bx-world icon" style="color: #28a745;"></i> Scenario
                        </label>
                        <select id="scenario" name="scenario" required>
                            <option value="Convincing Someone to Help You">Convincing Someone to Help You</option>
                            <option value="Describing an Object Without Naming It">Describing an Object Without Naming It</option>
                            <option value="Expressing Emotions & Feelings">Expressing Emotions & Feelings</option>
                            <option value="Explaining a Complex Concept Simply">Explaining a Complex Concept Simply</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="communication">
                            <i class="bx bx-chat icon" style="color: #007bff;"></i> Communication Style
                        </label>
                        <select id="communication" name="communication" required>
                            <option value="Formal & Professional">Formal & Professional</option>
                            <option value="Casual & Friendly">Casual & Friendly</option>
                            <option value="Persuasive & Influential">Persuasive & Influential</option>
                            <option value="Minimalist & Direct">Minimalist & Direct</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3: Expression Format -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="format">
                            <i class="bx bx-pencil icon" style="color: #fd7e14;"></i> Submission Format
                        </label>
                        <select id="format" name="format" required>
                            <option value="Short Written Piece (250-500 words)">Short Written Piece (250-500 words)</option>
                            <option value="Video Presentation (1-3 Minutes)">Video Presentation (1-3 Minutes)</option>
                            <option value="Dialogue or Script">Dialogue or Script</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="creativity">
                            <i class="bx bx-brush icon" style="color: #e83e8c;"></i> Creativity Element
                        </label>
                        <select id="creativity" name="creativity" required>
                            <option value="Use Humor or Metaphors">Use Humor or Metaphors</option>
                            <option value="Make It Poetic or Rhythmic">Make It Poetic or Rhythmic</option>
                            <option value="Use Gestures & Expressions (for video)">Use Gestures & Expressions (for video)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: Evaluation Criteria -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="clarity">
                            <i class="bx bx-check-shield icon" style="color: #198754;"></i> Clarity & Effectiveness (30%)
                        </label>
                        <input type="range" min="0" max="100" id="clarity" name="clarity">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="criticalThinking">
                            <i class="bx bx-brain icon" style="color: #007bff;"></i> Critical Thinking & Adaptability (40%)
                        </label>
                        <input type="range" min="0" max="100" id="criticalThinking" name="criticalThinking">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="engagement">
                            <i class="bx bx-palette icon" style="color: #6c757d;"></i> Creativity & Engagement (30%)
                        </label>
                        <input type="range" min="0" max="100" id="engagement" name="engagement">
                    </div>
                </div>

                <!-- Row 5: Deadline & Submission -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="deadline">
                            <i class="bx bx-calendar icon" style="color: #fd7e14;"></i> Submission Deadline
                        </label>
                        <input type="datetime-local" id="deadline" name="deadline" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="submission">
                            <i class="bx bx-upload icon" style="color: #6610f2;"></i> Upload Your Work
                        </label>
                        <input type="file" id="submission" name="submission" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-check-circle" style="color: #198754;"></i> Assign Task
                </button>
            </form>
        </div>
    </div>
</div>

    </div>
	
	
 <!-- Alien Travel Guide Form -->
    <div id="alienGuideForm" class="hidden">
      <div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="alienGuide"><h4>🛸 Alien Travel Guide Assignment</h4></label>
            <hr class="m-0" />
            <p><br>
            <form>
                <!-- Row 1: Scenario Selection -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="scenario">
                            <i class="bx bx-world icon" style="color: #ff5733;"></i> Scenario
                        </label>
                        <select id="scenario" name="scenario" required>
                            <option value="Explaining Earth to an Alien Ambassador">Explaining Earth to an Alien Ambassador</option>
                            <option value="Giving a Space Traveler a Guide to Human Life">Giving a Space Traveler a Guide to Human Life</option>
                            <option value="Presenting Earth’s Technology & Innovation">Presenting Earth’s Technology & Innovation</option>
                            <option value="Describing Human Values & Society to an Observer">Describing Human Values & Society to an Observer</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tone">
                            <i class="bx bx-chat icon" style="color: #28a745;"></i> Tone & Style
                        </label>
                        <select id="tone" name="tone" required>
                            <option value="Scientific & Logical">Scientific & Logical</option>
                            <option value="Narrative & Storytelling">Narrative & Storytelling</option>
                            <option value="Comparative (Earth vs. Alien Civilization)">Comparative (Earth vs. Alien Civilization)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Key Topics -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="humanValues">
                            <i class="bx bx-heart icon" style="color: #dc3545;"></i> Human Values Focus
                        </label>
                        <select id="humanValues" name="humanValues" required>
                            <option value="Kindness, Empathy, and Cooperation">Kindness, Empathy, and Cooperation</option>
                            <option value="Freedom, Rights, and Ethics">Freedom, Rights, and Ethics</option>
                            <option value="Cultural Diversity and Traditions">Cultural Diversity and Traditions</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="techImpact">
                            <i class="bx bx-chip icon" style="color: #17a2b8;"></i> Technology & Society Impact
                        </label>
                        <select id="techImpact" name="techImpact" required>
                            <option value="AI, Automation, and Future Innovations">AI, Automation, and Future Innovations</option>
                            <option value="Space Exploration and Scientific Discoveries">Space Exploration and Scientific Discoveries</option>
                            <option value="Social Media and Digital Influence">Social Media and Digital Influence</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="societyView">
                            <i class="bx bx-group icon" style="color: #6610f2;"></i> Society Evaluation
                        </label>
                        <select id="societyView" name="societyView" required>
                            <option value="How We Govern & Make Decisions">How We Govern & Make Decisions</option>
                            <option value="How We Handle Conflict & Differences">How We Handle Conflict & Differences</option>
                            <option value="How We Adapt to Change & Innovation">How We Adapt to Change & Innovation</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3: Format & Creativity -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="format">
                            <i class="bx bx-pencil icon" style="color: #fd7e14;"></i> Submission Format
                        </label>
                        <select id="format" name="format" required>
                            <option value="Written Guide (3-5 Pages)">Written Guide (3-5 Pages)</option>
                            <option value="Video Presentation (3-4 Minutes)">Video Presentation (3-4 Minutes)</option>
                            <option value="Illustrated Story or Infographic">Illustrated Story or Infographic</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="creativity">
                            <i class="bx bx-brush icon" style="color: #e83e8c;"></i> Creativity Enhancement
                        </label>
                        <select id="creativity" name="creativity" required>
                            <option value="Use Humor & Satire">Use Humor & Satire</option>
                            <option value="Describe Earth from a Non-Human Perspective">Describe Earth from a Non-Human Perspective</option>
                            <option value="Create a Fictional Alien Interview">Create a Fictional Alien Interview</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: Evaluation Criteria -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="clarity">
                            <i class="bx bx-check-shield icon" style="color: #198754;"></i> Clarity & Accuracy (30%)
                        </label>
                        <input type="range" min="0" max="100" id="clarity" name="clarity">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="insight">
                            <i class="bx bx-brain icon" style="color: #007bff;"></i> Insight & Depth (40%)
                        </label>
                        <input type="range" min="0" max="100" id="insight" name="insight">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="creativityScore">
                            <i class="bx bx-palette icon" style="color: #6c757d;"></i> Creativity & Engagement (30%)
                        </label>
                        <input type="range" min="0" max="100" id="creativityScore" name="creativityScore">
                    </div>
                </div>

                <!-- Row 5: Deadline & Submission -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="deadline">
                            <i class="bx bx-calendar icon" style="color: #fd7e14;"></i> Submission Deadline
                        </label>
                        <input type="datetime-local" id="deadline" name="deadline" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="submission">
                            <i class="bx bx-upload icon" style="color: #6610f2;"></i> Upload Your Work
                        </label>
                        <input type="file" id="submission" name="submission" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-check-circle" style="color: #198754;"></i> Assign Task
                </button>
            </form>
        </div>
    </div>
</div>

 
    </div>

    <!-- Talk It Out Form -->
    <div id="talkItOutForm" class="hidden">
        <div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="talkItOut"><h4>✪ Talk It Out Assignment Creation</h4></label>
            <hr class="m-0" />
            <p><br>
            <form>
                <!-- Row 1: Basic Assignment Details -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="title">
                            <i class="bx bx-edit icon" style="color: #ff5733;"></i> Assignment Title
                        </label>
                        <select id="title" name="title" required>
                            <option value="Great Debates">Great Debates</option>
                            <option value="Defend Your Stance">Defend Your Stance</option>
                            <option value="Argue, Reason, Persuade">Argue, Reason, Persuade</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="description">
                            <i class="bx bx-book icon" style="color: #28a745;"></i> Assignment Description
                        </label>
                        <select id="description" name="description" required>
                            <option value="Engage in a structured debate and defend your stance.">Engage in a structured debate and defend your stance.</option>
                            <option value="Use logic, reasoning, and evidence to support your argument.">Use logic, reasoning, and evidence to support your argument.</option>
                            <option value="Analyze opposing viewpoints and formulate strong rebuttals.">Analyze opposing viewpoints and formulate strong rebuttals.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="objectives">
                            <i class="bx bx-target-lock icon" style="color: #dc3545;"></i> Learning Objectives
                        </label>
                        <select id="objectives" name="objectives" required>
                            <option value="Develop critical thinking and logical reasoning skills.">Develop critical thinking and logical reasoning skills.</option>
                            <option value="Enhance persuasive speaking and public speaking abilities.">Enhance persuasive speaking and public speaking abilities.</option>
                            <option value="Learn how to analyze and counter opposing arguments effectively.">Learn how to analyze and counter opposing arguments effectively.</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Debate Structure & Research Requirements -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="format">
                            <i class="bx bx-microphone icon" style="color: #007bff;"></i> Debate Format
                        </label>
                        <select id="format" name="format" required>
                            <option value="Individual Speech">Individual Speech</option>
                            <option value="Team Debate">Team Debate</option>
                            <option value="Panel Discussion">Panel Discussion</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stance">
                            <i class="bx bx-shuffle icon" style="color: #20c997;"></i> Stance Selection
                        </label>
                        <select id="stance" name="stance" required>
                            <option value="Pre-assigned Pro/Con">Pre-assigned Pro/Con</option>
                            <option value="Students choose their own stance">Students choose their own stance</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sources">
                            <i class="bx bx-book-open icon" style="color: #ffc107;"></i> Research & Evidence
                        </label>
                        <select id="sources" name="sources" required>
                            <option value="Minimum 3 credible sources required">Minimum 3 credible sources required</option>
                            <option value="Fact-based arguments only">Fact-based arguments only</option>
                            <option value="Logical, ethical, and emotional appeals encouraged">Logical, ethical, and emotional appeals encouraged</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3: Debate Rules & Speaking Time -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="speakingTime">
                            <i class="bx bx-timer icon" style="color: #6610f2;"></i> Speaking Time
                        </label>
                        <select id="speakingTime" name="speakingTime" required>
                            <option value="3 minutes per speaker">3 minutes per speaker</option>
                            <option value="2 rounds of rebuttals">2 rounds of rebuttals</option>
                            <option value="5-minute opening & 3-minute rebuttal">5-minute opening & 3-minute rebuttal</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="teamSize">
                            <i class="bx bx-user icon" style="color: #17a2b8;"></i> Team Size
                        </label>
                        <select id="teamSize" name="teamSize" required>
                            <option value="Individual">Individual</option>
                            <option value="Pairs">Pairs</option>
                            <option value="Small Groups (3-5)">Small Groups (3-5)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="speakingOrder">
                            <i class="bx bx-sort icon" style="color: #6c757d;"></i> Speaking Order
                        </label>
                        <select id="speakingOrder" name="speakingOrder" required>
                            <option value="Opening Statement, Arguments, Rebuttal, Conclusion">Opening Statement, Arguments, Rebuttal, Conclusion</option>
                            <option value="Flexible order based on debate format">Flexible order based on debate format</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: Submission & Evaluation Criteria -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="submissionFormat">
                            <i class="bx bx-upload icon" style="color: #6610f2;"></i> Submission Format
                        </label>
                        <select id="submissionFormat" name="submissionFormat" required>
                            <option value="Live Debate">Live Debate</option>
                            <option value="Recorded Video Submission">Recorded Video Submission</option>
                            <option value="Written Argument (Essay)">Written Argument (Essay)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="deadline">
                            <i class="bx bx-calendar icon" style="color: #fd7e14;"></i> Submission Deadline
                        </label>
                        <input type="datetime-local" id="deadline" name="deadline" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="criteria">
                            <i class="bx bx-trophy icon" style="color: #e83e8c;"></i> Evaluation Criteria
                        </label>
                        <select id="criteria" name="criteria" required>
                            <option value="Clarity (20%), Logic (20%), Persuasion (20%), Evidence (20%), Counterarguments (20%)">
                                Clarity (20%), Logic (20%), Persuasion (20%), Evidence (20%), Counterarguments (20%)
                            </option>
                            <option value="Confidence (25%), Structure (25%), Use of Evidence (25%), Argument Strength (25%)">
                                Confidence (25%), Structure (25%), Use of Evidence (25%), Argument Strength (25%)
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Row 5: Reflection & Feedback -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="reflection">
                            <i class="bx bx-reflect-horizontal icon" style="color: #28a745;"></i> Reflection & Learning
                        </label>
                        <select id="reflection" name="reflection" required>
                            <option value="Write a short reflection on what you learned.">Write a short reflection on what you learned.</option>
                            <option value="Did your perspective change after the debate? Explain.">Did your perspective change after the debate? Explain.</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-check-circle" style="color: #198754;"></i> Create Assignment
                </button>
            </form>
        </div>
    </div>
</div>

    </div>

    <!-- Elevator Pitch Form -->
    <div id="elevatorPitchForm" class="hidden">
       <div class="d-flex align-items-end row">
    <div class="col-sm-12">
        <div class="card-body">
            <label for="elevatorPitch"><h4>🚀  Elevator Pitch Assignment</h4></label>
            <hr class="m-0" />
            <p><br>
            <form>
                <!-- Row 1 -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="title">
                            <i class="bx bx-microphone icon" style="color: #ff5733;"></i> Pitch Scenario
                        </label>
                        <select id="title" name="title" required>
                            <option value="Pitching a Startup Idea">Pitching a Startup Idea</option>
                            <option value="Job Interview Introduction">Job Interview Introduction</option>
                            <option value="Networking at a Business Event">Networking at a Business Event</option>
                            <option value="Convincing an Investor in 60 Seconds">Convincing an Investor in 60 Seconds</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="description">
                            <i class="bx bx-chat icon" style="color: #28a745;"></i> Key Message Focus
                        </label>
                        <select id="description" name="description" required>
                            <option value="Who you are and what you offer">Who you are and what you offer</option>
                            <option value="What problem you solve and why it matters">What problem you solve and why it matters</option>
                            <option value="Why you are the right person for the opportunity">Why you are the right person for the opportunity</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="objectives">
                            <i class="bx bx-target-lock icon" style="color: #dc3545;"></i> Learning Objectives
                        </label>
                        <select id="objectives" name="objectives" required>
                            <option value="Develop clear and concise communication skills">Develop clear and concise communication skills</option>
                            <option value="Enhance persuasion and confidence in professional settings">Enhance persuasion and confidence in professional settings</option>
                            <option value="Master the art of making a strong first impression">Master the art of making a strong first impression</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="instructions">
                            <i class="bx bx-list-check icon" style="color: #17a2b8;"></i> Instructions
                        </label>
                        <select id="instructions" name="instructions" required>
                            <option value="Craft a 30-60 second pitch tailored to your chosen scenario.">Craft a 30-60 second pitch tailored to your chosen scenario.</option>
                            <option value="Use powerful language, avoiding filler words.">Use powerful language, avoiding filler words.</option>
                            <option value="Practice speaking with confidence and clarity.">Practice speaking with confidence and clarity.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="difficulty">
                            <i class="bx bx-bar-chart icon" style="color: #6c757d;"></i> Difficulty Level
                        </label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>
                            <i class="bx bx-upload icon" style="color: #6610f2;"></i> Submission Format
                        </label>
                        <select name="submission_format" required>
                            <option value="Video Recording">Video Recording (Preferred)</option>
                            <option value="Audio Recording">Audio Recording</option>
                            <option value="Text-based Script">Text-based Script</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="deadline">
                            <i class="bx bx-calendar icon" style="color: #fd7e14;"></i> Submission Deadline
                        </label>
                        <input type="datetime-local" id="deadline" name="deadline" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="criteria">
                            <i class="bx bx-trophy icon" style="color: #e83e8c;"></i> Evaluation Criteria
                        </label>
                        <select id="criteria" name="criteria" required>
                            <option value="Clarity (30%), Persuasiveness (40%), Confidence (30%)">Clarity (30%), Persuasiveness (40%), Confidence (30%)</option>
                            <option value="Engagement (35%), Structure (35%), Impact (30%)">Engagement (35%), Structure (35%), Impact (30%)</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-check-circle" style="color: #198754;"></i> Create Assignment
                </button>
            </form>
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




	  
		  
		  	
	

   

   

    

   
	  
 </div> 
</div>
</div>
 <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   