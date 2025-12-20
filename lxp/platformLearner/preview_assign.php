<?php
/**
 * preview_assign.php
 * Returns an HTML fragment with assignment preview (no get_result(); uses bind_result)
 * Place alongside learner pages; expects $coni from ../../config.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
// optional: require_once('../../session-guard.php');

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$assignment = isset($_GET['assignment']) ? trim($_GET['assignment']) : '';
if ($assignment === '') {
    header('HTTP/1.1 400 Bad Request');
    echo '<p>Missing assignment id.</p>';
    exit;
}

/* Fetch assignment row (bind_result style) */
$sql = "SELECT id, title, description FROM ct_assignments WHERE id = ?";
if (!$stmt = $coni->prepare($sql)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo '<p>Prepare failed: ' . h($coni->error) . '</p>';
    exit;
}
$stmt->bind_param('s', $assignment);
if (!$stmt->execute()) {
    echo '<p>Execute failed: ' . h($stmt->error) . '</p>';
    $stmt->close();
    exit;
}
$stmt->bind_result($a_id, $a_title, $a_description);
if (!$stmt->fetch()) {
    header('HTTP/1.1 404 Not Found');
    echo '<p>Assignment not found.</p>';
    $stmt->close();
    exit;
}
$stmt->close();

/* Fetch up to 10 subassignments for preview (bind_result) */
$subs = array();
$sql = "SELECT id, title, instructions, response_type FROM ct_subassignments WHERE assignment_id = ? ORDER BY id LIMIT 10";
if ($stmt = $coni->prepare($sql)) {
    $stmt->bind_param('s', $assignment);
    if ($stmt->execute()) {
        $stmt->bind_result($s_id, $s_title, $s_instructions, $s_response);
        while ($stmt->fetch()) {
            $subs[] = array('id'=>$s_id, 'title'=>$s_title, 'instructions'=>$s_instructions, 'response_type'=>$s_response);
        }
    }
    $stmt->close();
}

/* Output fragment */
?>
<div class="assign-preview">
  <h4><?php echo h($a_title); ?></h4>
  <p><?php echo nl2br(h($a_description)); ?></p>

  <hr/>
  <h5>Example tasks</h5>
  <?php if (count($subs) === 0): ?>
    <p><em>No preview items available for this assignment yet.</em></p>
  <?php else: ?>
    <ul>
      <?php foreach ($subs as $s): ?>
        <li style="margin-bottom:10px;">
          <strong><?php echo h($s['id']); ?> — <?php echo h($s['title']); ?></strong>
          <div style="margin:6px 0 8px;"><?php echo nl2br(h($s['instructions'])); ?></div>
          <small class="text-muted">Response: <?php echo h($s['response_type']); ?></small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <hr/>
  <p>
    <a href="assignment_view.php?assignment=<?php echo urlencode($a_id); ?>" class="btn btn-sm btn-outline-primary">Open full assignment</a>
    <?php if (count($subs) > 0): ?>
      <a href="player.php?sub=<?php echo urlencode($subs[0]['id']); ?>" class="btn btn-sm btn-primary">Start now</a>
    <?php endif; ?>
  </p>
</div>
