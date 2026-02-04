<?php
// File: learners/career-path-compliance.php
// Learner-focused Career Path & Compliance – Accordion + Workflow Cards

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$page = "careerPath";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>



<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- HEADER -->
<div class="card shadow-sm mb-4">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h4 class="mb-1">Career Path & Compliance Management</h4>
        <p class="text-muted mb-0">
          <strong>Understand & analyze</strong> your readiness, responsibility, and long-term growth
        </p>
      </div>

      <div class="text-end">
        <i class="bx bx-compass bx-lg text-primary"></i>
      </div>
    </div>

    <!-- CLOSING MESSAGE -->
    <div class="alert alert-info mt-3 mb-0">
      This module helps ensure that growth is intentional,
      responsibility is earned, and progression is safe — for you and the system.
    </div>

  </div>
</div>



<!-- PRIMARY ACCORDION : Career & Compliance Meaning -->
<div class="card shadow-sm mb-4">
  <div class="card-body p-0">

    <div class="accordion" id="careerComplianceAccordion">

      <!-- Accordion Item -->
      <div class="accordion-item border-0">
        <h2 class="accordion-header" id="headingCareerCompliance">
          <button class="accordion-button bg-label-primary collapsed fw-semibold"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseCareerCompliance"
                  aria-expanded="false"
                  aria-controls="collapseCareerCompliance">
            <i class="bx bx-info-circle text-primary me-2"></i>
            Why Career Path & Compliance exists
          </button>
        </h2>

        <div id="collapseCareerCompliance"
             class="accordion-collapse collapse"
             aria-labelledby="headingCareerCompliance"
             data-bs-parent="#careerComplianceAccordion">

          <div class="accordion-body">

            <!-- WHY -->
            <h6 class="mb-2 mt-2">
              <i class="bx bx-question-mark text-primary me-1"></i>
              Why this module exists
            </h6>

            <p>
              Your career is not just about moving up.
              It is about becoming someone who can
              <strong>handle greater responsibility safely, ethically, and sustainably</strong>.
            </p>

            <p class="mb-2">
              This module exists to continuously answer two critical questions:
            </p>

            <ul>
              <li><strong>Am I ready for what I am becoming?</strong></li>
              <li><strong>Is the system safe if I step into that responsibility?</strong></li>
            </ul>

            <hr>

            <!-- DEFINITIONS -->
            <h6 class="mb-2">
              <i class="bx bx-book-open text-primary me-1"></i>
              How astraal LXP defines career and compliance
            </h6>

            <p><strong>Career in astraal LXP</strong> is not a title ladder.</p>
            <ul>
              <li>It is your trajectory of intelligence, judgment, responsibility, and impact</li>
              <li>It reflects how you think, decide, and apply learning in real situations</li>
            </ul>

            <p class="mt-3"><strong>Compliance in astraal LXP</strong> is not training completion.</p>
            <ul class="mb-0">
              <li>It is proof that you are ready to operate safely</li>
              <li>It reflects ethical grounding, decision maturity, and risk awareness</li>
            </ul>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<!-- ACCORDION : How astraal LXP supports the learner -->
<div class="card shadow-sm mb-4">
  <div class="card-body p-0">

    <div class="accordion" id="learnerSupportAccordion">

      <!-- Accordion Header -->
      <div class="accordion-item border-0">
        <h2 class="accordion-header" id="headingLearnerSupport">
          <button class="accordion-button bg-label-danger collapsed fw-semibold"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLearnerSupport"
                  aria-expanded="false"
                  aria-controls="collapseLearnerSupport">
            <i class="bx bx-support text-warning me-2"></i>
            How we support your career readiness
          </button>
        </h2>

        <!-- Accordion Body -->
        <div id="collapseLearnerSupport"
             class="accordion-collapse collapse"
             aria-labelledby="headingLearnerSupport"
             data-bs-parent="#learnerSupportAccordion">

          <div class="accordion-body">

            <!-- ITEM 1 -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2 mt-3">
                <i class="bx bx-user bx-sm text-primary me-2"></i>
                <strong>Understanding who you are as a learner</strong>
              </div>
              <p class="text-muted mb-0">
                The platform observes how you learn, what motivates you, how you respond to pressure,
                and how consistently you grow — building a living picture of you, not just a profile.
              </p>
            </div>

            <!-- ITEM 2 -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <i class="bx bx-dna bx-sm text-primary me-2"></i>
                <strong>Measuring what you are capable of — realistically</strong>
              </div>
              <p class="text-muted mb-0">
                Your readiness is evaluated based on what you can actually demonstrate,
                not just what you are certified in.
              </p>
            </div>

            <!-- ITEM 3 -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <i class="bx bx-brain bx-sm text-primary me-2"></i>
                <strong>Observing how you think and decide</strong>
              </div>
              <p class="text-muted mb-0">
                Through scenarios and simulations, astraal LXP evaluates how you handle ambiguity,
                risk, bias, and real-world trade-offs.
              </p>
            </div>

            <!-- ITEM 4 -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <i class="bx bx-buildings bx-sm text-primary me-2"></i>
                <strong>Requiring proof of application</strong>
              </div>
              <p class="text-muted mb-0">
                Career progression requires evidence that learning is turning into value —
                not just completion or attendance.
              </p>
            </div>

            <!-- ITEM 5 -->
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <i class="bx bx-shield-quarter bx-sm text-primary me-2"></i>
                <strong>Protecting you and the system</strong>
              </div>
              <p class="text-muted mb-0">
                When stress, fatigue, ethical strain, or role misfit is detected,
                the system slows progression and triggers support instead of punishment.
              </p>
            </div>

            <hr class="my-4">

            <!-- WHAT THIS MEANS -->
            <div>
              <h6 class="mb-2">
                <i class="bx bx-check-circle text-primary me-1"></i>
                What this means for you
              </h6>
              <ul class="mb-0">
                <li>You are not pushed into roles you are not ready for</li>
                <li>Your growth is guided, not forced</li>
                <li>Compliance supports your safety and credibility, not just audits</li>
                <li>Your career evolves with awareness, not burnout</li>
              </ul>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>




<!-- WORKFLOW CARDS -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h6 class="mb-3">Your Career Path & Compliance Workflows</h6>

    <div class="row g-4">

<?php
$workflows = array(
  array(
    'key'=>'career',
    'title'=>'My Career Trajectory',
    'icon'=>'bx-trending-up',
    'desc'=>'Understand where you are heading and why.',
    'view'=>'Shows your current trajectory, pacing, and direction.',
    'evaluate'=>'Learning velocity, decision maturity, applied impact.',
    'how'=>'Observed learning signals and applied outcomes.',
    'preview'=>'Current trajectory: Growth-focused | Pace: Moderate | Risk: Low'
  ),
  array(
    'key'=>'compliance',
    'title'=>'My Compliance Readiness',
    'icon'=>'bx-shield-quarter',
    'desc'=>'Check readiness for regulated responsibility.',
    'view'=>'Explains your compliance readiness status.',
    'evaluate'=>'Behavioral signals, scenario responses, ethical flags.',
    'how'=>'Scenario simulations and historical patterns.',
    'preview'=>'Readiness: Provisional | Expiry risk: None'
  ),
  array(
    'key'=>'decision',
    'title'=>'Decision & Risk Readiness',
    'icon'=>'bx-brain',
    'desc'=>'See how decisions align with role expectations.',
    'view'=>'Evaluates how you make decisions under pressure.',
    'evaluate'=>'Bias patterns, ambiguity handling, risk appetite.',
    'how'=>'Scenario-based assessments.',
    'preview'=>'Bias risk: Low | Risk appetite: Balanced'
  ),
  array(
    'key'=>'sustain',
    'title'=>'Sustainability & Load',
    'icon'=>'bx-pulse',
    'desc'=>'Monitor stress, resilience, and sustainability.',
    'view'=>'Shows long-term viability indicators.',
    'evaluate'=>'Stress signals, engagement consistency.',
    'how'=>'Behavioral and cadence signals.',
    'preview'=>'Sustainability: Stable | Burnout risk: Low'
  ),
  array(
    'key'=>'capability',
    'title'=>'Capability Readiness Map',
    'icon'=>'bx-dna',
    'desc'=>'What you can do vs what is expected.',
    'view'=>'Maps certified vs demonstrated capability.',
    'evaluate'=>'Capability gaps and strengths.',
    'how'=>'Evidence-backed capability signals.',
    'preview'=>'Certified: High | Demonstrated: Medium'
  ),
  array(
    'key'=>'ethics',
    'title'=>'Ethical & Conduct Readiness',
    'icon'=>'bx-check-shield',
    'desc'=>'Ethical grounding and conduct signals.',
    'view'=>'Explains ethical readiness for responsibility.',
    'evaluate'=>'Judgment consistency, ethical drift.',
    'how'=>'Scenario choices and reflection loops.',
    'preview'=>'Ethical stability: High'
  ),
  array(
    'key'=>'transition',
    'title'=>'Role Transition Readiness',
    'icon'=>'bx-transfer',
    'desc'=>'Are you ready for the next role?',
    'view'=>'Evaluates readiness for role change.',
    'evaluate'=>'Skill, load, risk alignment.',
    'how'=>'Multi-signal readiness checks.',
    'preview'=>'Transition risk: Medium'
  ),
  array(
    'key'=>'impact',
    'title'=>'Learning → Impact Evidence',
    'icon'=>'bx-buildings',
    'desc'=>'Proof that learning creates value.',
    'view'=>'Shows applied learning outcomes.',
    'evaluate'=>'Impact artefacts and results.',
    'how'=>'Linked learning-to-impact evidence.',
    'preview'=>'Impact artefacts: 3 validated'
  ),
);
?>

<?php foreach ($workflows as $w): ?>
  <div class="col-xl-3 col-lg-4 col-md-6">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">

        <div class="d-flex align-items-center mb-2">
          <i class="bx <?php echo $w['icon']; ?> bx-md text-primary me-2"></i>
          <h6 class="mb-0"><?php echo $w['title']; ?></h6>
        </div>

        <p class="text-muted small flex-grow-1"><?php echo $w['desc']; ?></p>

        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#view_<?php echo $w['key']; ?>">View</button>

            <button class="btn btn-outline-secondary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#preview_<?php echo $w['key']; ?>">Preview</button>
          </div>

          <button class="btn btn-success btn-sm"
            onclick="analyze('<?php echo $w['title']; ?>')">
            Analyze
          </button>
        </div>

      </div>
    </div>
  </div>
<!-- VIEW MODAL -->
<div class="modal fade" id="view_<?php echo $w['key']; ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <div class="d-flex align-items-center">
          <i class="bx <?php echo $w['icon']; ?> bx-sm text-primary me-2"></i>
          <h5 class="modal-title mb-0"><?php echo $w['title']; ?></h5>
        </div>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p>
          <i class="bx bx-info-circle text-primary me-1"></i>
          <strong>What it is</strong><br>
          <?php echo $w['view']; ?>
        </p>

        <p>
          <i class="bx bx-check-circle text-primary me-1"></i>
          <strong>What we evaluate</strong><br>
          <?php echo $w['evaluate']; ?>
        </p>

        <p class="mb-0">
          <i class="bx bx-cog text-primary me-1"></i>
          <strong>How we evaluate</strong><br>
          <?php echo $w['how']; ?>
        </p>
      </div>

    </div>
  </div>
</div>

<!-- PREVIEW MODAL -->
<div class="modal fade" id="preview_<?php echo $w['key']; ?>" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <div class="d-flex align-items-center">
          <i class="bx <?php echo $w['icon']; ?> bx-sm text-primary me-2"></i>
          <h5 class="modal-title mb-0"><?php echo $w['title']; ?> – Preview</h5>
        </div>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="text-muted mb-2">
          <i class="bx bx-show me-1"></i>
          Sample preview (static)
        </p>

        <div class="alert alert-light mb-0">
          <?php echo $w['preview']; ?>
        </div>
      </div>

    </div>
  </div>
</div>


<?php endforeach; ?>

    </div>
  </div>
</div>


<script>
function analyze(title) {
  Swal.fire({
    icon: 'info',
    title: 'Analysis Initiated',
    text: title + ' analysis will be available once sufficient signals are collected.',
    confirmButtonText: 'Understood'
  });
}
</script>










</div>
</div>

<?php require_once('../platformFooter.php'); ?>
