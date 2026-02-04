<?php
/**
 * Course Intelligence Designer – Faculty View
 * Attaches intelligence to existing eFront course
 * PHP 5.4 compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'], $_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired.")));
    exit;
}

$page = "course_intelligence_designer";
require_once('instructorHead_Nav2.php');

/* Fetch existing eFront courses */
$courses = [];
$sql = "SELECT id, name FROM courses ORDER BY name ASC";
if ($stmt = $coni->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($cid, $cname);
    while ($stmt->fetch()) {
        $courses[] = ['id'=>$cid,'name'=>$cname];
    }
    $stmt->close();
}

/* Fetch learner milestones master */
$milestones = [];
$sql = "SELECT milestone_code, milestone_title FROM learner_milestones ORDER BY milestone_title ASC";
if ($stmt = $coni->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($mcode,$mname);
    while ($stmt->fetch()) {
        $milestones[] = ['code'=>$mcode,'name'=>$mname];
    }
    $stmt->close();
}
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- PAGE HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Course Intelligence Designer</h4>
        <small class="text-muted">
            Design how a course fits into learner journeys before building content.
        </small>
    </div>
    <span class="badge bg-primary">+ Create Course Intelligence</span>
</div>

<div class="card">
<div class="card-body">

<form action="instructorFormsli.php" method="post" id="intelligenceForm">

<input type="hidden" name="processType" value="saveCourseIntelligence">

<!-- ================= SECTION 1 ================= -->
<h5>SECTION 1 — Course Anchor</h5>

<div class="mb-3">
<label class="form-label">Select Existing Course (eFront)</label>
<select class="form-control" name="efront_course_id" id="efront_course_id" required>
<option value="">Select course</option>
<?php foreach ($courses as $c): ?>
<option value="<?= (int)$c['id']; ?>"
        data-name="<?= htmlspecialchars($c['name']); ?>">
<?= htmlspecialchars($c['name']); ?>
</option>
<?php endforeach; ?>
</select>
<small class="text-danger">This does NOT create a course. It attaches intelligence.</small>
</div>

<!-- ================= SECTION 2 ================= -->
<h5 class="mt-4">SECTION 2 — Course Design Sheet</h5>

<!-- Course Name -->
<div class="mb-3">
<label class="form-label">Course Name</label>
<input type="text" class="form-control" id="course_name_display" readonly>
<input type="hidden" name="course_name_label" id="course_name_label">
</div>

<!-- Primary Learner Problem -->
<div class="mb-3">
<label class="form-label">Primary Learner Problem</label>
<textarea class="form-control" name="primary_problem" rows="2"></textarea>
</div>

<!-- Supported Learning Directions -->
<label class="form-label">Supported Learning Directions</label>
<div class="row mb-3">
<?php
$dirs = [
 'guided_learning'=>'Guided Learning',
 'foundation_building'=>'Foundation Building',
 'skill_booster'=>'Skill Booster',
 'level_up'=>'Level Up'
];
foreach ($dirs as $k=>$v):
?>
<div class="col-md-3">
<div class="form-check">
<input class="form-check-input direction-check" type="checkbox" value="<?= $k; ?>">
<label class="form-check-label"><?= $v; ?></label>
</div>
</div>
<?php endforeach; ?>
</div>
<input type="hidden" name="supported_directions" id="supported_directions">

<!-- Milestones -->
<label class="form-label">Milestones Unlocked</label>
<div id="milestoneBlock">
<div class="row mb-2 milestone-row">
<div class="col-md-6">
<select class="form-control milestone-code">
<option value="">Select milestone</option>
<?php foreach ($milestones as $m): ?>
<option value="<?= htmlspecialchars($m['code']); ?>">
<?= htmlspecialchars($m['name']); ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="col-md-3">
<input type="number" step="0.01" min="0" max="1"
       class="form-control milestone-weight"
       placeholder="Weight">
</div>
</div>
</div>

<button type="button" class="btn btn-sm btn-outline-secondary" onclick="addMilestone()">
+ Add Milestone
</button>

<input type="hidden" name="milestone_support" id="milestone_support">

<!-- Bucket Roles -->
<label class="form-label mt-4">Bucket Roles</label>
<div class="row">
<?php
$buckets = [
 'curated'=>'Curated Paths',
 'active'=>'Active Learning',
 'booster'=>'Skills Booster',
 'level_up'=>'Level Up Courses',
 'crowd'=>'Crowd Favourites'
];
foreach ($buckets as $k=>$v):
?>
<div class="col-md-3">
<div class="form-check">
<input class="form-check-input bucket-check" type="checkbox" value="<?= $k; ?>">
<label class="form-check-label"><?= $v; ?></label>
</div>
</div>
<?php endforeach; ?>
</div>
<input type="hidden" name="bucket_roles" id="bucket_roles">

<!-- Exit Condition -->
<div class="mb-3 mt-3">
<label class="form-label text-danger">Exit Condition (mandatory)</label>
<textarea class="form-control" name="exit_condition" id="exit_condition" rows="3" required></textarea>
</div>

<!-- ================= SECTION 3 ================= -->
<h5 class="mt-4">SECTION 3 — Difficulty Role</h5>

<div class="mb-3">
<?php
$difficulty = ['foundation'=>'Foundation','core'=>'Core','stretch'=>'Stretch'];
foreach ($difficulty as $k=>$v):
?>
<div class="form-check form-check-inline">
<input class="form-check-input" type="radio"
       name="difficulty_role" value="<?= $k; ?>" <?= $k==='foundation'?'checked':''; ?>>
<label class="form-check-label"><?= $v; ?></label>
</div>
<?php endforeach; ?>
</div>

<!-- ================= SECTION 4 ================= -->
<h5>SECTION 4 — Crowd Signal</h5>

<div class="form-check mb-4">
<input class="form-check-input" type="checkbox" name="crowd_flag" value="1">
<label class="form-check-label">
Eligible to become Crowd Favourite
</label>
</div>

<!-- ACTIONS -->
<div class="d-flex justify-content-between">
<a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
<button type="submit" class="btn btn-primary">Save Intelligence</button>
</div>

</form>
</div>
</div>

</div>
</div>

<?php require_once('../platformFooter.php'); ?>
</div>
<script>
/* Auto-fill course name */
document.getElementById('efront_course_id').onchange = function () {
    var opt = this.options[this.selectedIndex];
    var name = opt ? opt.getAttribute('data-name') : '';
    document.getElementById('course_name_display').value = name;
    document.getElementById('course_name_label').value = name;
};

/* Add milestone row */
function addMilestone() {
    var block = document.getElementById('milestoneBlock');
    var row = block.children[0].cloneNode(true);
    var inputs = row.getElementsByTagName('input');
    var selects = row.getElementsByTagName('select');

    for (var i = 0; i < inputs.length; i++) inputs[i].value = '';
    for (var j = 0; j < selects.length; j++) selects[j].value = '';

    block.appendChild(row);
}

/* Guardrails + JSON packing (LEGACY SAFE) */
document.getElementById('intelligenceForm').onsubmit = function (e) {

    var dirs = [];
    var buckets = [];
    var milestones = [];

    var dirChecks = document.getElementsByClassName('direction-check');
    for (var i = 0; i < dirChecks.length; i++) {
        if (dirChecks[i].checked) {
            dirs.push(dirChecks[i].value);
        }
    }

    var bucketChecks = document.getElementsByClassName('bucket-check');
    for (var i = 0; i < bucketChecks.length; i++) {
        if (bucketChecks[i].checked) {
            buckets.push(bucketChecks[i].value);
        }
    }

    var rows = document.getElementsByClassName('milestone-row');
    for (var i = 0; i < rows.length; i++) {
        var code = rows[i].getElementsByClassName('milestone-code')[0].value;
        var weight = rows[i].getElementsByClassName('milestone-weight')[0].value;

        if (code !== '' && weight !== '') {
            milestones.push({
                milestone: code,
                weight: parseFloat(weight)
            });
        }
    }

    if (
        dirs.length < 1 ||
        buckets.length < 1 ||
        milestones.length < 1 ||
        document.getElementById('exit_condition').value.trim() === ''
    ) {
        alert('Design guardrails not satisfied.');
        return false;
    }

    document.getElementById('supported_directions').value = JSON.stringify(dirs);
    document.getElementById('bucket_roles').value = JSON.stringify(buckets);
    document.getElementById('milestone_support').value = JSON.stringify(milestones);

    return true;
};
</script>
