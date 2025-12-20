<?php
// File: learners/problem_solving.php
// PHP 5.4 + MySQL 5.x compatible
// Shows problem variants for a given type slug; level selected on-page.
// Robust matching and inline LIMIT (works on shared hosts).

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}
$user_login = $_SESSION['phx_user_login'];

/* ---------------- INPUTS ---------------- */
$type_slug = isset($_GET['type']) ? trim($_GET['type']) : '';
$level     = isset($_GET['level']) ? trim($_GET['level']) : ''; // optional, selected on page
$d_raw     = isset($_GET['difficulty']) ? trim($_GET['difficulty']) : '0';
$page      = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page  = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;

if ($page < 1) $page = 1;
if ($per_page < 1) $per_page = 10;
if ($per_page > 100) $per_page = 100;

/* type required */
if ($type_slug === '') {
    die("Missing required parameter: type (slug).");
}

/* validate level if provided */
$validLevels = array('kid','teen','adult');
if ($level !== '' && !in_array($level, $validLevels, true)) {
    $level = '';
}

/* normalize difficulty */
$allowed_text = array('novice','beginner','intermediate','advance','expert','advanced');
$numeric_to_text = array('1'=>'novice','2'=>'beginner','3'=>'intermediate','4'=>'advance','5'=>'expert');

if ($d_raw === '' || $d_raw === '0') {
    $d_filter = '0';
} elseif (array_key_exists($d_raw, $numeric_to_text)) {
    $d_filter = $numeric_to_text[$d_raw];
} elseif (in_array(strtolower($d_raw), $allowed_text, true)) {
    $d_filter = strtolower($d_raw);
} else {
    $d_filter = '0';
}

/* ---------------- FETCH problem_type (prepared) ---------------- */
$ptype_id = 0;
$ptype = null;
if ($stmt = $coni->prepare("SELECT id, title FROM problem_types WHERE slug = ? AND active = 1 LIMIT 1")) {
    $stmt->bind_param("s", $type_slug);
    $stmt->execute();
    $res = method_exists($stmt, 'get_result') ? $stmt->get_result() : null;
    if ($res) {
        $ptype = $res->fetch_assoc();
    } else {
        // fallback
        $stmt->bind_result($tmp_id, $tmp_title);
        if ($stmt->fetch()) {
            $ptype = array('id' => $tmp_id, 'title' => $tmp_title);
        }
    }
    $stmt->close();
}
if (! $ptype || ! isset($ptype['id'])) {
    die("Invalid problem type.");
}
$ptype_id = (int)$ptype['id'];

/* ---------------- PAGE HEADER ---------------- */
$page = "problemSolving";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>
<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<!-- PAGE HEADER -->
<div class="row">
  <div class="col-lg-12 mb-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title mb-3">
          <i class="fa fa-lightbulb text-warning"></i>
          <?php echo htmlspecialchars($ptype['title']); ?>
        </h4>

        <!-- Filters -->
        <form method="GET" class="mb-3">
          <input type="hidden" name="type" value="<?php echo htmlspecialchars($type_slug); ?>" />

          <label class="form-label me-2">Age / Level</label>
          <select name="level" class="form-select d-inline-block me-3" style="width:auto" onchange="this.form.submit()">
            <option value="">-- Select Age --</option>
            <option value="kid" <?php if ($level === 'kid') echo 'selected'; ?>>Kids</option>
            <option value="teen" <?php if ($level === 'teen') echo 'selected'; ?>>Teen</option>
            <option value="adult" <?php if ($level === 'adult') echo 'selected'; ?>>Adult</option>
          </select>

          <?php if ($level !== ''): ?>
            <label class="form-label me-2">Difficulty</label>
            <select name="difficulty" class="form-select d-inline-block me-3" style="width:auto" onchange="this.form.submit()">
              <option value="0" <?php if ($d_filter === '0') echo 'selected'; ?>>-- All Difficulties --</option>
              <option value="novice" <?php if ($d_filter === 'novice') echo 'selected'; ?>>Novice</option>
              <option value="beginner" <?php if ($d_filter === 'beginner') echo 'selected'; ?>>Beginner</option>
              <option value="intermediate" <?php if ($d_filter === 'intermediate') echo 'selected'; ?>>Intermediate</option>
              <option value="advance" <?php if ($d_filter === 'advance') echo 'selected'; ?>>Advanced</option>
              <option value="expert" <?php if ($d_filter === 'expert') echo 'selected'; ?>>Expert</option>
            </select>

            <label class="form-label me-2">Per page</label>
            <select name="per_page" class="form-select d-inline-block" style="width:90px" onchange="this.form.submit()">
              <option value="5" <?php if ($per_page==5) echo 'selected'; ?>>5</option>
              <option value="10" <?php if ($per_page==10) echo 'selected'; ?>>10</option>
              <option value="25" <?php if ($per_page==25) echo 'selected'; ?>>25</option>
            </select>
          <?php endif; ?>
        </form>
</div>
</div>
</div>
</div>


        <?php
        if ($level === '') {
            echo '<div class="alert alert-info d-flex align-items-center"><i class="fa fa-info-circle me-2"></i>Please select an age group to view problems.</div>';
            require_once('../platformFooter.php');
            exit;
        }

        /* ---------------- BUILD WHERE for COUNT ---------------- */
        $where_sql = " WHERE problem_type_id = ? AND LOWER(TRIM(level)) = LOWER(TRIM(?)) ";
        $bind_types = "is";
        $bind_vals = array($ptype_id, $level);

        if ($d_filter !== '0') {
            $where_sql .= " AND (LOWER(TRIM(difficulty_score)) = LOWER(TRIM(?)) OR LOWER(TRIM(problem_slug)) LIKE CONCAT('%-', LOWER(TRIM(?)))) ";
            $bind_types .= "ss";
            $bind_vals[] = $d_filter;
            $bind_vals[] = $d_filter;
        }

        /* COUNT (prepared) */
        $total_count = 0;
        $count_sql = "SELECT COUNT(*) AS cnt FROM problem_variants " . $where_sql;
        if ($stmt = $coni->prepare($count_sql)) {
            $bind_params = array();
            $bind_params[] = & $bind_types;
            for ($i=0; $i<count($bind_vals); $i++) $bind_params[] = & $bind_vals[$i];
            call_user_func_array(array($stmt,'bind_param'), $bind_params);
            $stmt->execute();
            // fetch count robustly
            if (method_exists($stmt, 'get_result')) {
                $gres = $stmt->get_result();
                if ($gres) {
                    $r = $gres->fetch_assoc();
                    if ($r && isset($r['cnt'])) $total_count = (int)$r['cnt'];
                }
            } else {
                $stmt->bind_result($tmp_cnt);
                if ($stmt->fetch()) $total_count = (int)$tmp_cnt;
            }
            $stmt->close();
        } else {
            echo '<div class="alert alert-danger">Count query failed: ' . htmlspecialchars($coni->error) . '</div>';
        }

        $total_pages = ($total_count > 0) ? (int) ceil($total_count / $per_page) : 1;
        if ($page > $total_pages) $page = $total_pages;
        $offset = ($page - 1) * $per_page;
        if ($offset < 0) $offset = 0;

        /* ---------------- SELECT with INLINE LIMIT (required on PHP5.4 shared hosts) ---------------- */
        // build select SQL (note: LIMIT uses literal ints, not bound params)
        $ofs = (int)$offset;
        $pp  = (int)$per_page;

        $sqlVar = "
            SELECT id, problem_slug, statement, expected_outcome, difficulty_score
            FROM problem_variants
            {$where_sql}
            ORDER BY FIELD(difficulty_score,'novice','beginner','intermediate','advance','expert') ASC
            LIMIT {$ofs}, {$pp}
        ";

        $variants = array();

        if ($stmt = $coni->prepare($sqlVar)) {
            // bind only WHERE params (LIMIT is inlined)
            $bind_all = array();
            $bind_all[] = & $bind_types;
            for ($i=0; $i<count($bind_vals); $i++) $bind_all[] = & $bind_vals[$i];
            if (count($bind_vals) > 0) {
                call_user_func_array(array($stmt, 'bind_param'), $bind_all);
            }
            $exe = $stmt->execute();
            if (! $exe) {
                echo '<div class="alert alert-danger">Variants execute failed: ' . htmlspecialchars($stmt->error) . '</div>';
            } else {
                // fetch robustly
                if (method_exists($stmt, 'get_result')) {
                    $gres = $stmt->get_result();
                    if ($gres) {
                        while ($row = $gres->fetch_assoc()) $variants[] = $row;
                    }
                } else {
                    // fallback: bind_result
                    // columns: id, problem_slug, statement, expected_outcome, difficulty_score
                    $stmt->store_result();
                    if ($stmt->bind_result($col_id, $col_slug, $col_statement, $col_expected, $col_diff)) {
                        while ($stmt->fetch()) {
                            $variants[] = array(
                                'id' => $col_id,
                                'problem_slug' => $col_slug,
                                'statement' => $col_statement,
                                'expected_outcome' => $col_expected,
                                'difficulty_score' => $col_diff
                            );
                        }
                    } else {
                        echo '<div class="alert alert-danger">Variants bind_result failed: ' . htmlspecialchars($stmt->error) . '</div>';
                    }
                }
            }
            $stmt->close();
        } else {
            echo '<div class="alert alert-danger">Variants prepare failed: ' . htmlspecialchars($coni->error) . '</div>';
        }

        /* helpers to display normalized difficulty label & badge */
        function num_to_text($n) {
            $map = array('1'=>'novice','2'=>'beginner','3'=>'intermediate','4'=>'advance','5'=>'expert');
            return isset($map[$n]) ? $map[$n] : null;
        }
        function suffix_from_slug($slug) {
            $slug = trim($slug);
            if ($slug === '') return null;
            $parts = explode('-', $slug);
            return strtolower(trim(array_pop($parts)));
        }
        function pretty_label($v) {
            $names = array('novice'=>'Novice','beginner'=>'Beginner','intermediate'=>'Intermediate','advance'=>'Advanced','expert'=>'Expert');
            $raw = isset($v['difficulty_score']) ? trim($v['difficulty_score']) : '';
            $low = strtolower($raw);
            if (ctype_digit($low) && ($m = num_to_text($low))) return isset($names[$m]) ? $names[$m] : ucfirst($m);
            if (isset($names[$low])) return $names[$low];
            if (!empty($v['problem_slug'])) {
                $s = suffix_from_slug($v['problem_slug']);
                if ($s && isset($names[$s])) return $names[$s];
                if ($s) return ucfirst($s);
            }
            return ($raw !== '') ? ucfirst($raw) : 'Unknown';
        }
        function badge_class($v) {
            $map = array('novice'=>'bg-primary','beginner'=>'bg-info','intermediate'=>'bg-warning text-dark','advance'=>'bg-danger','expert'=>'bg-dark');
            $raw = isset($v['difficulty_score']) ? trim($v['difficulty_score']) : '';
            $low = strtolower($raw);
            if (ctype_digit($low) && ($m = num_to_text($low))) {
                if (isset($map[$m])) return $map[$m];
            }
            if (isset($map[$low])) return $map[$low];
            if (!empty($v['problem_slug'])) {
                $s = suffix_from_slug($v['problem_slug']);
                if ($s && isset($map[$s])) return $map[$s];
            }
            return 'bg-secondary';
        }
        ?>

<!-- Questions HEADER -->
<div class="row">
  <div class="col-lg-12 mb-4">
    <div class="card shadow-sm">
      <div class="card-body">

        <!-- Render results -->
        <?php if (empty($variants)): ?>
            <div class="alert alert-warning d-flex align-items-center">
                <i class="fa fa-exclamation-triangle me-2"></i>
                No problems found for this selection.
            </div>
        <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
                <thead class="table-primary text-center">
                  <tr>
                    <th>Difficulty</th>
                    <th>Slug</th>
                    <th class="text-start">Statement</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($variants as $v): 
                    $lbl = pretty_label($v);
                    $bcls = badge_class($v);
                  ?>
                    <tr class="align-middle text-center">
                      <td><span class="badge <?php echo $bcls; ?>"><?php echo htmlspecialchars($lbl); ?></span></td>
                      <td><?php echo htmlspecialchars($v['problem_slug']); ?></td>
                      <td class="text-start"><?php echo nl2br(htmlspecialchars($v['statement'])); ?></td>
                      <td>
                        <a href="attempt.php?variant_id=<?php echo (int)$v['id']; ?>" class="btn btn-sm btn-success">
                          <i class="fa fa-arrow-circle-right"></i> Attempt
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <?php
            $base_params = array('type'=>$type_slug,'level'=>$level,'difficulty'=>$d_filter,'per_page'=>$per_page);
            ?>
            <nav class="mt-3">
              <ul class="pagination">
                <?php
                $prev = max(1, $page-1);
                $u = $_SERVER['PHP_SELF'].'?'.http_build_query($base_params + array('page'=>$prev));
                ?>
                <li class="page-item <?php if ($page<=1) echo 'disabled'; ?>"><a class="page-link" href="<?php echo htmlspecialchars($u); ?>">Prev</a></li>

                <?php
                $win = 2;
                $start = max(1,$page-$win);
                $end = min($total_pages,$page+$win);
                if ($start > 1) {
                    $u1 = $_SERVER['PHP_SELF'].'?'.http_build_query($base_params + array('page'=>1));
                    echo '<li class="page-item"><a class="page-link" href="'.htmlspecialchars($u1).'">1</a></li>';
                    if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
                for ($i = $start; $i <= $end; $i++) {
                    $ui = $_SERVER['PHP_SELF'].'?'.http_build_query($base_params + array('page'=>$i));
                    $active = ($i == $page) ? ' active' : '';
                    echo '<li class="page-item'.$active.'"><a class="page-link" href="'.htmlspecialchars($ui).'">'.$i.'</a></li>';
                }
                if ($end < $total_pages) {
                    if ($end < $total_pages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                    $ul = $_SERVER['PHP_SELF'].'?'.http_build_query($base_params + array('page'=>$total_pages));
                    echo '<li class="page-item"><a class="page-link" href="'.htmlspecialchars($ul).'">'.$total_pages.'</a></li>';
                }
                $next = min($total_pages, $page + 1);
                $un = $_SERVER['PHP_SELF'].'?'.http_build_query($base_params + array('page'=>$next));
                ?>
                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>"><a class="page-link" href="<?php echo htmlspecialchars($un); ?>">Next</a></li>
              </ul>
              <div class="small text-muted">Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $per_page, $total_count); ?> of <?php echo $total_count; ?> problems</div>
            </nav>
        <?php endif; ?>

    

<?php
/* telemetry */
$payload = json_encode(array('type'=>$type_slug,'level'=>$level,'difficulty'=>$d_filter,'page'=>$page,'per_page'=>$per_page));
if ($stmt = $coni->prepare("INSERT INTO problem_telemetry (user_login, event_type, payload, occurred_at) VALUES (?, 'problem_listing_view', ?, UNIX_TIMESTAMP())")) {
    $stmt->bind_param("ss", $user_login, $payload);
    $stmt->execute();
    $stmt->close();
}
ob_end_flush();
?>


</div>
</div>

  <?php require_once('../platformFooter.php'); ?>