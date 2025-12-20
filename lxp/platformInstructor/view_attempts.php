<?php
/**
 * File: lxp/platformInstructor/view_attempts.php
 * Purpose: Instructor attempts list (PHP 5.4 compatible)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$session_user = $_SESSION['phx_user_login'];

/* Simple instructor permission check placeholder - replace with real check */
$allowed_instructors = true;
if (!$allowed_instructors) {
    http_response_code(403);
    die("Access denied.");
}

/* -----------------------
   Helper: dynamic bind for PHP 5.4 (uses references)
   ----------------------- */
function bindParamsDynamic($stmt, $types, $params)
{
    if (empty($params)) {
        // If there are no params to bind but types provided, do nothing.
        // bind_param requires at least one var; handled by caller.
        return true;
    }

    // call_user_func_array requires references
    $refs = array();
    foreach ($params as $key => $value) {
        $refs[$key] = &$params[$key];
    }
    array_unshift($refs, $types);
    return call_user_func_array(array($stmt, 'bind_param'), $refs);
}

/* -----------------------
   Inputs / Filters
   ----------------------- */
$q                 = isset($_GET['q']) ? trim($_GET['q']) : '';
$status            = isset($_GET['status']) ? trim($_GET['status']) : '';
$variant_id        = isset($_GET['variant_id']) ? (int) $_GET['variant_id'] : 0;
$filter_user       = isset($_GET['user_login']) ? trim($_GET['user_login']) : '';
$problem_type_slug = isset($_GET['problem_type_slug']) ? trim($_GET['problem_type_slug']) : '';

$page     = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$per_page = 25;
$offset   = ($page - 1) * $per_page;
$export   = isset($_GET['export']) && $_GET['export'] == '1';

/* -----------------------
   Build WHERE and JOIN dynamically
   ----------------------- */
$where_clauses = array();
$params = array();
$types = '';

$join_sql = ''; // will include JOINs if needed

if ($variant_id > 0) {
    $where_clauses[] = 'pa.problem_variant_id = ?';
    $params[] = $variant_id; $types .= 'i';
}

if ($filter_user !== '') {
    $where_clauses[] = 'pa.user_login = ?';
    $params[] = $filter_user; $types .= 's';
}

if ($status !== '') {
    $where_clauses[] = 'pa.status = ?';
    $params[] = $status; $types .= 's';
}

if ($q !== '') {
    $where_clauses[] = '(pa.user_login LIKE ? OR CAST(pa.id AS CHAR) = ? OR pa.answer_text LIKE ?)';
    $like = '%' . $q . '%';
    $params[] = $like; $params[] = $q; $params[] = $like; $types .= 'sss';
}

if ($problem_type_slug !== '') {
    // Need to join through variants → types and filter by slug
    $join_sql = ' INNER JOIN problem_variants pv ON pa.problem_variant_id = pv.id INNER JOIN problem_types pt ON pv.problem_type_id = pt.id ';
    $where_clauses[] = 'pt.slug = ?';
    $params[] = $problem_type_slug; $types .= 's';
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = ' WHERE ' . implode(' AND ', $where_clauses);
}

/* -----------------------
   Count total rows for pagination
   ----------------------- */
$total = 0;
$count_sql = "SELECT COUNT(pa.id) AS cnt FROM problem_attempts pa " . $join_sql . $where_sql;
$stmt = $coni->prepare($count_sql);
if ($stmt) {
    if (!empty($params)) {
        bindParamsDynamic($stmt, $types, $params);
    }
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $total = isset($row['cnt']) ? (int) $row['cnt'] : 0;
    } else {
        error_log("Count execute error: " . $stmt->error);
    }
    $stmt->close();
} else {
    error_log("Count prepare error: " . $coni->error . " SQL: " . $count_sql);
}

/* -----------------------
   CSV export (if requested)
   ----------------------- */
if ($export) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=attempts_export_' . date('Ymd_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, array('id','user_login','problem_variant_id','started_at','last_saved_at','submitted_at','status','score','file_path','feedback'));

    $export_sql = "SELECT pa.id,pa.user_login,pa.problem_variant_id,pa.started_at,pa.last_saved_at,pa.submitted_at,pa.status,pa.score,pa.file_path,pa.feedback FROM problem_attempts pa " . $join_sql . $where_sql . " ORDER BY pa.id DESC";
    $stmt = $coni->prepare($export_sql);
    if ($stmt) {
        if (!empty($params)) {
            bindParamsDynamic($stmt, $types, $params);
        }
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            while ($r = $res->fetch_assoc()) {
                $r['started_at']   = (isset($r['started_at']) && is_numeric($r['started_at'])) ? date('c', (int)$r['started_at']) : $r['started_at'];
                $r['last_saved_at'] = (isset($r['last_saved_at']) && is_numeric($r['last_saved_at'])) ? date('c', (int)$r['last_saved_at']) : $r['last_saved_at'];
                $r['submitted_at'] = (isset($r['submitted_at']) && is_numeric($r['submitted_at'])) ? date('c', (int)$r['submitted_at']) : $r['submitted_at'];
                fputcsv($out, array($r['id'],$r['user_login'],$r['problem_variant_id'],$r['started_at'],$r['last_saved_at'],$r['submitted_at'],$r['status'],$r['score'],$r['file_path'],$r['feedback']));
            }
        } else {
            error_log("CSV execute error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        error_log("CSV prepare error: " . $coni->error . " SQL: " . $export_sql);
    }
    fclose($out);
    exit;
}

/* -----------------------
   Fetch paginated attempt rows
   ----------------------- */
$list_sql = "SELECT pa.id,pa.user_login,pa.problem_variant_id,pa.started_at,pa.last_saved_at,pa.submitted_at,pa.status,pa.score,pa.file_path FROM problem_attempts pa " . $join_sql . $where_sql . " ORDER BY pa.id DESC LIMIT ? OFFSET ?";
$stmt = $coni->prepare($list_sql);
$rows = array();

if ($stmt) {
    // bind params plus limit/offset
    if (!empty($params)) {
        $all_params = $params;
        // append per_page and offset
        $all_params[] = $per_page;
        $all_params[] = $offset;
        $all_types = $types . 'ii';
        bindParamsDynamic($stmt, $all_types, $all_params);
    } else {
        // only limit/offset
        bindParamsDynamic($stmt, 'ii', array($per_page, $offset));
    }

    if ($stmt->execute()) {
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
    } else {
        error_log("List execute error: " . $stmt->error);
    }
    $stmt->close();
} else {
    error_log("List prepare error: " . $coni->error . " SQL: " . $list_sql);
}

/* -----------------------
   Pagination meta
   ----------------------- */
$total_pages = ($per_page > 0) ? (int) ceil($total / $per_page) : 1;

/* -----------------------
   Render page (head/nav assumed to exist)
   ----------------------- */
$page_title = "Instructor — Attempts";
require_once('instructorHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="card">
  <div class="card-body">
    <h4 class="card-title"><?php echo htmlspecialchars($page_title); ?></h4>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3"><input type="text" name="q" class="form-control" placeholder="Search user, id, or text" value="<?php echo htmlspecialchars($q); ?>"></div>
        <div class="col-md-2"><input type="text" name="user_login" class="form-control" placeholder="User login" value="<?php echo htmlspecialchars($filter_user); ?>"></div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Any status</option>
                <option value="draft" <?php if ($status === 'draft') echo 'selected'; ?>>Draft</option>
                <option value="submitted" <?php if ($status === 'submitted') echo 'selected'; ?>>Submitted</option>
                <option value="graded" <?php if ($status === 'graded') echo 'selected'; ?>>Graded</option>
            </select>
        </div>
        <div class="col-md-2"><input type="number" name="variant_id" class="form-control" placeholder="Variant ID" value="<?php echo $variant_id ? (int)$variant_id : ''; ?>"></div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a class="btn btn-outline-secondary" href="?<?php $qs = $_GET; $qs['export'] = '1'; echo htmlspecialchars(http_build_query($qs)); ?>">Export CSV</a>
        </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr><th>ID</th><th>User</th><th>Variant</th><th>Started</th><th>Submitted</th><th>Status</th><th>Score</th><th>File</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (count($rows) === 0): ?>
            <tr><td colspan="9" class="text-center">No attempts found.</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $r): 
                $file_link = (!empty($r['file_path'])) ? '../../uploads/attempts/' . rawurlencode($r['file_path']) : '';
            ?>
            <tr>
              <td><?php echo (int)$r['id']; ?></td>
              <td><?php echo htmlspecialchars($r['user_login']); ?></td>
              <td><?php echo (int)$r['problem_variant_id']; ?></td>
              <td><?php echo (!empty($r['started_at']) && is_numeric($r['started_at'])) ? date('Y-m-d H:i', (int)$r['started_at']) : ''; ?></td>
              <td><?php echo (!empty($r['submitted_at']) && is_numeric($r['submitted_at'])) ? date('Y-m-d H:i', (int)$r['submitted_at']) : ''; ?></td>
              <td><?php echo htmlspecialchars($r['status']); ?></td>
              <td><?php echo ($r['score'] !== null) ? htmlspecialchars($r['score']) : '-'; ?></td>
              <td><?php echo $file_link ? '<a target="_blank" rel="noopener" href="'.htmlspecialchars($file_link).'">Download</a>' : '-'; ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="attempt.php?variant_id=<?php echo (int)$r['problem_variant_id']; ?>&attempt_id=<?php echo (int)$r['id']; ?>">View</a>
                <a class="btn btn-sm btn-outline-success" href="grade_attempt.php?attempt_id=<?php echo (int)$r['id']; ?>">Grade</a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <nav aria-label="Pagination">
      <ul class="pagination">
      <?php $base = $_GET; unset($base['page']); for ($p = 1; $p <= max(1, $total_pages); $p++): $base['page'] = $p; $link = '?' . htmlspecialchars(http_build_query($base)); ?>
        <li class="page-item <?php if ($p == $page) echo 'active'; ?>"><a class="page-link" href="<?php echo $link; ?>"><?php echo $p; ?></a></li>
      <?php endfor; ?>
      </ul>
    </nav>

    <div class="small text-muted mt-2">Showing <?php echo count($rows); ?> of <?php echo $total; ?> attempts.</div>

  </div>
</div>

</div><!-- container -->
</div><!-- content-wrapper -->

<?php require_once('../platformFooter.php'); ?>
</div><!-- layout-page -->
