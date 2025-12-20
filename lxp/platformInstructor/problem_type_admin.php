<?php
/**
 * Astraal LXP - Instructor Problem Type Administration
 * Multi-view router + integrated attempts view (DataTables client-side)
 * PHP 5.4 + MySQL 5.x Compatible
 *
 * Modified: list view now shows Attempts count per variant and:
 *  - Attempts count is a link only when > 0
 *  - Attempts action button in Actions column is shown only when attempts_count > 0
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];
$page = "problemSolvingInstructor";
require_once('instructorHead_Nav2.php');

/* ----------------------------------------------
   READ QUERY PARAMS
---------------------------------------------- */
$action = isset($_GET['action']) ? trim($_GET['action']) : 'dashboard';
$type_slug = isset($_GET['type']) ? trim($_GET['type']) : '';

// NOTE: for attempts view type is optional (show across all types if absent)
if ($action !== 'attempts' && $type_slug == '') {
    die("Invalid problem type.");
}

if ($type_slug !== '') {
    $type_slug = mysqli_real_escape_string($coni, $type_slug);
}

/* ----------------------------------------------
   FETCH PROBLEM TYPE DETAILS (if provided)
---------------------------------------------- */
$type_id = 0;
$type_title = '';

if ($type_slug !== '') {
    $sqlType = "
        SELECT id, slug, title
        FROM problem_types
        WHERE slug = '{$type_slug}' AND active = 1
        LIMIT 1
    ";
    $resType = $coni->query($sqlType);

    if (!$resType || $resType->num_rows == 0) {
        die("Problem Type Not Found");
    }

    $typeData = $resType->fetch_assoc();
    $type_id  = (int)$typeData['id'];
    $type_title = $typeData['title'];
}

/* ----------------------------------------------
   PHP5.4 helper: dynamic bind for mysqli_stmt (references)
---------------------------------------------- */
function bindParamsDynamic($stmt, $types, $params)
{
    if (empty($params)) {
        return true;
    }
    $refs = array();
    foreach ($params as $k => $v) {
        $refs[$k] = &$params[$k];
    }
    array_unshift($refs, $types);
    return call_user_func_array(array($stmt, 'bind_param'), $refs);
}

/* ------------------------------
   If type present, load some counts for dashboard/list
   ------------------------------ */
$levelCounts = array('kid'=>0,'teen'=>0,'adult'=>0);
$totalVar = 0;
$pendingAttempts = 0;

if ($type_id > 0) {

    /* variants by level */
    $sqlCount = "
        SELECT level, COUNT(*) AS total
        FROM problem_variants
        WHERE problem_type_id = {$type_id}
        GROUP BY level
    ";
    $resCount = $coni->query($sqlCount);
    if ($resCount) {
        while ($row = $resCount->fetch_assoc()) {
            $levelCounts[$row['level']] = (int)$row['total'];
        }
    }

    /* total variants */
    $sqlTotalVar = "
        SELECT COUNT(*) AS c
        FROM problem_variants
        WHERE problem_type_id = {$type_id}
    ";
    $resTotalVar = $coni->query($sqlTotalVar);
    if ($resTotalVar) {
        $totalVar = (int)$resTotalVar->fetch_assoc()['c'];
    }

    /* pending attempts */
    $sqlPending = "
        SELECT COUNT(*) AS c
        FROM problem_attempts
        WHERE status='submitted'
          AND problem_variant_id IN (
                SELECT id FROM problem_variants
                WHERE problem_type_id={$type_id}
          )
    ";
    $resPend = $coni->query($sqlPending);
    if ($resPend) {
        $pendingAttempts = (int)$resPend->fetch_assoc()['c'];
    }
}

/* ============================================================
   DASHBOARD VIEW (default)
============================================================ */
if ($action == 'dashboard'):
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">
<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
          <i class="bx bx-category me-2"></i>
          Problem Type : <?php echo htmlspecialchars($type_title); ?> Dashboard
        </h3>

        <p class="text-muted mb-0">
          Manage all variants, difficulty levels, and learner attempts for this problem type.
        </p>

      </div>
    </div>
  </div>
</div>


<!-- SUMMARY CARDS -->
<div class="row">


<!-- Back to global dashboard (requested change) -->
<div class="row mt-4 mb-5">
  <div class="col-md-4">
    <a href="problem-solving-skills.php" class="btn btn-secondary w-100">
      <i class="bx bx-arrow-back"></i> Back to Dashboard
    </a>
  </div>
</div>



  <!-- Total Variants -->
  <div class="col-md-4 mb-3">
    <div class="card shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bx bx-list-ul text-primary" style="font-size:32px;"></i>
        <h5 class="card-title mt-2">Total Questions</h5>
        <h3><?php echo (int)$totalVar; ?></h3>

        <a href="problem_type_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>"
           class="btn btn-primary btn-sm mt-2">
           View All Questions
        </a>
      </div>
    </div>
  </div>

  <!-- Pending Grading -->
  <div class="col-md-4 mb-3">
    <div class="card shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bx bx-time-five text-warning" style="font-size:32px;"></i>
        <h5 class="card-title mt-2">Pending for Review</h5>
        <h3><?php echo (int)$pendingAttempts; ?></h3>

        <!-- Link to integrated attempts view filtered to this type -->
        <a href="problem_type_admin.php?action=attempts&type=<?php echo urlencode($type_slug); ?>"
           class="btn btn-warning btn-sm mt-2">
           Review Attempts
        </a>
      </div>
    </div>
  </div>

  <!-- Add New Variant -->
  <div class="col-md-4 mb-3">
    <div class="card shadow-sm h-100">
      <div class="card-body text-center">
        <i class="bx bx-plus-circle text-success" style="font-size:32px;"></i>
        <h5 class="card-title mt-2">Create New Question</h5>
        <h3><i class="bx bx-chevron-right"></i></h3>

        <a href="problem_type_admin.php?action=create&type=<?php echo urlencode($type_slug); ?>"
           class="btn btn-success btn-sm mt-2">
           Add New Variant
        </a>
      </div>
    </div>
  </div>

</div>

<!-- VARIANTS BY LEVEL -->
<div class="row mt-4">
  <div class="col-12">
    <h5 class="fw-semibold">
      <i class="bx bx-layer"></i> Variants by Age Group
    </h5>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card p-3 shadow-sm text-center">
      <h6>Kid</h6>
      <h3><?php echo (int)$levelCounts['kid']; ?></h3>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card p-3 shadow-sm text-center">
      <h6>Teen</h6>
      <h3><?php echo (int)$levelCounts['teen']; ?></h3>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card p-3 shadow-sm text-center">
      <h6>Adult</h6>
      <h3><?php echo (int)$levelCounts['adult']; ?></h3>
    </div>
  </div>
</div>

<!-- NAVIGATION BUTTONS -->
<div class="row mt-4">
  <div class="col-md-4">
    <a href="problem_type_admin.php?action=list&type=<?php echo urlencode($type_slug); ?>"
       class="btn btn-primary w-100">
       <i class="bx bx-list-ul"></i> View All Questions
    </a>
  </div>

  <div class="col-md-4">
    <a href="problem_type_admin.php?action=create&type=<?php echo urlencode($type_slug); ?>"
       class="btn btn-success w-100">
       <i class="bx bx-plus-circle"></i> Create New Question
    </a>
  </div>

  <div class="col-md-4">
    <a href="problem_type_admin.php?action=analytics&type=<?php echo urlencode($type_slug); ?>"
       class="btn btn-info w-100">
       <i class="bx bx-bar-chart-alt"></i> View Analytics
    </a>
  </div>
</div>


</div>
</div>

<?php
endif; // dashboard
?>





<?php
/* ============================================================
   LIST VIEW — Shows all problem variants for this type
   (Now: includes attempts_count column and link only when >0,
    and Actions column hides Attempts button when attempts_count == 0)
============================================================ */
if ($action == 'list'):

$sqlList = "
    SELECT v.id, v.level, v.difficulty_score, v.problem_slug, v.statement, v.version,
           (SELECT COUNT(*) FROM problem_attempts pa WHERE pa.problem_variant_id = v.id) AS attempts_count
    FROM problem_variants v
    WHERE problem_type_id = {$type_id}
    ORDER BY 
        FIELD(level,'kid','teen','adult'),
        difficulty_score ASC
";
$resList = $coni->query($sqlList);

$diffNames = array(
    1 => "Novice",
    2 => "Beginner",
    3 => "Intermediate",
    4 => "Advanced",
    5 => "Expert"
);

?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">



<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
		 <i class="bx bx-list-ul me-2"></i>
            List of Questions for: <?php echo htmlspecialchars($type_title); ?> Type
        </h3>

        <p class="text-muted mb-0">
          Manage, edit, and review problem variants.
        </p>

      </div>
    </div>
  </div>
</div>



<!-- ACTION BUTTONS -->
<div class="row mb-3">
  <div class="col-md-4 mb-2">
    <a href="problem_type_admin.php?action=create&type=<?php echo urlencode($type_slug); ?>"
       class="btn btn-success w-100">
       <i class="bx bx-plus-circle"></i> Add New Variant
    </a>
  </div>

  <div class="col-md-4 mb-2">
    <a href="problem-solving-skills.php"
       class="btn btn-secondary w-100">
       <i class="bx bx-arrow-back"></i> Back to Dashboard
    </a>
  </div>

  <div class="col-md-4 mb-2">
    <a href="problem_type_admin.php?action=analytics&type=<?php echo urlencode($type_slug); ?>"
       class="btn btn-info w-100">
       <i class="bx bx-bar-chart-alt"></i> View Analytics
    </a>
  </div>
</div>

<!-- VARIANTS TABLE -->
<div class="card shadow-sm">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
          <tr>
            <th width="10%">Level</th>
            <th width="10%">Difficulty</th>
            <th width="18%">Slug</th>
            <th width="10%">Attempts</th>
            <th>Statement</th>
            <th width="6%">Ver.</th>
            <th width="16%">Actions</th>
          </tr>
        </thead>

        <tbody>

        <?php if (!$resList || $resList->num_rows == 0): ?>
          <tr>
            <td colspan="7" class="text-center text-muted">
              No questions found. Click "Add New Variant" to begin.
            </td>
          </tr>

        <?php else: ?>

          <?php while ($v = $resList->fetch_assoc()): ?>

          <tr>
            <td class="text-center">
              <?php if ($v['level'] == 'kid'): ?>
                <span class="badge bg-primary">Kid</span>
              <?php elseif ($v['level'] == 'teen'): ?>
                <span class="badge bg-warning text-dark">Teen</span>
              <?php else: ?>
                <span class="badge bg-dark">Adult</span>
              <?php endif; ?>
            </td>

            <td class="text-center">
              <span class="badge bg-info">
                <?php echo htmlspecialchars($v['difficulty_score']); ?>
              </span>
            </td>

            <td><?php echo htmlspecialchars($v['problem_slug']); ?></td>

            <td class="text-center">
                <?php $attempts_count = (int)$v['attempts_count']; ?>
                <?php if ($attempts_count > 0): ?>
                    <a href="problem_type_admin.php?action=attempts&type=<?php echo urlencode($type_slug); ?>&variant_id=<?php echo (int)$v['id']; ?>"
                       class="badge bg-secondary" style="text-decoration:none;">
                        <?php echo $attempts_count; ?> Attempts
                    </a>
                <?php else: ?>
                    <span class="text-muted">0 Attempts</span>
                <?php endif; ?>
            </td>

            <td>
              <?php
                $s = strip_tags($v['statement']);
                echo htmlspecialchars(strlen($s) > 120 ? substr($s,0,120)."..." : $s);
              ?>
            </td>

            <td class="text-center">
              <span class="badge bg-secondary"><?php echo (int)$v['version']; ?></span>
            </td>

            <td class="text-center">

              <a href="problem_type_admin.php?action=edit&type=<?php echo urlencode($type_slug); ?>&id=<?php echo (int)$v['id']; ?>"
                 class="btn btn-sm btn-primary mb-1">
                 <i class="bx bx-edit"></i> Edit
              </a>

              <?php if ($attempts_count > 0): ?>
              <a href="problem_type_admin.php?action=attempts&type=<?php echo urlencode($type_slug); ?>&variant_id=<?php echo (int)$v['id']; ?>"
                 class="btn btn-sm btn-warning mb-1">
                 <i class="bx bx-user-check"></i> Attempts
              </a>
              <?php endif; ?>

            </td>
          </tr>

          <?php endwhile; ?>

        <?php endif; ?>

        </tbody>
      </table>
    </div>

  </div>
</div>

<?php endif; // list ?>







<?php
/* ============================================================
   CREATE / SAVE NEW / EDIT / SAVE EDIT (re-used verbatim)
============================================================ */
if ($action == 'create'):
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
		 <i class="bx bx-plus-circle me-2"></i>
            Create Questions for: <?php echo htmlspecialchars($type_title); ?> Type
        </h3>

        <p class="text-muted mb-0">
         Create a new problem statement for Kid, Teen, or Adult.
        </p>

      </div>
    </div>
  </div>
</div>



<div class="card shadow-sm">
  <div class="card-body">

    <form method="POST" action="problem_type_admin.php?action=save_new&type=<?php echo urlencode($type_slug); ?>">

      <div class="mb-3">
        <label class="form-label fw-bold">Age Level</label>
        <select name="level" class="form-control" required>
          <option value="">-- Select Level --</option>
          <option value="kid">Kid</option>
          <option value="teen">Teen</option>
          <option value="adult">Adult</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Difficulty</label>
        <select name="difficulty_score" class="form-control" required>
          <option value="">-- Select Difficulty --</option>
          <option value="1">Novice</option>
          <option value="2">Beginner</option>
          <option value="3">Intermediate</option>
          <option value="4">Advanced</option>
          <option value="5">Expert</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Problem Slug</label>
        <input type="text" name="problem_slug" class="form-control" placeholder="example: authentic-kid-01" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Problem Statement</label>
        <textarea name="statement" rows="4" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">How to Approach</label>
        <textarea name="how_to" rows="3" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Expected Outcome</label>
        <textarea name="expected_outcome" rows="3" class="form-control"></textarea>
      </div>

      <button type="submit" class="btn btn-success">
        <i class="bx bx-check-circle"></i> Save New Question
      </button>

      <a href="problem_type_admin.php?action=list&type=<?php echo urlencode($type_slug); ?> "
         class="btn btn-secondary">
        Cancel
      </a>

    </form>

  </div>
</div>



<?php
endif; // create
?>


<?php
/* SAVE NEW */
if ($action == 'save_new'):

$level      = mysqli_real_escape_string($coni, $_POST['level']);
$diff       = (int)$_POST['difficulty_score'];
$slug_v     = mysqli_real_escape_string($coni, $_POST['problem_slug']);
$statement  = mysqli_real_escape_string($coni, $_POST['statement']);
$howto      = mysqli_real_escape_string($coni, $_POST['how_to']);
$outcome    = mysqli_real_escape_string($coni, $_POST['expected_outcome']);

$now = time();

$sqlInsert = "
    INSERT INTO problem_variants
    (problem_type_id, level, problem_slug, statement, how_to, expected_outcome, difficulty_score, version, created_at, updated_at)
    VALUES
    ({$type_id}, '{$level}', '{$slug_v}', '{$statement}', '{$howto}', '{$outcome}', {$diff}, 1, {$now}, {$now})
";

$coni->query($sqlInsert);

$diffText = mysqli_real_escape_string($coni, "Created variant {$slug_v}");
$coni->query("
    INSERT INTO audit_log (actor_login, entity_type, entity_id, action, diff_text, timestamp)
    VALUES ('{$phx_user_login}', 'variant', '{$slug_v}', 'create', '{$diffText}', UNIX_TIMESTAMP())
");

header("Location: problem_type_admin.php?action=list&type={$type_slug}&msg=" . urlencode(base64_encode("Created successfully")));
exit;

endif; // save_new
?>





<?php
/* EDIT */
if ($action == 'edit'):

$vid = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sqlOne = "
    SELECT *
    FROM problem_variants
    WHERE id = {$vid} AND problem_type_id = {$type_id}
    LIMIT 1
";
$resOne = $coni->query($sqlOne);

if (!$resOne || $resOne->num_rows == 0) {
    die("Invalid variant ID");
}

$V = $resOne->fetch_assoc();

?>



<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
		 <i class="bx bx-edit me-2"></i>
            Edit Questions for: <?php echo htmlspecialchars($type_title); ?> Type
        </h3>

        <p class="text-muted mb-0">
         Edit the problem statement for Kid, Teen, or Adult.
        </p>

      </div>
    </div>
  </div>
</div>



<div class="card shadow-sm">
  <div class="card-body">

    <form method="POST" action="problem_type_admin.php?action=save_edit&type=<?php echo urlencode($type_slug); ?>&id=<?php echo $vid; ?>">

      <div class="mb-3">
        <label class="form-label fw-bold">Age Level</label>
        <select name="level" class="form-control" required>
          <option value="kid"   <?php if($V['level']=='kid') echo 'selected'; ?>>Kid</option>
          <option value="teen"  <?php if($V['level']=='teen') echo 'selected'; ?>>Teen</option>
          <option value="adult" <?php if($V['level']=='adult') echo 'selected'; ?>>Adult</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Difficulty</label>
        <select name="difficulty_score" class="form-control" required>
          <?php foreach (array(1=>"Novice",2=>"Beginner",3=>"Intermediate",4=>"Advanced",5=>"Expert") as $k=>$v): ?>
          <option value="<?php echo $k; ?>" <?php if($V['difficulty_score']==$k) echo 'selected'; ?>>
            <?php echo $v; ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Problem Slug</label>
        <input type="text" name="problem_slug" class="form-control" value="<?php echo htmlspecialchars($V['problem_slug']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Problem Statement</label>
        <textarea name="statement" rows="4" class="form-control"><?php echo htmlspecialchars($V['statement']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">How to Approach</label>
        <textarea name="how_to" rows="3" class="form-control"><?php echo htmlspecialchars($V['how_to']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label fw-bold">Expected Outcome</label>
        <textarea name="expected_outcome" rows="3" class="form-control"><?php echo htmlspecialchars($V['expected_outcome']); ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="bx bx-save"></i> Save Changes
      </button>

      <a href="problem-solving-skills.php" class="btn btn-secondary">Back to Dashboard</a>

    </form>

  </div>
</div>

<?php endif; // edit ?>


<?php
/* SAVE EDIT */
if ($action == 'save_edit'):

$vid        = (int)$_GET['id'];
$level      = mysqli_real_escape_string($coni, $_POST['level']);
$diff       = (int)$_POST['difficulty_score'];
$slug_v     = mysqli_real_escape_string($coni, $_POST['problem_slug']);
$statement  = mysqli_real_escape_string($coni, $_POST['statement']);
$howto      = mysqli_real_escape_string($coni, $_POST['how_to']);
$outcome    = mysqli_real_escape_string($coni, $_POST['expected_outcome']);
$now        = time();

$sqlUpd = "
    UPDATE problem_variants
    SET
        level='{$level}',
        difficulty_score={$diff},
        problem_slug='{$slug_v}',
        statement='{$statement}',
        how_to='{$howto}',
        expected_outcome='{$outcome}',
        version = version + 1,
        updated_at = {$now}
    WHERE id = {$vid}
      AND problem_type_id = {$type_id}
";

$coni->query($sqlUpd);

$diffText = mysqli_real_escape_string($coni, "Updated variant {$slug_v}");
$coni->query("
    INSERT INTO audit_log (actor_login, entity_type, entity_id, action, diff_text, timestamp)
    VALUES ('{$phx_user_login}', 'variant', '{$slug_v}', 'update', '{$diffText}', UNIX_TIMESTAMP())
");

header("Location: problem_type_admin.php?action=list&type={$type_slug}&msg=" . urlencode(base64_encode("Updated successfully")));
exit;

endif; // save_edit
?>







<?php
/* ============================================================
   ANALYTICS VIEW  (action=analytics)
============================================================ */
if ($action == 'analytics'):
    /* TOTAL ATTEMPTS */
    $sqlAttempts = "
        SELECT COUNT(*) AS c
        FROM problem_attempts
        WHERE problem_variant_id IN (
            SELECT id FROM problem_variants WHERE problem_type_id={$type_id}
        )
    ";
    $totalAttempts = 0;
    $rA = $coni->query($sqlAttempts);
    if ($rA) { $totalAttempts = (int)$rA->fetch_assoc()['c']; }

    /* AVG SCORE */
    $sqlAvgScore = "
        SELECT AVG(score) AS avg_s
        FROM problem_attempts
        WHERE status='graded'
          AND score IS NOT NULL
          AND problem_variant_id IN (
                SELECT id FROM problem_variants WHERE problem_type_id={$type_id}
          )
    ";
    $avgScore = 0;
    $rS = $coni->query($sqlAvgScore);
    if ($rS) {
        $avgScore = round((float)$rS->fetch_assoc()['avg_s'], 2);
    }

    /* ATTEMPTS BY LEVEL */
    $sqlLevel = "
        SELECT v.level, COUNT(a.id) AS total
        FROM problem_attempts a
        JOIN problem_variants v ON v.id = a.problem_variant_id
        WHERE v.problem_type_id = {$type_id}
        GROUP BY v.level
    ";
    $levelStats = array('kid'=>0,'teen'=>0,'adult'=>0);
    $rL = $coni->query($sqlLevel);
    if ($rL) {
        while ($row = $rL->fetch_assoc()) {
            $levelStats[$row['level']] = (int)$row['total'];
        }
    }

    /* ATTEMPTS BY DIFFICULTY */
    $sqlDiff = "
        SELECT v.difficulty_score, COUNT(a.id) AS total
        FROM problem_attempts a
        JOIN problem_variants v ON v.id = a.problem_variant_id
        WHERE v.problem_type_id = {$type_id}
        GROUP BY v.difficulty_score
    ";
    $diffStats = array(1=>0,2=>0,3=>0,4=>0,5=>0);
    $rD = $coni->query($sqlDiff);
    if ($rD) {
        while ($row = $rD->fetch_assoc()) {
            $diffStats[(int)$row['difficulty_score']] = (int)$row['total'];
        }
    }
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
		 <i class="bx bx-bar-chart-alt me-2"></i>
            Analytics for: <?php echo htmlspecialchars($type_title); ?> Type
        </h3>

        <p class="text-muted mb-0">
         Performance summary based on all learner interactions.
        </p>

      </div>
    </div>
  </div>
</div>




<!-- SUMMARY CARDS -->
<div class="row">

  <div class="col-md-4 mb-3">
    <div class="card shadow-sm text-center p-3">
      <i class="bx bx-line-chart text-primary" style="font-size:32px;"></i>
      <h5 class="mt-2">Total Attempts</h5>
      <h3><?php echo $totalAttempts; ?></h3>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card shadow-sm text-center p-3">
      <i class="bx bx-star text-warning" style="font-size:32px;"></i>
      <h5 class="mt-2">Average Score</h5>
      <h3><?php echo ($avgScore > 0 ? $avgScore : "—"); ?></h3>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card shadow-sm text-center p-3">
      <i class="bx bx-group text-success" style="font-size:32px;"></i>
      <h5 class="mt-2">Unique Age Groups Attempted</h5>
      <h3>
        <?php
          $u = 0;
          if ($levelStats['kid']   > 0) $u++;
          if ($levelStats['teen']  > 0) $u++;
          if ($levelStats['adult'] > 0) $u++;
          echo $u;
        ?>
      </h3>
    </div>
  </div>

</div>

<!-- BACK BUTTON -->
<div class="row mt-4 mb-5">
  <div class="col-md-4">
    <a href="problem-solving-skills.php" class="btn btn-secondary w-100">
       <i class="bx bx-arrow-back"></i> Back to Dashboard
    </a>
  </div>
</div>

</div>
</div>

<?php endif; // analytics ?>





<!----         VIEW Learener ATTEMPTS  Block STARTS HERE --------------------->
<?php
/*   VIEW Learener ATTEMPTS 

File: platformInstructor/attempts_view_fixed_with_attachments.php
   PHP 5.4 compatible. Uses problem_attachments and problem_attempt_scores.
*/

if ($action == 'attempts'):

// DEV flag: set to true while debugging to show SQL/params/errors on page. Set false in production.
$DEV = false;

/* Reliable bind helper for mysqli_stmt (PHP 5.4 compatible) */
function bind_stmt_params($stmt, $types, $params) {
    if ($types === '' || empty($params)) return true;
    $params_copy = array_values($params);
    $refs = array();
    foreach ($params_copy as $k => $v) {
        $refs[$k] = & $params_copy[$k];
    }
    array_unshift($refs, $types);
    return call_user_func_array(array($stmt, 'bind_param'), $refs);
}

// Dev debug printing (PHP 5.4 compatible)
function dev_debug_sql($sql, $params, $error) {
    global $DEV;
    if (!$DEV) return;
  //  echo "<div style='padding:8px;border:1px solid #d39e00;background:#fff9e6;color:#6a4b00;margin-bottom:10px;font-family:monospace;'>";
   // echo "<strong>DEBUG SQL:</strong><br>" . htmlspecialchars($sql) . "<br>";
    if (!empty($params)) echo "<strong>PARAMS:</strong> " . htmlspecialchars(json_encode($params)) . "<br>";
    if (!empty($error)) echo "<strong>ERROR:</strong> " . htmlspecialchars($error) . "<br>";
  //  echo "</div>";
}

/* Build server query: optional restriction by type_id (if set) or specific variant */
$filter_variant = isset($_GET['variant_id']) ? (int) $_GET['variant_id'] : 0;
$export_attempts = isset($_GET['export']) && $_GET['export'] == '1';

$where_parts = array();
$params = array();
$types = '';

// Default join to fetch variant slug (keep INNER JOIN to enforce variant existence)
$join_sql = ' INNER JOIN problem_variants pv ON pa.problem_variant_id = pv.id ';

if (isset($type_id) && $type_id > 0) {
    $where_parts[] = 'pv.problem_type_id = ?';
    $params[] = $type_id; $types .= 'i';
}

if ($filter_variant > 0) {
    $where_parts[] = 'pa.problem_variant_id = ?';
    $params[] = $filter_variant; $types .= 'i';
}

$where_sql = '';
if (count($where_parts) > 0) {
    $where_sql = ' WHERE ' . implode(' AND ', $where_parts);
}

/* Count total attempts (no attachments/scores needed) */
$total_attempts = 0;
$count_sql = "SELECT COUNT(pa.id) AS cnt FROM problem_attempts pa " . $join_sql . $where_sql;
$stmt = $coni->prepare($count_sql);
if ($stmt) {
    $p_types = $types; $p_params = $params;
    if (!bind_stmt_params($stmt, $p_types, $p_params)) {
        error_log("bind failed for count query");
        if ($DEV) dev_debug_sql($count_sql, $p_params, 'bind_param failed');
    }
    if ($DEV) dev_debug_sql($count_sql, $p_params, null);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $total_attempts = (int)$row['cnt'];
    } else {
        error_log("count exec err: " . $stmt->error);
        if ($DEV) dev_debug_sql($count_sql, $p_params, $stmt->error);
    }
    $stmt->close();
} else {
    error_log("count prepare err: " . $coni->error . " SQL: " . $count_sql);
    if ($DEV) dev_debug_sql($count_sql, $params, $coni->error);
}

/* CSV Export: include attachments (file_path) and computed display_score */
if ($export_attempts) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=attempts_export_' . date('Ymd_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, array('id','user_login','problem_variant_id','variant_slug','started_at','submitted_at','status','base_score','display_score','file_paths','feedback'));

    $export_sql =
      "SELECT pa.id,pa.user_login,pa.problem_variant_id,pv.problem_slug,pa.started_at,pa.submitted_at,pa.status,pa.score AS base_score,"
      . " COALESCE(ps.rubric_score, pa.score) AS display_score, att.file_paths, pa.feedback "
      . "FROM problem_attempts pa "
      . $join_sql
      . " LEFT JOIN (SELECT attempt_id, GROUP_CONCAT(file_path ORDER BY id SEPARATOR ';') AS file_paths FROM problem_attachments WHERE is_deleted = 0 GROUP BY attempt_id) att ON att.attempt_id = pa.id "
      . " LEFT JOIN (SELECT attempt_id, SUM(IFNULL(score,0)) AS rubric_score FROM problem_attempt_scores GROUP BY attempt_id) ps ON ps.attempt_id = pa.id "
      . $where_sql
      . " ORDER BY pa.id DESC";

    $stmt = $coni->prepare($export_sql);
    if ($stmt) {
        $p_types = $types; $p_params = $params;
        if (!bind_stmt_params($stmt, $p_types, $p_params)) {
            error_log("bind failed for export query");
            if ($DEV) dev_debug_sql($export_sql, $p_params, 'bind_param failed');
        }
        if ($DEV) dev_debug_sql($export_sql, $p_params, null);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                fputcsv($out, array(
                    $r['id'],
                    $r['user_login'],
                    $r['problem_variant_id'],
                    $r['problem_slug'],
                    $r['started_at'],
                    $r['submitted_at'],
                    $r['status'],
                    $r['base_score'],
                    $r['display_score'],
                    $r['file_paths'],
                    $r['feedback']
                ));
            }
        } else {
            error_log("export exec err: " . $stmt->error);
            if ($DEV) dev_debug_sql($export_sql, $p_params, $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("export prepare err: " . $coni->error . " SQL: " . $export_sql);
        if ($DEV) dev_debug_sql($export_sql, $params, $coni->error);
    }
    fclose($out);
    exit;
}

/* Fetch server-side result set for rendering into client DataTable
   - att.file_paths: semicolon-separated list of file names for the attempt (may be NULL)
   - ps.rubric_score: sum of rubric scores (may be NULL)
   - display_score: prefer rubric_score if present, else attempt's base score
*/
$list_sql =
  "SELECT pa.id,pa.user_login,pa.problem_variant_id,pv.problem_slug,pa.started_at,pa.submitted_at,pa.status,pa.score AS base_score, "
  . "COALESCE(ps.rubric_score, pa.score) AS display_score, att.file_paths "
  . "FROM problem_attempts pa "
  . $join_sql
  . " LEFT JOIN (SELECT attempt_id, GROUP_CONCAT(file_path ORDER BY id SEPARATOR ';') AS file_paths FROM problem_attachments WHERE is_deleted = 0 GROUP BY attempt_id) att ON att.attempt_id = pa.id "
  . " LEFT JOIN (SELECT attempt_id, SUM(IFNULL(score,0)) AS rubric_score FROM problem_attempt_scores GROUP BY attempt_id) ps ON ps.attempt_id = pa.id "
  . $where_sql
  . " ORDER BY pa.id DESC";

$stmt = $coni->prepare($list_sql);
$attempt_rows = array();
if ($stmt) {
    $p_types = $types; $p_params = $params;
    if (!bind_stmt_params($stmt, $p_types, $p_params)) {
        error_log("bind failed for list query");
        if ($DEV) dev_debug_sql($list_sql, $p_params, 'bind_param failed');
    }
    if ($DEV) dev_debug_sql($list_sql, $p_params, null);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $attempt_rows[] = $r;
    } else {
        error_log("attempts list exec err: " . $stmt->error);
        if ($DEV) dev_debug_sql($list_sql, $p_params, $stmt->error);
    }
    $stmt->close();
} else {
    error_log("attempts list prepare err: " . $coni->error . " SQL: " . $list_sql);
    if ($DEV) dev_debug_sql($list_sql, $params, $coni->error);
}

/* Render attempts list with DataTables */
?>




<div class="layout-page">
<?php require_once('instructorNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="row">
  <div class="col-lg-12 mb-4 order-0">
    <div class="card">
      <div class="card-body">

        <h3 class="fw-bold mb-2">
		 <i class="bx bx-user-check me-2"></i>
            Attempts for: <?php if (isset($type_id) && $type_id > 0) echo "— " . htmlspecialchars($type_title); ?>
			 
        </h3>

        <p class="text-muted mb-0">
         Attempts summary based on all learner's submissions.
        </p>

      </div>
    </div>
  </div>
</div>


<div class="row mb-2">
  <div class="col-md-3">
    <a href="problem-solving-skills.php" class="btn btn-secondary w-100">
      <i class="bx bx-arrow-back"></i> Back to Dashboard
    </a>
  </div>
  <div class="col-md-3">
    <a class="btn btn-outline-secondary" href="?<?php $qs = $_GET; $qs['export']='1'; echo htmlspecialchars(http_build_query($qs)); ?>">Export CSV</a>
  </div>
  <div class="col-md-6 text-end">
    <div class="small text-muted">Showing <?php echo count($attempt_rows); ?> server rows (<?php echo (int)$total_attempts; ?> total attempts).</div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="attemptsTable" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
			<th>User</th>
			<th>Variant</th>
			<th>Variant Slug</th>
			<th>Started</th>
			<th>Submitted</th>
			<th>Status</th>
			<th>Score</th>
			<th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($attempt_rows as $ar):
              $id = (int)$ar['id'];
              $user_login = htmlspecialchars($ar['user_login']);
              $variant_id = (int)$ar['problem_variant_id'];
              $slug = htmlspecialchars(isset($ar['problem_slug']) ? $ar['problem_slug'] : 'Unknown');
              $started = (!empty($ar['started_at']) && is_numeric($ar['started_at'])) ? date('Y-m-d H:i', (int)$ar['started_at']) : '';
              $submitted = (!empty($ar['submitted_at']) && is_numeric($ar['submitted_at'])) ? date('Y-m-d H:i', (int)$ar['submitted_at']) : '';
              // display_score comes from query: COALESCE(ps.rubric_score, pa.score)
              $display_score = ($ar['display_score'] !== null) ? htmlspecialchars($ar['display_score']) : '-';
              $file_paths = isset($ar['file_paths']) ? $ar['file_paths'] : null;
              // use first file for direct download link, or list all as links separated by <br>
              $file_cell = '-';
              if (!empty($file_paths)) {
                  $parts = explode(';', $file_paths);
                  $links = array();
                  foreach ($parts as $f) {
                      $f_trim = trim($f);
                      if ($f_trim === '') continue;
                      $url = '../../uploads/attempts/' . rawurlencode($f_trim);
                      $links[] = '<a target="_blank" rel="noopener" href="'.htmlspecialchars($url).'">'.htmlspecialchars($f_trim).'</a>';
                  }
                  if (!empty($links)) $file_cell = implode('<br>', $links);
              }
          ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $user_login; ?></td>
                <td><?php echo $variant_id; ?></td>
                <td><?php echo $slug; ?></td>
                <td><?php echo $started; ?></td>
                <td><?php echo $submitted; ?></td>
                <td><?php echo htmlspecialchars($ar['status']); ?></td>
                <td><?php echo $display_score; ?></td>
              
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="attempt.php?variant_id=<?php echo $variant_id; ?>&attempt_id=<?php echo $id; ?>">View</a>
                    <a class="btn btn-sm btn-outline-success" href="grade_attempt.php?attempt_id=<?php echo $id; ?>">Grade</a>
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
</div>

<!-- DataTables (CDN) and initialization -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
jQuery(document).ready(function($){
    try {
        $('#attemptsTable').DataTable({
            "pageLength": 25,
            "lengthChange": false,
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": 8 } // actions column
            ],
            "deferRender": true,
            "autoWidth": false
        });
    } catch (err) {
        console.error('DataTables init failed:', err);
    }
});
</script>

<?php
endif; // attempts
?>
<!----         VIEW Learener ATTEMPTS  Block ENDS HERE --------------------->


<?php
/* Footer */
require_once('../platformFooter.php');
?>
