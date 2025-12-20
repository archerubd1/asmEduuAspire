<?php
/**
 * Astraal LXP — ct_view_grades.php 🧠 (PHP 5.4 Compatible)
 * Learner view: All Critical Thinking submissions with scores, feedback, and domain badges.
 * Path: /lxp/platformLearner/ct_view_grades.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
  header("Location: ../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
  exit;
}

$phx_user_id = (int)$_SESSION['phx_user_id'];
$page = "criticalThinking";
require_once('learnerHead_Nav2.php');

// Fetch submissions for learner
$sql = "
  SELECT 
    s.id, s.subassignment_id, s.attempt_no, s.level, s.auto_score, s.final_score, 
    s.created_at, s.status,
    COALESCE(ir.comments, '—') AS instructor_comments,
    COALESCE(ir.instructor_score, s.final_score) AS instructor_score,
    sa.assignment_id, a.title AS assignment_title
  FROM ct_submissions s
  LEFT JOIN ct_instructor_reviews ir ON ir.submission_id = s.id
  LEFT JOIN ct_subassignments sa ON sa.id = s.subassignment_id
  LEFT JOIN ct_assignments a ON a.id = sa.assignment_id
  WHERE s.user_id = ?
  ORDER BY s.created_at DESC
";

$stmt = $coni->prepare($sql);
$stmt->bind_param('i', $phx_user_id);
$stmt->execute();
$res = $stmt->get_result();

$submissions = array();
while ($row = $res->fetch_assoc()) {
  $submissions[] = $row;
}
$stmt->close();

$domain_icons = array(
  'clarity'    => '🧠',
  'evidence'   => '🔍',
  'reasoning'  => '⚖️',
  'creativity' => '💡',
  'relevance'  => '🎯',
  'reflection' => '🔄'
);
?>
<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h3 class="mb-1">My Critical Thinking Submissions</h3>
            <p class="text-muted mb-0">View your attempts, scores, and feedback.</p>
          </div>
          <a href="critical-thinking/critical-thinking.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back
          </a>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <?php if (empty($submissions)) { ?>
            <p class="text-center text-muted py-4">No submissions yet.</p>
          <?php } else { ?>
          <div class="table-responsive">
            <table id="ctGradesTable" class="table table-striped align-middle">
              <thead class="table-light">
                <tr class="text-center">
                  <th>ID</th>
                  <th>Assignment</th>
                  <th>Subassignment</th>
                  <th>Attempt</th>
                  <th>Level</th>
                  <th>AI Score</th>
                  <th>Final Score</th>
                
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($submissions as $s) { 
                  $dscores = array();
                  $q = $coni->query("SELECT domain_key, score FROM ct_domain_scores WHERE submission_id=".(int)$s['id']);
                  if ($q) {
                    while ($d = $q->fetch_assoc()) {
                      $score = (float)$d['score'];
                      $color = ($score >= 75) ? 'success' : (($score >= 50) ? 'warning' : 'danger');
                      $icon = isset($domain_icons[$d['domain_key']]) ? $domain_icons[$d['domain_key']] : '📘';
                      $dscores[] = "<span class='badge bg-".$color." me-1'>".$icon." ".$d['domain_key']." ".$score."%</span>";
                    }
                  }
                ?>
                <tr class="text-center">
                  <td><?php echo htmlspecialchars($s['id']); ?></td>
                  <td><?php echo htmlspecialchars($s['assignment_title']); ?></td>
                  <td><?php echo htmlspecialchars($s['subassignment_id']); ?></td>
                  <td><?php echo htmlspecialchars($s['attempt_no']); ?></td>
                  <td><?php echo strtoupper(htmlspecialchars($s['level'])); ?></td>
                  <td><span class="badge bg-info"><?php echo number_format($s['auto_score'],1); ?>%</span></td>
                  <td><span class="badge bg-success"><?php echo number_format($s['final_score'],1); ?>%</span></td>
                  
                  <td>
  <?php
    $is_graded = isset($s['instructor_score']) && $s['instructor_score'] != $s['final_score'] && $s['instructor_score'] != 0;
    if ($is_graded) {
      echo '<span class="badge bg-success">Graded</span>';
    } else {
      echo '<span class="badge bg-secondary">Yet to Grade</span>';
    }
  ?>
</td>

                  <td>
                    <a href="ct_submission_detail.php?submission_id=<?php echo urlencode($s['id']); ?>" 
                       class="btn btn-sm btn-outline-primary">
                      <i class="fa fa-eye"></i> View
                    </a>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <?php require_once('../platformFooter.php'); ?>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
jQuery(function($){
  $('#ctGradesTable').DataTable({
    "pageLength": 25,
    "order": [[0, 'desc']],
    "lengthChange": false,
    "columnDefs": [{ "orderable": false, "targets": [5,7,8] }],
    "autoWidth": false
  });
});
</script>
