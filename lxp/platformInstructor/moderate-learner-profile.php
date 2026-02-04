<?php
/**
 * Astraal LXP – Faculty Moderate Learner Profiles
 * AJAX moderation + diff view
 * PHP 5.4 compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$page = "moderation";
require_once('instructorHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl container-p-y">

<?php
// Flash messages
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars(base64_decode($_GET['msg']));
    echo "<script>Swal.fire('Info','$msg','info');</script>";
}
if (isset($_GET['error'])) {
    $err = htmlspecialchars(base64_decode($_GET['error']));
    echo "<script>Swal.fire('Error','$err','error');</script>";
}

// Fetch learner profile updates (last 4 weeks)
$sql = "
SELECT 
  lc.connection_id,
  lc.platform,
  lc.profile_url,
  lc.previous_profile_url,
  lc.updated_on,
  lc.status,
  u.name,
  u.surname,
  u.email
FROM learner_connections lc
JOIN users u ON u.id = lc.learner_id
WHERE lc.updated_on >= DATE_SUB(NOW(), INTERVAL 4 WEEK)
ORDER BY lc.updated_on DESC
";

$res = mysqli_query($coni, $sql);
?>

<div class="col-lg-12 mb-4 order-0">
<div class="accordion mt-3">
<div class="accordion-item">

<h4 class="accordion-header">
<button class="accordion-button bg-label-primary" data-bs-toggle="collapse"
data-bs-target="#accordion1">
<i class="bx bx-user-check" style="color:#ff5733;font-size:22px;"></i>
&nbsp; Learner Profile Updates – Pending Review (Last 4 Weeks)
</button>
</h4>

<div id="accordion1" class="accordion-collapse collapse show">
<div class="accordion-body">

<div class="table-responsive">
<table class="table table-bordered table-hover m-0">
<thead>
<tr class="text-center">
<th>#</th>
<th>Learner</th>
<th>Platform</th>
<th>What Changed</th>
<th>Updated</th>
<th>Faculty Comment</th>
<th colspan="2">Actions</th>
</tr>
</thead>

<tbody>

<?php
$i = 1;
if ($res && mysqli_num_rows($res) > 0):
while ($row = mysqli_fetch_assoc($res)):

$statusClass = 'bg-warning';
$statusText  = 'Under Review';

if ($row['status'] === 'approved') {
    $statusClass = 'bg-success';
    $statusText  = 'Approved';
} elseif ($row['status'] === 'rejected') {
    $statusClass = 'bg-danger';
    $statusText  = 'Rejected';
}
?>

<tr>
<td class="text-center"><?php echo $i++; ?></td>

<td>
<strong><?php echo htmlspecialchars($row['name'].' '.$row['surname']); ?></strong><br>
<small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
</td>

<td>
<i class="bx bx-link"></i> <?php echo htmlspecialchars($row['platform']); ?>
</td>

<td>
<?php if (!empty($row['previous_profile_url'])): ?>
  <div class="text-danger small">
    <strong>Old:</strong>
    <?php echo htmlspecialchars($row['previous_profile_url']); ?>
  </div>
  <div class="text-success small">
    <strong>New:</strong>
    <?php echo htmlspecialchars($row['profile_url']); ?>
  </div>
<?php else: ?>
  <a href="<?php echo htmlspecialchars($row['profile_url']); ?>" target="_blank">
    <?php echo htmlspecialchars($row['profile_url']); ?>
  </a>
<?php endif; ?>
</td>

<td>
<span class="badge bg-label-info">
<?php echo date('d M Y', strtotime($row['updated_on'])); ?>
</span>
</td>

<td>
<textarea id="comment-<?php echo $row['connection_id']; ?>"
class="form-control form-control-sm"
placeholder="Add review comment..."
<?php echo $row['status'] !== 'pending' ? 'disabled' : ''; ?>></textarea>
</td>

<td>
<?php if ($row['status'] === 'pending'): ?>
<button class="btn btn-sm btn-success"
onclick="moderateConnection(<?php echo $row['connection_id']; ?>,'approve')">
<i class="bx bx-check-circle"></i> Approve
</button>
<?php endif; ?>
</td>

<td>
<?php if ($row['status'] === 'pending'): ?>
<button class="btn btn-sm btn-danger"
onclick="moderateConnection(<?php echo $row['connection_id']; ?>,'reject')">
<i class="bx bx-x-circle"></i> Reject
</button>
<?php endif; ?>
</td>

</tr>

<?php endwhile; else: ?>
<tr>
<td colspan="9" class="text-center text-muted">
No learner profile updates in the last 4 weeks.
</td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>

<script>
function moderateConnection(id, action) {
  const comment = document.getElementById('comment-' + id).value;

  fetch('moderate_connection_ajax.php', {
    method: 'POST',
    body: new URLSearchParams({
      connection_id: id,
      action: action,
      comment: comment
    })
  })
  .then(r => r.json())
  .then(j => {
    if (j.status !== 'success') {
      Swal.fire('Error', j.message, 'error');
      return;
    }

    const badge = document.getElementById('status-' + id);
    badge.className = 'badge ' + (j.new_status === 'approved' ? 'bg-success' : 'bg-danger');
    badge.innerText = j.new_status === 'approved' ? 'Approved' : 'Rejected';

    Swal.fire('Success', 'Profile update ' + j.new_status, 'success');
  })
  .catch(() => {
    Swal.fire('Error', 'Server communication failed', 'error');
  });
}
</script>

<?php require_once('../platformFooter.php'); ?>
