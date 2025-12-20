<?php
/**
 * Astraal LXP — ct_submission_detail.php (PHP 5.4 Compatible + Domain Explanations & Importance)
 * Learner detailed submission view with AI feedback, instructor notes, domain explanations, and importance notes.
 * Path: /lxp/platformLearner/ct_submission_detail.php
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) { 
  header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired."))); 
  exit; 
}

$submission_id = isset($_GET['submission_id']) ? (int)$_GET['submission_id'] : 0;
if ($submission_id <= 0) die("Invalid submission ID.");

$page = "criticalThinking";
require_once('learnerHead_Nav2.php');

$sql = "SELECT s.*, u.name, u.surname, u.login, u.email, a.title AS assignment_title 
        FROM ct_submissions s 
        INNER JOIN users u ON u.id = s.user_id 
        LEFT JOIN ct_subassignments sa ON sa.id = s.subassignment_id 
        LEFT JOIN ct_assignments a ON a.id = sa.assignment_id 
        WHERE s.id = ? LIMIT 1";
$stmt = $coni->prepare($sql);
$stmt->bind_param('i', $submission_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) die("Submission not found.");
$S = $res->fetch_assoc();
$stmt->close();

$review_q = $coni->query("SELECT comments, instructor_score FROM ct_instructor_reviews WHERE submission_id = " . (int)$submission_id);
$review = $review_q ? $review_q->fetch_assoc() : array();
$instructor_comments = isset($review['comments']) ? $review['comments'] : null;
$instructor_score = isset($review['instructor_score']) ? $review['instructor_score'] : $S['final_score'];

$domain_scores = array();
$q = $coni->query("SELECT domain_key, label, score FROM ct_domain_scores WHERE submission_id = " . (int)$submission_id);
if ($q) {
  while ($r = $q->fetch_assoc()) $domain_scores[] = $r;
}

$domain_definitions = array(
  'clarity'    => array('🧠 Clarity', 'How clearly and logically ideas are presented.', 'Clear communication ensures that reasoning is understood without ambiguity.'),
  'evidence'   => array('🔍 Evidence', 'How effectively claims are supported by data or facts.', 'Evidence strengthens arguments and helps validate conclusions.'),
  'reasoning'  => array('⚖️ Reasoning', 'How well arguments follow logical structure.', 'Strong reasoning prevents fallacies and ensures sound conclusions.'),
  'creativity' => array('💡 Creativity', 'Originality and innovative thinking in approach.', 'Creative thinking fosters new perspectives and deeper insight.'),
  'relevance'  => array('🎯 Relevance', 'Focus and pertinence to the given topic.', 'Relevance maintains logical flow and task alignment.'),
  'reflection' => array('🔄 Reflection', 'Ability to critique and refine one’s own thinking.', 'Reflection leads to continuous cognitive improvement.')
);

$ai_feedback_text = '';
$ai_q = $coni->query("SELECT feedback_json FROM ct_ai_feedback WHERE submission_id = " . (int)$submission_id . " LIMIT 1");
if ($ai_q && $ai_q->num_rows > 0) {
  $ai_data = $ai_q->fetch_assoc();
  $decoded = json_decode($ai_data['feedback_json'], true);
  if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
    if (isset($decoded['summary'])) $ai_feedback_text .= '<p>' . htmlspecialchars($decoded['summary']) . '</p>';
    if (isset($decoded['tips']) && is_array($decoded['tips'])) {
      $ai_feedback_text .= "<ul class='text-muted small'>";
      foreach ($decoded['tips'] as $tip) $ai_feedback_text .= '<li>' . htmlspecialchars($tip) . '</li>';
      $ai_feedback_text .= '</ul>';
    }
  }
}
?>
<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h3 class="mb-0"><?php echo htmlspecialchars($S['assignment_title']); ?> — Submission Detail</h3>
            <p class="text-muted mb-0">Review your response, AI and instructor scores, and domain insights.</p>
          </div>
          <a href="ct_view_grades.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back
          </a>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <div class="mb-3">
            <h6 class="text-muted mb-1">Learner:</h6>
            <p class="mb-0"><strong><?php echo htmlspecialchars($S['name'] . ' ' . $S['surname']); ?></strong></p>
            <small class="text-muted">Login: <?php echo htmlspecialchars($S['login']); ?> | Email: <?php echo htmlspecialchars($S['email']); ?></small>
          </div>

          <div class="mb-3">
            <h6 class="text-muted mb-1">Response (<?php echo strtoupper(htmlspecialchars($S['level'])); ?>):</h6>
            <div class="bg-light border rounded p-3" style="white-space:pre-wrap;"><?php echo nl2br(htmlspecialchars($S['content_text'])); ?></div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <h6 class="text-muted mb-1">AI Auto Score:</h6>
              <span class="badge bg-primary fs-6"><?php echo number_format($S['auto_score'], 1); ?>%</span>
            </div>
            <div class="col-md-4 mb-3">
              <h6 class="text-muted mb-1">Final Score:</h6>
              <span class="badge bg-success fs-6"><?php echo number_format($S['final_score'], 1); ?>%</span>
            </div>
            <div class="col-md-4 mb-3">
              <h6 class="text-muted mb-1">Instructor Score:</h6>
              <span class="badge bg-info fs-6"><?php echo number_format($instructor_score, 1); ?>%</span>
            </div>
          </div>

          <h6 class="text-muted mt-4 mb-2">Domain Breakdown:</h6>
          <?php if (!empty($domain_scores)) { ?>
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Domain</th>
                <th>Score (%)</th>
                <th>Importance in Critical Thinking</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($domain_scores as $d) {
                $key = $d['domain_key'];
                $score = (float)$d['score'];
                $color = ($score >= 75) ? 'success' : (($score >= 50) ? 'warning' : 'danger');
                if (isset($domain_definitions[$key])) {
                  $title = $domain_definitions[$key][0];
                  $explanation = $domain_definitions[$key][1];
                  $importance = $domain_definitions[$key][2];
                } else {
                  $title = '📘 ' . htmlspecialchars($d['label']);
                  $explanation = 'No definition available.';
                  $importance = 'Not specified.';
                }
              ?>
              <tr>
                <td>
                  <strong><?php echo $title; ?></strong><br>
                  <small class="text-muted"><?php echo htmlspecialchars($explanation); ?></small>
                </td>
                <td><span class="badge bg-<?php echo $color; ?>"><?php echo $score; ?>%</span></td>
                <td class="small text-muted"><?php echo htmlspecialchars($importance); ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <?php } else { ?>
            <p class="text-muted">No domain scores available.</p>
          <?php } ?>

          <h6 class="text-muted mt-4 mb-2">AI Feedback Summary:</h6>
          <div class="bg-light border rounded p-3 small">
            <?php echo !empty($ai_feedback_text) ? $ai_feedback_text : '<span class="text-muted">No AI feedback available.</span>'; ?>
          </div>

          <h6 class="text-muted mt-4 mb-2">Instructor Comments:</h6>
          <div class="bg-light border rounded p-3 small">
            <?php echo !empty($instructor_comments) ? nl2br(htmlspecialchars($instructor_comments)) : '<span class="text-muted">No instructor feedback yet.</span>'; ?>
          </div>

          <div class="mt-4 text-end">
            <?php 
              $status_color = ($S['status'] === 'submitted') ? 'secondary' : 'success';
              echo '<span class="badge bg-' . $status_color . '">Status: ' . ucfirst($S['status']) . '</span>';
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>
