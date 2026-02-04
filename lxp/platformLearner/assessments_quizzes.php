<?php
// File: learners/assessments.php
// Assessment Catalogue – UI aligned with Problem-Solving Catalogue

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$page = "assessments";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- HEADER -->
<div class="card shadow-sm mb-4">
  <div class="card-body d-flex justify-content-between align-items-center">
    <div>
      <h4 class="mb-1">Assessment Catalogue</h4>
      <p class="text-muted mb-0">
        Click <strong>View</strong> to understand the assessment.
        Click <strong>Preview</strong> to see sample questions.
        Click <strong>Attempt Now</strong> to begin.
      </p>
    </div>
    <i class="fa fa-clipboard-check fa-2x text-primary"></i>
  </div>
</div>

<?php
$assessments = array(
  array('key'=>'adaptive','title'=>'Adaptive Assessment','icon'=>'fa-brain',
        'meaning'=>'Difficulty dynamically adjusts based on learner responses.',
        'skills'=>'Learning agility, problem-solving, cognitive flexibility',
        'preview'=>'Questions change in complexity based on your answers.',
        'gain'=>'Personalized challenge without frustration'),

  array('key'=>'multimodal','title'=>'Multimodal Assessment','icon'=>'fa-layer-group',
        'meaning'=>'Uses text, audio, video, and interaction formats.',
        'skills'=>'Comprehension, interpretation, media literacy',
        'preview'=>'Mixed-format questions including visuals and audio.',
        'gain'=>'Engagement across learning styles'),

  array('key'=>'diagnostic','title'=>'Diagnostic Assessment','icon'=>'fa-stethoscope',
        'meaning'=>'Identifies strengths and learning gaps.',
        'skills'=>'Baseline knowledge, conceptual clarity',
        'preview'=>'Targeted questions to assess readiness.',
        'gain'=>'Clear learning direction'),

  array('key'=>'formative','title'=>'Formative Assessment','icon'=>'fa-chart-line',
        'meaning'=>'Provides feedback during learning.',
        'skills'=>'Progress tracking, self-correction',
        'preview'=>'Short checkpoints with instant feedback.',
        'gain'=>'Continuous improvement'),

  array('key'=>'summative','title'=>'Summative Assessment','icon'=>'fa-award',
        'meaning'=>'Evaluates mastery at the end of learning.',
        'skills'=>'Retention, synthesis, mastery',
        'preview'=>'Comprehensive end-of-unit questions.',
        'gain'=>'Proof of achievement'),

  array('key'=>'project','title'=>'Project-Based Assessment','icon'=>'fa-project-diagram',
        'meaning'=>'Real-world tasks demonstrating applied skills.',
        'skills'=>'Application, planning, execution',
        'preview'=>'Multi-step project submissions.',
        'gain'=>'Practical competence'),

  array('key'=>'peer','title'=>'Peer to Peer Assessment','icon'=>'fa-users',
        'meaning'=>'Learners assess each other.',
        'skills'=>'Feedback, collaboration, evaluation',
        'preview'=>'Rubric-based peer reviews.',
        'gain'=>'Perspective and communication'),

  array('key'=>'simulation','title'=>'Simulation Assessment','icon'=>'fa-vr-cardboard',
        'meaning'=>'Scenario-driven simulations.',
        'skills'=>'Decision-making, adaptability',
        'preview'=>'Simulated environments with choices.',
        'gain'=>'Safe real-world practice'),

  array('key'=>'continuous','title'=>'Continuous Assessment','icon'=>'fa-sync-alt',
        'meaning'=>'Ongoing evaluation across time.',
        'skills'=>'Consistency, progress',
        'preview'=>'Distributed micro-assessments.',
        'gain'=>'Sustained growth'),

  array('key'=>'certification','title'=>'Certification Assessment','icon'=>'fa-certificate',
        'meaning'=>'Validates skills against standards.',
        'skills'=>'Compliance, mastery',
        'preview'=>'Standard-aligned exam questions.',
        'gain'=>'Recognized credentials'),

  array('key'=>'competency','title'=>'Competency-Based Assessment','icon'=>'fa-check-circle',
        'meaning'=>'Focuses on demonstrated skills.',
        'skills'=>'Skill mastery, outcomes',
        'preview'=>'Performance-driven tasks.',
        'gain'=>'Skill validation'),

  array('key'=>'performance','title'=>'Performance Assessment','icon'=>'fa-running',
        'meaning'=>'Evaluates execution quality.',
        'skills'=>'Accuracy, efficiency',
        'preview'=>'Task output evaluation.',
        'gain'=>'Execution excellence'),

  array('key'=>'softskills','title'=>'Personality & Soft Skills','icon'=>'fa-user-friends',
        'meaning'=>'Measures behavioral traits.',
        'skills'=>'Communication, teamwork',
        'preview'=>'Situational judgment questions.',
        'gain'=>'Workplace readiness'),

  array('key'=>'scenario','title'=>'Scenario-Based Assessment','icon'=>'fa-route',
        'meaning'=>'Decision-making in context.',
        'skills'=>'Critical thinking',
        'preview'=>'Multi-path scenarios.',
        'gain'=>'Better judgment'),

  array('key'=>'benchmark','title'=>'Benchmarking Assessment','icon'=>'fa-balance-scale',
        'meaning'=>'Compares performance to standards.',
        'skills'=>'Relative performance awareness',
        'preview'=>'Norm-referenced questions.',
        'gain'=>'Competitive insight'),

  array('key'=>'self','title'=>'Self Assessment & Insights','icon'=>'fa-user-check',
        'meaning'=>'Reflection-driven evaluation.',
        'skills'=>'Self-awareness',
        'preview'=>'Reflection-based prompts.',
        'gain'=>'Personal insight'),
);
?>

<div class="row g-4">
<?php foreach ($assessments as $a): ?>
  <div class="col-xl-3 col-lg-4 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">

        <div class="d-flex align-items-center mb-2">
          <i class="fa <?php echo $a['icon']; ?> fa-2x text-primary me-3"></i>
          <h6 class="mb-0"><?php echo $a['title']; ?></h6>
        </div>

        <p class="text-muted small flex-grow-1"><?php echo $a['meaning']; ?></p>

        <!-- CTA layout EXACT like Problem-Solving -->
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#view_<?php echo $a['key']; ?>">View</button>

            <button class="btn btn-outline-secondary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#preview_<?php echo $a['key']; ?>">Preview</button>
          </div>

          <a href="assessment_attempt.php?type=<?php echo $a['key']; ?>"
             class="btn btn-success btn-sm">
            Attempt Now
          </a>
        </div>
      </div>
    </div>
  </div>

<!-- VIEW MODAL -->
<div class="modal fade" id="view_<?php echo $a['key']; ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fa <?php echo $a['icon']; ?> text-primary me-2"></i>
        <h5 class="modal-title"><?php echo $a['title']; ?></h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>What this assessment means</strong><br><?php echo $a['meaning']; ?></p>
        <p><strong>Skills evaluated</strong><br><?php echo $a['skills']; ?></p>
      </div>
    </div>
  </div>
</div>

<!-- PREVIEW MODAL -->
<div class="modal fade" id="preview_<?php echo $a['key']; ?>" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fa <?php echo $a['icon']; ?> text-primary me-2"></i>
        <h5 class="modal-title"><?php echo $a['title']; ?> – Preview</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>What you will attempt</strong><br><?php echo $a['preview']; ?></p>
        <p><strong>What you gain</strong><br><?php echo $a['gain']; ?></p>
      </div>
    </div>
  </div>
</div>

<?php endforeach; ?>
</div>

<p><br></p>	
		
<div class="row g-4">
  <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">

        <div class="d-flex align-items-center mb-2">
          <i class="fa fa-sync fa-2x text-primary me-3"></i>
          <h6 class="mb-0">360-Degree Feedback</h6>
        </div>

        <p class="text-muted small flex-grow-1">Feedback from multiple perspectives</p>

        <!-- CTA layout EXACT like Problem-Solving -->
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#view_feedback360">View</button>

            <button class="btn btn-outline-secondary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#preview_feedback360">Preview</button>
          </div>

          <a href="assessment_attempt.php?type=feedback360"
             class="btn btn-success btn-sm">
            Attempt Now
          </a>
        </div>
      </div>
    </div>
  </div>
</div>




</div>
</div>

<?php require_once('../platformFooter.php'); ?>
