<?php
/**
 * Astraal LXP - Instructor Adaptive learning Paths
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
          
		 
          
          <!-- Content wrapper -->
<div class="content-wrapper">
            <!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-pills mb-0" role="tablist">
          <!-- Course Content Tab -->
          <li class="nav-item" style="margin-right: 6px;">
            <a href="#tab-course-content" class="nav-link active" data-bs-toggle="pill" role="tab" aria-selected="true">
               <i class="menu-icon bx bx-plus-circle"></i> Course Content
            </a>
          </li>
          <!-- Course Library Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-course-library" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="menu-icon bx bx-library"></i> Course Library
            </a>
          </li>
          <!-- Bulk Upload Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-bulk-upload" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
               <i class="menu-icon bx bx-upload"></i> Bulk Upload
            </a>
          </li>
          <!-- Content Repository Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-content-repository" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
               <i class="menu-icon bx bx-folder"></i> Content Repository
            </a>
          </li>
          <!-- AI Content Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-ai-content" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="menu-icon bx bx-brain"></i> AI-Powered Content
            </a>
          </li>
        </ul>
      </div>
      
      <div class="card-body">
        <div class="tab-content">
          
          <!-- Course Content Tab -->
          <div class="tab-pane fade show active" id="tab-course-content" role="tabpanel">
            <h5 class="card-title text-primary">Create & Manage Course Content</h5>
            <p>Instructors can create, edit, and manage course materials, including:</p>
            <ul>
              <li>Adding new course modules</li>
              <li>Editing and updating course lessons</li>
              <li>Embedding multimedia (videos, PDFs, quizzes)</li>
              <li>Organizing content into structured topics</li>
            </ul>
            <a href="create-content.php" class="btn btn-sm btn-outline-primary">Manage Course Content</a>
          </div>
          
          <!-- Course Library Tab -->
          <div class="tab-pane fade" id="tab-course-library" role="tabpanel">
            <h5 class="card-title text-success">Access & Organize Course Library</h5>
            <p>The course library contains all the instructional materials instructors have created or imported.</p>
            <ul>
              <li>Browse and search existing courses</li>
              <li>Reuse and repurpose course materials</li>
              <li>Organize content by subject or category</li>
              <li>Maintain a structured repository of resources</li>
            </ul>
            <a href="course-library.php" class="btn btn-sm btn-outline-success">Go to Course Library</a>
          </div>
          
          <!-- Bulk Upload Tab -->
          <div class="tab-pane fade" id="tab-bulk-upload" role="tabpanel">
            <h5 class="card-title text-info">Bulk Upload Course Materials</h5>
            <p>Instructors can efficiently upload multiple course materials at once:</p>
            <ul>
              <li>Upload PDFs, videos, images, and documents</li>
              <li>Map bulk uploads to course modules</li>
              <li>Ensure compliance with content formats</li>
              <li>Manage large-scale content updates</li>
            </ul>
            <a href="bulk-upload.php" class="btn btn-sm btn-outline-info">Start Bulk Upload</a>
          </div>
          
          <!-- Content Repository Tab -->
          <div class="tab-pane fade" id="tab-content-repository" role="tabpanel">
            <h5 class="card-title text-warning">Content Repository & Version Control</h5>
            <p>Manage and organize instructional content in a structured repository:</p>
            <ul>
              <li>Store, retrieve, and manage course assets</li>
              <li>Track different versions of course content</li>
              <li>Maintain backups and prevent data loss</li>
              <li>Ensure seamless content updates</li>
            </ul>
            <a href="content-repository.php" class="btn btn-sm btn-outline-warning">Access Repository</a>
          </div>
          
          <!-- AI-Powered Content Tab -->
          <div class="tab-pane fade" id="tab-ai-content" role="tabpanel">
            <h5 class="card-title text-info">AI-Powered Content Recommendations</h5>
            <p>Utilize AI-driven tools to enhance learning experiences:</p>
            <ul>
              <li>AI-based content recommendations for students</li>
              <li>Adaptive learning paths based on student progress</li>
              <li>Automated content tagging and categorization</li>
              <li>Personalized course suggestions</li>
            </ul>
            <a href="ai-content-recommendations.php" class="btn btn-sm btn-outline-info">Explore AI Features</a>
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
          <!-- Content Approvals Tab -->
          <li class="nav-item" style="margin-right: 6px;">
            <a href="#tab-content-approvals" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="true">
              <i class="menu-icon bx bx-check-circle"></i> Content Approvals
            </a>
          </li>
          <!-- Version Control Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-version-control" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="menu-icon bx bx-git-branch"></i> Version Control
            </a>
          </li>
          <!-- Quizzes & Tests Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-quizzes-tests" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="menu-icon bx bx-task"></i> Quizzes & Tests
            </a>
          </li>
          <!-- Skills Assessment Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-skills-assessment" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="menu-icon bx bx-poll"></i> Skills Assessment
            </a>
          </li>
          <!-- Badges & Certificates Tab -->
          <li class="nav-item" style="margin-right: 8px;">
            <a href="#tab-badges-certificates" class="nav-link" data-bs-toggle="pill" role="tab" aria-selected="false">
              <i class="menu-icon bx bxs-certification"></i> Badges & Certificates
            </a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          
          <!-- Content Approvals Tab Content -->
          <div class="tab-pane fade" id="tab-content-approvals" role="tabpanel">
            <h5 class="card-title text-primary">Manage Content Approvals</h5>
            <p>Instructors can review and approve submitted course materials before they go live.</p>
            <ul>
              <li>Verify course accuracy and completeness</li>
              <li>Approve or reject submitted content</li>
              <li>Provide feedback and request revisions</li>
              <li>Ensure compliance with institutional guidelines</li>
            </ul>
            <a href="content-approvals.php" class="btn btn-sm btn-outline-primary">Review Content</a>
          </div>

          <!-- Version Control Tab Content -->
          <div class="tab-pane fade" id="tab-version-control" role="tabpanel">
            <h5 class="card-title text-success">Manage Version Control</h5>
            <p>Track and manage changes in course content to ensure updates are versioned properly.</p>
            <ul>
              <li>Maintain a history of content revisions</li>
              <li>Restore previous versions if needed</li>
              <li>Track who made changes and when</li>
              <li>Ensure consistency across course materials</li>
            </ul>
            <a href="version-control.php" class="btn btn-sm btn-outline-success">View Version History</a>
          </div>

          <!-- Quizzes & Tests Tab Content -->
          <div class="tab-pane fade" id="tab-quizzes-tests" role="tabpanel">
            <h5 class="card-title text-info">Create & Manage Quizzes & Tests</h5>
            <p>Design assessments to evaluate student understanding and progress.</p>
            <ul>
              <li>Create multiple-choice, fill-in-the-blank, and essay-based tests</li>
              <li>Set time limits and grading policies</li>
              <li>Automate grading and feedback</li>
              <li>Analyze student performance through reports</li>
            </ul>
            <a href="quizzes-tests.php" class="btn btn-sm btn-outline-info">Build a Quiz</a>
          </div>

          <!-- Skills Assessment Tab Content -->
          <div class="tab-pane fade" id="tab-skills-assessment" role="tabpanel">
            <h5 class="card-title text-warning">Conduct Skills Assessments</h5>
            <p>Assess student competencies and proficiency levels in various subjects.</p>
            <ul>
              <li>Develop structured competency-based assessments</li>
              <li>Evaluate soft and technical skills</li>
              <li>Use rubrics and criteria-based evaluations</li>
              <li>Provide actionable feedback for improvement</li>
            </ul>
            <a href="#skills-assessment.php" class="btn btn-sm btn-outline-warning">Start Assessment</a>
          </div>

          <!-- Badges & Certificates Tab Content -->
          <div class="tab-pane fade" id="tab-badges-certificates" role="tabpanel">
            <h5 class="card-title text-info">Award Badges & Certificates</h5>
            <p>Recognize student achievements through digital badges and certifications.</p>
            <ul>
              <li>Issue completion and achievement certificates</li>
              <li>Assign digital badges for skill mastery</li>
              <li>Track student progress and credentials</li>
              <li>Integrate with learning pathways and gamification</li>
            </ul>
            <a href="#badges-certificates.php" class="btn btn-sm btn-outline-info">Manage Certifications</a>
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
   