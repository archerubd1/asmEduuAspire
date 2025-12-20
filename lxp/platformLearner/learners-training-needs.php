<?php
/**
 *  Astraal - Learner Training Needs
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

$page = "learningPath";
require_once('learnerHead_Nav2.php');
?>

<!-- Layout container -->
<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>

  <!-- Content wrapper -->
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="row">

<?php
// Success message
if (isset($_REQUEST['msg'])) {
    $successMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
      document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
          title: "Successful!",
          text: "' . $successMessage . '",
          icon: "success",
          confirmButtonText: "OK"
        });
        history.replaceState({}, document.title, window.location.pathname);
      });
    </script>';
}

// Error message
if (isset($_REQUEST['error'])) {
    $errorMessage = base64_decode(urldecode($_GET['error']));
    echo '<script>
      document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
          title: "Invalid Registration!",
          text: "' . $errorMessage . '",
          icon: "error",
          confirmButtonText: "OK"
        });
        history.replaceState({}, document.title, window.location.pathname);
      });
    </script>';
}
?>

<div class="col-lg-12 mb-4 order-0">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="d-flex justify-content-end">
    <ol class="breadcrumb breadcrumb-style1">
      <li class="breadcrumb-item"><a href="learning-path.php">Learning Path</a></li>
      <li class="breadcrumb-item active">Training Needs</li>
    </ol>
  </nav>

  <!-- Accordion -->
  <div class="accordion mt-3" id="accordionExample">
    <div class="accordion-item">
      <h4 class="accordion-header" id="heading3">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
          data-bs-target="#accordion1" aria-expanded="true" aria-controls="accordion1">
          <i class="bx bx-target-lock"></i>&nbsp;&nbsp; Set & Define Your Training Needs
        </button>
      </h4>

      <div id="accordion1" class="accordion-collapse collapse show" aria-labelledby="heading3">
        <div class="accordion-body">
          <div class="d-flex align-items-end row">
            <div class="col-sm-12">
              <div class="card-body">
                <div class="container mt-4">

<form>
  <h4><i class="bx bx-search-alt"></i> Identify Your Current Level</h4>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">
        <i class="bx bx-book-open text-primary"></i> A. What do I already know?
      </label>
      <select class="form-select">
        <option>Beginner – No prior experience</option>
        <option>Basic – Familiar with fundamentals</option>
        <option>Intermediate – Can apply with guidance</option>
        <option>Advanced – Can work independently</option>
        <option>Expert – Can teach others</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">
        <i class="bx bx-brain text-success"></i> B. Skills I Already Have
      </label>
      <select class="form-select">
        <option>Programming (Python, Java, C++, etc.)</option>
        <option>Data Analysis</option>
        <option>Machine Learning</option>
        <option>Cloud Computing</option>
        <option>Cybersecurity</option>
        <option>Business Intelligence</option>
        <option>Web Development</option>
        <option>UI/UX Design</option>
        <option>Blockchain</option>
        <option>Soft Skills (Leadership, Communication)</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">
        <i class="bx bx-confused text-danger"></i> C. What do I struggle with?
      </label>
      <select class="form-select">
        <option>Understanding theoretical concepts</option>
        <option>Applying concepts to real-world problems</option>
        <option>Writing efficient code</option>
        <option>Debugging errors</option>
        <option>Data visualization & storytelling</option>
        <option>Collaborating in a team</option>
        <option>Managing projects & deadlines</option>
        <option>Public speaking & presentation skills</option>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <label class="form-label">
        <i class="bx bx-book-reader text-warning"></i> D. What do I need to learn?
      </label>
      <textarea class="form-control" placeholder="Enter key topics or skills" rows="3"></textarea>
    </div>
  </div>

  <div class="dropdown-divider"></div>
  <h4><i class="bx bx-target-lock text-success"></i> Define Clear Learning Goals (SMART Goals)</h4>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label"><i class="bx bx-code-alt text-primary"></i> Specific Skill to Develop</label>
      <select class="form-select">
        <option>Programming (Specify below)</option>
        <option>Data Science</option>
        <option>AI & Machine Learning</option>
        <option>Web Development</option>
        <option>Cloud Computing</option>
        <option>Blockchain</option>
        <option>Soft Skills (Negotiation, Leadership)</option>
      </select>
      <input type="text" class="form-control mt-2" placeholder="Specify language if applicable">
    </div>

    <div class="col-md-4">
      <label class="form-label"><i class="bx bx-bar-chart-alt text-success"></i> Measurable Progress</label>
      <select class="form-select">
        <option>Complete certification/course</option>
        <option>Build a project</option>
        <option>Pass assessment/exam</option>
        <option>Present to a group</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label"><i class="bx bx-time text-warning"></i> Achievable (Time Commitment)</label>
      <select class="form-select">
        <option>Daily (1-2 hrs)</option>
        <option>Weekly (4-6 hrs)</option>
        <option>Monthly (10+ hrs)</option>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label class="form-label"><i class="bx bx-briefcase text-info"></i> Relevance to Career Goals</label>
      <select class="form-select">
        <option>Job Promotion</option>
        <option>Career Switch</option>
        <option>Freelancing/Consulting</option>
        <option>Personal Development</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label"><i class="bx bx-calendar text-danger"></i> Time-Bound Target</label>
      <input type="date" class="form-control">
    </div>
  </div>

  <div class="dropdown-divider"></div>
  <h4><i class="bx bx-book-reader text-primary"></i> Identify Preferred Learning Methods</h4>

  <div class="row mb-3">
    <div class="col-md-6">
      <label class="form-label"><i class="bx bx-brain text-primary"></i> How do you learn best?</label>
      <select class="form-select">
        <option>Hands-on projects</option>
        <option>Visual learning (Videos, diagrams)</option>
        <option>Theory & reading</option>
        <option>Group discussions</option>
        <option>Mentorship (1-on-1 coaching)</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label"><i class="bx bx-book text-success"></i> Preferred Learning Resources</label>
      <select class="form-select">
        <option>Online Courses</option>
        <option>Books & eBooks</option>
        <option>YouTube Tutorials</option>
        <option>Podcasts & Webinars</option>
        <option>Coding Platforms</option>
      </select>
    </div>
  </div>

  <div class="dropdown-divider"></div>
  <h4><i class="bx bx-map text-success"></i> Request a Structured Learning Path</h4>

  <div class="row mb-3">
    <div class="col-md-6">
      <label class="form-label"><i class="bx bx-map text-primary"></i> Learning Roadmap Components</label>
      <select class="form-select">
        <option>Core Concepts</option>
        <option>Practical Applications</option>
        <option>Assessments</option>
        <option>Milestones & Feedback</option>
        <option>Capstone Project</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label"><i class="bx bx-layout text-success"></i> Preferred Learning Structure</label>
      <select class="form-select">
        <option>Self-paced</option>
        <option>Instructor-led</option>
        <option>Blended</option>
      </select>
    </div>
  </div>

  <div class="dropdown-divider"></div>
  <h4><i class="bx bx-support text-primary"></i> Set Expectations for Instructor Support</h4>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label"><i class="bx bx-support text-primary"></i> Support Method</label>
      <select class="form-select">
        <option>Written feedback</option>
        <option>1-on-1 mentoring</option>
        <option>Group Q&A</option>
        <option>Live code reviews</option>
        <option>Study materials</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label"><i class="bx bx-calendar-check text-success"></i> Frequency</label>
      <select class="form-select">
        <option>Weekly</option>
        <option>Bi-weekly</option>
        <option>Monthly</option>
        <option>On-demand</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label"><i class="bx bx-comment-edit text-warning"></i> Additional Requests</label>
      <textarea class="form-control" rows="1" placeholder="Any specific expectations"></textarea>
    </div>
  </div>

  <div class="dropdown-divider"></div>
  <h4><i class="bx bx-message-square-edit text-danger"></i> Final Request to Instructor</h4>

  <div class="row mb-3">
    <div class="col-md-12">
      <textarea class="form-control" rows="4" placeholder="Example: I want to strengthen my Python visualization skills using real-world datasets. Please create a roadmap with milestones and feedback checkpoints."></textarea>
    </div>
  </div>

  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary">
      <i class="bx bx-send"></i> Submit
    </button>
  </div>
</form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- End accordion -->
    </div>
  </div>
</div>
</div>
</div>

<?php require_once('../platformFooter.php'); ?>
