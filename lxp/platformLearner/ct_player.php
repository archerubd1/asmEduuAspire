<?php
/**
 * ct_player.php - patched
 * - AJAX submit + autoscore with rubric dimensions (clarity/reasoning/creativity)
 * - Fetch latest per-statement via AJAX when switching statements
 * - PHP 5.4 + MySQL 5.x compatible (mysqli)
 *
 * Ready-to-drop replacement for your existing ct_player.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}
$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function now_datetime() { return date('Y-m-d H:i:s'); }

/* DB connection: prefer $coni */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

if (!($mysqli instanceof mysqli)) { echo "<h2>Database connection missing.</h2>"; exit; }

/* config */
$MAX_ATTEMPTS_PER_STATEMENT = 5;

/* get type param */
$type_raw = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
if ($type_raw === '') { echo "<h2>Missing assignment type.</h2><p>Open the catalogue and click Attempt on an assignment.</p>"; exit; }

/* find assignment */
$assignment = null;
$sql = "SELECT id, title, description FROM ct_assignments WHERE id = ? LIMIT 1";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('s', $type_raw);
    $stmt->execute();
    $stmt->bind_result($a_id, $a_title, $a_description);
    if ($stmt->fetch()) $assignment = array('id'=>$a_id,'title'=>$a_title,'description'=>$a_description);
    $stmt->close();
}
if (!$assignment) {
    $like = '%'.$type_raw.'%';
    $sql = "SELECT id, title, description FROM ct_assignments WHERE UPPER(title) LIKE UPPER(?) LIMIT 1";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $like);
        $stmt->execute();
        $stmt->bind_result($a_id, $a_title, $a_description);
        if ($stmt->fetch()) $assignment = array('id'=>$a_id,'title'=>$a_title,'description'=>$a_description);
        $stmt->close();
    }
}
if (!$assignment && ctype_digit($type_raw)) {
    $num = (int)$type_raw;
    $sql = "SELECT id, title, description FROM ct_assignments WHERE CAST(id AS SIGNED) = ? LIMIT 1";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $num);
        $stmt->execute();
        $stmt->bind_result($a_id, $a_title, $a_description);
        if ($stmt->fetch()) $assignment = array('id'=>$a_id,'title'=>$a_title,'description'=>$a_description);
        $stmt->close();
    }
}
if (!$assignment) { echo "<h2>Assignment not found for type: ".h($type_raw)."</h2>"; exit; }
$assignment_key = $assignment['id'];

/* load first active subassignment */
$sub = null;
$sql = "SELECT id, assignment_id, title, instructions, response_type, max_attempts, default_weights, rubric_template FROM ct_subassignments WHERE assignment_id = ? AND is_active = 1 ORDER BY id ASC LIMIT 1";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('s', $assignment_key);
    $stmt->execute();
    $stmt->bind_result($s_id, $s_assignment_id, $s_title, $s_instructions, $s_response_type, $s_max_attempts, $s_default_weights, $s_rubric_template);
    if ($stmt->fetch()) {
        $sub = array(
            'id'=>$s_id,'assignment_id'=>$s_assignment_id,'title'=>$s_title,
            'instructions'=>$s_instructions,'response_type'=>$s_response_type,
            'max_attempts'=>$s_max_attempts,'default_weights'=>$s_default_weights,'rubric_template'=>$s_rubric_template
        );
    }
    $stmt->close();
}
if (!$sub) { echo "<h2>No active tasks available for '".h($assignment['title'])."'.</h2><p>Please contact your instructor.</p>"; exit; }

/* load statements grouped by level */
$level_statements = array('kid'=>array(),'teen'=>array(),'adult'=>array());
$sql = "SELECT id, level, statement, sort_order FROM ct_statements WHERE subassignment_id = ? AND is_active = 1 ORDER BY level, sort_order ASC, id ASC";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('s', $sub['id']);
    $stmt->execute();
    $stmt->bind_result($st_id, $st_level, $st_statement, $st_sort);
    while ($stmt->fetch()) {
        if (!isset($level_statements[$st_level])) $level_statements[$st_level] = array();
        $level_statements[$st_level][] = array('id'=>$st_id,'statement'=>$st_statement,'sort'=>$st_sort);
    }
    $stmt->close();
}
foreach (array('kid','teen','adult') as $lvl) {
    if (empty($level_statements[$lvl])) $level_statements[$lvl][] = array('id'=>null,'statement'=>$sub['instructions'],'sort'=>0);
}

/* latest submission (most recent overall for display) */
$latest_submission = null;
$sql = "SELECT id, content_text, computed_score, auto_score, final_score, is_overridden, instructor_notes, created_at, level, statement_id, signals FROM ct_submissions WHERE user_id = ? AND subassignment_id = ? ORDER BY id DESC LIMIT 1";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('is', $phx_user_id, $sub['id']);
    $stmt->execute();
    $stmt->bind_result($subm_id, $subm_content, $subm_comp, $subm_auto, $subm_final, $subm_over, $subm_instructor_notes, $subm_created, $subm_level, $subm_statement_id, $subm_signals);
    if ($stmt->fetch()) {
        $latest_submission = array(
            'id'=>$subm_id,'content'=>$subm_content,'computed_score'=>$subm_comp,
            'auto_score'=>$subm_auto,'final_score'=>$subm_final,'is_overridden'=>$subm_over,
            'instructor_notes'=>$subm_instructor_notes,'created_at'=>$subm_created,'level'=>$subm_level,'statement_id'=>$subm_statement_id,'signals'=>$subm_signals
        );
    }
    $stmt->close();
}

/* progress */
$progress = null;
$sql = "SELECT user_id, subassignment_id, is_completed, attempts, last_attempted_at FROM ct_progress WHERE user_id = ? AND subassignment_id = ? LIMIT 1";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('is', $phx_user_id, $sub['id']);
    $stmt->execute();
    $stmt->bind_result($p_user_id, $p_subid, $p_is_completed, $p_attempts, $p_last_attempted_at);
    if ($stmt->fetch()) $progress = array('user_id'=>$p_user_id,'subassignment_id'=>$p_subid,'is_completed'=>$p_is_completed,'attempts'=>$p_attempts,'last_attempted_at'=>$p_last_attempted_at);
    $stmt->close();
}

/* learner info */
$learner = array('login'=>$phx_user_login,'name'=>'','surname'=>'');
$sql = "SELECT login, name, surname FROM users WHERE id = ? LIMIT 1";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('i', $phx_user_id);
    $stmt->execute();
    $stmt->bind_result($u_login, $u_name, $u_surname);
    if ($stmt->fetch()) $learner = array('login'=>$u_login,'name'=>$u_name,'surname'=>$u_surname);
    $stmt->close();
}

/* --- autoscore function (same as your robust version) --- */
function compute_autoscore_and_signals($text) {
    $signals = array();
    $text = trim($text);
    $signals['char_count'] = mb_strlen($text,'UTF-8');
    $norm = preg_replace('/\s+/u', ' ', $text);
    $norm = trim($norm);
    $words = ($norm === '') ? array() : preg_split('/\s+/u', $norm);
    $word_count = count($words);
    $signals['word_count'] = $word_count;
    $sentences = preg_split('/[\.!?]+[\s]*/u', $norm, -1, PREG_SPLIT_NO_EMPTY);
    $signals['sentence_count'] = count($sentences);
    $lower_words = array();
    foreach ($words as $w) {
        $clean = preg_replace('/[^\p{L}\p{N}\'\-]/u', '', $w);
        if ($clean === '') continue;
        $lower_words[] = mb_strtolower($clean,'UTF-8');
    }
    $unique = count(array_unique($lower_words));
    $signals['unique_words'] = $unique;
    $signals['unique_ratio'] = ($word_count>0 ? round($unique / $word_count, 3) : 0);
    $avg_word_len = 0.0;
    if ($word_count > 0) {
        $total_letters = 0;
        foreach ($words as $w) $total_letters += mb_strlen(preg_replace('/[^\p{L}]/u','',$w),'UTF-8');
        $avg_word_len = ($word_count > 0 ? $total_letters / $word_count : 0);
    }
    $signals['avg_word_len'] = round($avg_word_len,3);
    $max_run = 0;
    if ($text !== '') {
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $run = 1;
        for ($i=1;$i<count($chars);$i++) {
            if ($chars[$i] === $chars[$i-1]) { $run++; if ($run > $max_run) $max_run = $run; }
            else $run = 1;
        }
    }
    $signals['max_char_run'] = $max_run;
    $vowels = preg_match_all('/[aeiouAEIOU]/u', $text);
    $consonants = preg_match_all('/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]/u', $text);
    $signals['vowels'] = intval($vowels);
    $signals['consonants'] = intval($consonants);
    $signals['vowel_ratio'] = ($vowels + $consonants > 0 ? round($vowels / max(1, ($vowels+$consonants)), 3) : 0);
    $profane_list = array('fuck','shit','bitch','asshole','damn','crap');
    $lower_text = mb_strtolower($text,'UTF-8');
    $profane_found = array();
    foreach ($profane_list as $pw) if (strpos($lower_text,$pw) !== false) $profane_found[] = $pw;
    $signals['profane'] = $profane_found;
    $clarity = 0;
    if ($word_count == 0) $clarity = 5;
    else {
        $clarity = min(100, (int) round( (min(1, $signals['unique_ratio']) * 40) + (min(1, $signals['sentence_count']/3) * 30) + (min(1, $signals['avg_word_len']/5) * 30) ));
    }
    $reasoning = 0;
    if ($word_count == 0) $reasoning = 5;
    else {
        $reasoning = min(100, (int) round( (min(1, $word_count/180) * 50) + (min(1, $signals['sentence_count']/4) * 30) + (min(1, $signals['unique_ratio']) * 20) ));
    }
    $creativity = min(100, (int) round( (min(1, $signals['unique_ratio']) * 60) + (min(1, $signals['avg_word_len']/6) * 20) + (min(1, $signals['sentence_count']/3) * 20) ));
    $signals['dimensions'] = array('clarity'=>$clarity,'reasoning'=>$reasoning,'creativity'=>$creativity);
    $auto_score = round(($clarity * 0.30) + ($reasoning * 0.50) + ($creativity * 0.20), 2);
    $flag_reasons = array();
    $flagged = 0;
    if ($max_run >= 5) { $auto_score -= 30; $flag_reasons[] = 'gibberish'; $signals['gibberish_flag'] = 1; $flagged = 1; }
    if (!empty($profane_found)) { $auto_score -= 20; $flag_reasons[] = 'profanity'; $signals['profanity'] = $profane_found; $flagged = 1; }
    if ($auto_score < 0) $auto_score = 0;
    $auto_score = round($auto_score,2);
    if ($auto_score < 35) { $flagged = 1; $flag_reasons[] = 'low_score'; }
    return array('score'=>$auto_score,'signals'=>$signals,'flagged'=>$flagged,'reasons'=>$flag_reasons);
}

/* dynamic bind helper for mysqli (PHP 5.4) */
function mysqli_bind_dynamic($stmt, $params) {
    if (!is_array($params) || !($stmt instanceof mysqli_stmt)) return false;
    $types = '';
    foreach ($params as $p) {
        if (is_int($p)) $types .= 'i';
        elseif (is_float($p) || is_double($p)) $types .= 'd';
        else $types .= 's';
    }
    $refs = array();
    $refs[] = & $types;
    foreach ($params as $k => $v) {
        $refs[] = & $params[$k];
    }
    return call_user_func_array(array($stmt, 'bind_param'), $refs);
}

/* ----------------- AJAX: fetch_latest endpoint ----------------- */
if (isset($_POST['ajax']) && $_POST['ajax'] == '1' && isset($_POST['action']) && $_POST['action'] === 'fetch_latest') {
    // return latest submission for user/subassignment/statement
    @ini_set('display_errors', 0);
    while (ob_get_level() > 0) { @ob_end_clean(); }
    header('Content-Type: application/json; charset=utf-8');

    $stmt_id = isset($_POST['statement_id']) && $_POST['statement_id'] !== '' ? $_POST['statement_id'] : null;
    $level = isset($_POST['level']) ? $_POST['level'] : 'adult';

    $latest = null;
    if ($stmt_id !== null && is_numeric($stmt_id)) {
        $q = "SELECT id, content_text, computed_score, auto_score, final_score, is_overridden, instructor_notes, signals, created_at, level, statement_id FROM ct_submissions WHERE user_id = ? AND subassignment_id = ? AND statement_id = ? ORDER BY id DESC LIMIT 1";
        if ($s = $mysqli->prepare($q)) {
            $s->bind_param('isi', $phx_user_id, $sub['id'], $stmt_id);
            $s->execute();
            $s->bind_result($sid,$scontent,$scomp,$sauto,$sfinal,$sover,$snotes,$ssignals,$screated,$slevel,$sstatementid);
            if ($s->fetch()) {
                $latest = array('id'=>$sid,'content'=>$scontent,'computed_score'=>$scomp,'auto_score'=>$sauto,'final_score'=>$sfinal,'is_overridden'=>$sover,'instructor_notes'=>$snotes,'signals'=>$ssignals,'created_at'=>$screated,'level'=>$slevel,'statement_id'=>$sstatementid);
            }
            $s->close();
        }
        // count attempts for this statement
        $cnt = 0;
        $csql = "SELECT COUNT(*) FROM ct_submissions WHERE user_id = ? AND subassignment_id = ? AND statement_id = ?";
        if ($c = $mysqli->prepare($csql)) {
            $c->bind_param('isi', $phx_user_id, $sub['id'], $stmt_id);
            $c->execute();
            $c->bind_result($cntres);
            if ($c->fetch()) $cnt = (int)$cntres;
            $c->close();
        }
    } else {
        // no statement id -> return nothing
        $cnt = 0;
    }

    echo json_encode(array('success'=>true,'latest'=>$latest,'attempts'=>$cnt));
    exit;
}

/* ----------------- Handle submission POST (ajax submit supported) ----------------- */
$success_msg = '';
$error_msg = '';
$is_ajax = (isset($_POST['ajax']) && $_POST['ajax'] == '1') ? true : false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit') {
    $level = isset($_POST['level']) ? trim($_POST['level']) : 'adult';
    $statement_id_submitted = isset($_POST['statement_id']) && $_POST['statement_id'] !== '' ? $_POST['statement_id'] : null;
    $response_raw = isset($_POST['response']) ? trim($_POST['response']) : '';

    if ($response_raw === '') {
        $error_msg = "Please enter a response before submitting.";
        if ($is_ajax) { @ini_set('display_errors', 0); while (ob_get_level()>0){@ob_end_clean();} header('Content-Type: application/json; charset=utf-8'); echo json_encode(array('success'=>false,'error'=>$error_msg)); exit; }
    }

    // count attempts for this user/subassignment/statement
    $attempts_count = 0;
    if ($statement_id_submitted !== null && $statement_id_submitted !== '') {
        $sql = "SELECT COUNT(*) AS cnt FROM ct_submissions WHERE user_id = ? AND subassignment_id = ? AND statement_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('isi', $phx_user_id, $sub['id'], $statement_id_submitted);
            $stmt->execute();
            $stmt->bind_result($cnt);
            if ($stmt->fetch()) $attempts_count = (int)$cnt;
            $stmt->close();
        }
    } else {
        $sql = "SELECT COUNT(*) AS cnt FROM ct_submissions WHERE user_id = ? AND subassignment_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('is', $phx_user_id, $sub['id']);
            $stmt->execute();
            $stmt->bind_result($cnt);
            if ($stmt->fetch()) $attempts_count = (int)$cnt;
            $stmt->close();
        }
    }

    if ($attempts_count >= $MAX_ATTEMPTS_PER_STATEMENT) {
        $error_msg = "You have reached the maximum allowed attempts (" . intval($MAX_ATTEMPTS_PER_STATEMENT) . ") for this problem statement.";
        if ($is_ajax) { @ini_set('display_errors', 0); while (ob_get_level()>0){@ob_end_clean();} header('Content-Type: application/json; charset=utf-8'); echo json_encode(array('success'=>false,'error'=>$error_msg)); exit; }
    }

    // legacy computed_score placeholder
    $len = function_exists('mb_strlen') ? mb_strlen($response_raw, 'UTF-8') : strlen($response_raw);
    if ($len >= 200) $computed_score = 95.0;
    elseif ($len >= 120) $computed_score = 85.0;
    elseif ($len >= 70)  $computed_score = 75.0;
    elseif ($len >= 30)  $computed_score = 60.0;
    else $computed_score = 40.0;

    $now = now_datetime();
    $attempt_no = $attempts_count + 1;

    $ins_cols = array(
        'user_id','subassignment_id','attempt_no','content_text','content_url','response_type',
        'response_time_seconds','rubric_input','computed_score','auto_score','final_score','instructor_id',
        'instructor_notes','is_overridden','level','statement_id','signals','status','created_at','updated_at'
    );
    $ins_placeholders = array_fill(0, count($ins_cols), '?');
    $ins_sql = "INSERT INTO ct_submissions (" . implode(',', $ins_cols) . ") VALUES (" . implode(',', $ins_placeholders) . ")";

    if ($ins_stmt = $mysqli->prepare($ins_sql)) {
        $params = array();
        $params[] = $phx_user_id;
        $params[] = $sub['id'];
        $params[] = $attempt_no;
        $params[] = $response_raw;
        $params[] = null;
        $params[] = $sub['response_type'];
        $params[] = 0;
        $params[] = null;
        $params[] = (float)$computed_score;
        $params[] = null;
        $params[] = null;
        $params[] = null;
        $params[] = null;
        $params[] = 0;
        $params[] = $level;
        $params[] = (is_numeric($statement_id_submitted) ? (int)$statement_id_submitted : null);
        $params[] = null;
        $params[] = 'submitted';
        $params[] = $now;
        $params[] = $now;

        if (mysqli_bind_dynamic($ins_stmt, $params) === false) {
            $error_msg = "Binding parameters failed.";
            if ($is_ajax) { @ini_set('display_errors', 0); while (ob_get_level()>0){@ob_end_clean();} header('Content-Type: application/json; charset=utf-8'); echo json_encode(array('success'=>false,'error'=>$error_msg)); exit; }
        } else {
            if ($ins_stmt->execute()) {
                $new_submission_id = $ins_stmt->insert_id;

                // Run autoscore
                $autos = compute_autoscore_and_signals($response_raw);
                $auto_score = $autos['score'];
                $signals_json = json_encode($autos['signals']);
                $flagged = $autos['flagged'];
                $flag_reasons = implode(',', $autos['reasons']);

                // Update submission with scores & signals
                $upd_sql = "UPDATE ct_submissions SET auto_score = ?, computed_score = ?, final_score = ?, signals = ?, updated_at = ? WHERE id = ? LIMIT 1";
                if ($upd_stmt = $mysqli->prepare($upd_sql)) {
                    // be careful with types: auto_score (d), computed_score (d), final_score (d), signals (s), updated_at (s), id (i)
                    $final_val = $auto_score;
                    $upd_stmt->bind_param('dddssi', $auto_score, $auto_score, $final_val, $signals_json, $now, $new_submission_id);
                    $upd_stmt->execute();
                    $upd_stmt->close();
                }

                if ($flagged) {
                    $rq_sql = "INSERT INTO ct_review_queue (submission_id, user_id, reason, created_at, status) VALUES (?, ?, ?, ?, 'open')";
                    if ($rq_stmt = $mysqli->prepare($rq_sql)) {
                        $rq_stmt->bind_param('iiss', $new_submission_id, $phx_user_id, $flag_reasons, $now);
                        $rq_stmt->execute();
                        $rq_stmt->close();
                    }
                }

                // update progress
                if ($progress) {
                    $update_sql = "UPDATE ct_progress SET attempts = attempts + 1, last_attempted_at = ?, is_completed = 1 WHERE user_id = ? AND subassignment_id = ?";
                    if ($up = $mysqli->prepare($update_sql)) {
                        $up->bind_param('sis', $now, $phx_user_id, $sub['id']);
                        $up->execute();
                        $up->close();
                    }
                } else {
                    $insert_p = "INSERT INTO ct_progress (user_id, subassignment_id, is_completed, attempts, last_attempted_at) VALUES (?, ?, 1, 1, ?)";
                    if ($ip = $mysqli->prepare($insert_p)) {
                        $ip->bind_param('iss', $phx_user_id, $sub['id'], $now);
                        $ip->execute();
                        $ip->close();
                    }
                }

                // fetch updated row
                $latest = null;
                $sql2 = "SELECT id, content_text, computed_score, auto_score, final_score, is_overridden, instructor_notes, signals, created_at, level, statement_id FROM ct_submissions WHERE id = ? LIMIT 1";
                if ($s2 = $mysqli->prepare($sql2)) {
                    $s2->bind_param('i', $new_submission_id);
                    $s2->execute();
                    $s2->bind_result($sid, $scontent, $scomp, $sauto, $sfinal, $sover, $snotes, $ssignals, $screated, $slevel, $sstatementid);
                    if ($s2->fetch()) {
                        $latest = array(
                            'id'=>$sid,'content'=>$scontent,'computed_score'=>$scomp,'auto_score'=>$sauto,'final_score'=>$sfinal,
                            'is_overridden'=>$sover,'instructor_notes'=>$snotes,'signals'=>$ssignals,'created_at'=>$screated,'level'=>$slevel,'statement_id'=>$sstatementid
                        );
                    }
                    $s2->close();
                }

                if ($is_ajax) {
                    @ini_set('display_errors', 0);
                    while (ob_get_level() > 0) { @ob_end_clean(); }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(array('success'=>true,'submission'=>$latest,'flagged'=>$flagged,'reasons'=>$autos['reasons'],'attempts'=>($attempts_count+1)));
                    exit;
                } else {
                    $success_msg = "Submission saved. Auto-score: " . intval($auto_score);
                    $latest_submission = $latest;
                }

            } else {
                $error_msg = "Failed to save submission: " . h($ins_stmt->error);
                if ($is_ajax) { @ini_set('display_errors', 0); while (ob_get_level()>0){@ob_end_clean();} header('Content-Type: application/json; charset=utf-8'); echo json_encode(array('success'=>false,'error'=>$error_msg)); exit; }
            }
        }
        $ins_stmt->close();
    } else {
        $error_msg = "Prepare failed: " . h($mysqli->error);
        if ($is_ajax) { @ini_set('display_errors', 0); while (ob_get_level()>0){@ob_end_clean();} header('Content-Type: application/json; charset=utf-8'); echo json_encode(array('success'=>false,'error'=>$error_msg)); exit; }
    }
}

/* --- UI render --- */
$page = "criticalThinkingPlayer";
require_once('learnerHead_Nav2.php');
?>

<style>
.level-tab { cursor:pointer; margin-right:6px; padding:6px 10px; border-radius:6px; border:1px solid #ddd; display:inline-block; }
.level-tab.selected { background:#0d6efd; color:#fff; border-color:#0d6efd; }
.icon-heading { font-size:1.2rem; margin-right:.5rem; vertical-align:middle; }
.statement-box { background:#fbfbff; padding:12px; border-radius:6px; border:1px dashed #e6ecff; margin-bottom:12px; min-height:80px; }
.nav-statement { cursor:pointer; padding:6px 10px; border-radius:6px; border:1px solid #ddd; background:#fff; margin-right:6px; }
.meta-learner { font-size:0.9rem; color:#444; }
.highlight-score { background: #eaf6ff; padding:4px 8px; border-radius:6px; font-weight:700; }
</style>

<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

      <!-- Hero -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body d-flex flex-column flex-md-row align-items-center">
              <div>
                <h1 class="mb-1 h4">Critical Thinking — <?php echo h($assignment['title']); ?></h1>
                <p class="mb-0 text-muted"><?php echo h($assignment['description']); ?></p>
                <div style="margin-top:10px;">
                  <span class="level-tab selected" data-level="kid">Kid</span>
                  <span class="level-tab" data-level="teen">Teen</span>
                  <span class="level-tab" data-level="adult">Adult</span>
                </div>
              </div>
              <div class="ms-auto text-center">
                <div class="meta-learner">Learner: <strong><?php echo h($learner['login']); ?></strong><br/>
                  Name: <strong><?php echo h(trim($learner['name'].' '.$learner['surname'])); ?></strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- meta cards -->
      <div class="row">
        <div class="col-md-6">
          <div class="card"><div class="card-body">
            <h4><i class="bx bx-brain bx-lg text-primary me-3 icon-heading"></i>Why this matters</h4>
            <p class="text-muted">This task trains critical thinking skills: analyze claims, structure responses, and practice evidence-based reasoning. Regular practice builds resilience and clarity.</p>
          </div></div>
        </div>
        <div class="col-md-6">
          <div class="card"><div class="card-body">
            <h4><i class="bx bx-info-circle bx-lg text-primary me-3 icon-heading"></i>Task details</h4>
            <p class="text-muted">
              <strong>Subtask ID:</strong> <?php echo h($sub['id']); ?> &nbsp;
              <strong>Response type:</strong> <?php echo h($sub['response_type']); ?> &nbsp;
              <strong>Max attempts (statement):</strong> <?php echo intval($MAX_ATTEMPTS_PER_STATEMENT); ?>
            </p>
            <p class="text-muted">
              <strong>Your attempts (subtask total):</strong> <?php echo ($progress ? intval($progress['attempts']) : 0); ?> &nbsp;
              <strong>Last attempted:</strong> <?php echo ($progress && $progress['last_attempted_at'] ? h($progress['last_attempted_at']) : '—'); ?>
            </p>
            <p class="text-muted">
              <strong>Attempts (current statement):</strong> <span id="statementAttempts">0</span>
            </p>
          </div></div>
        </div>
      </div>

      <!-- navigator + form -->
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="card"><div class="card-body">

            <div id="statementContainer" class="statement-box"></div>
            <div style="margin-bottom:10px;">
              <button id="prevStatement" class="nav-statement">Prev</button>
              <button id="nextStatement" class="nav-statement">Next</button>
              <span id="statementIndexInfo" style="margin-left:12px;color:#666;"></span>
            </div>

            <?php if ($error_msg): ?><div class="alert alert-danger"><?php echo h($error_msg); ?></div><?php endif; ?>
            <?php if ($success_msg): ?><div class="alert alert-success"><?php echo h($success_msg); ?></div><?php endif; ?>

            <form id="ctSubmitForm" method="post" action="" accept-charset="utf-8">
              <input type="hidden" name="action" value="submit">
              <input type="hidden" name="ajax" value="1">
              <input type="hidden" name="level" id="selectedLevel" value="kid">
              <input type="hidden" name="statement_id" id="selectedStatementId" value="">

              <div class="form-group">
                <label for="response">Your response</label>
                <textarea name="response" id="response" rows="8" class="form-control" required placeholder="Write your response here..."><?php echo $latest_submission ? h($latest_submission['content']) : ''; ?></textarea>
              </div>

              <div style="margin-top:12px;">
                <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                <a href="critical-thinking.php" class="btn btn-secondary">Back to catalogue</a>
              </div>
            </form>

            <div id="latestSubmissionBox" style="margin-top:16px;">
              <?php if ($latest_submission): ?>
                <hr/>
                <div class="small text-muted" id="latestSubmissionInner">
                  <strong>Latest submission (<?php echo h($latest_submission['created_at']); ?>)</strong><br/>
                  <span class="highlight-score">AI Auto Score: <span id="aiScoreVal"><?php echo ($latest_submission['auto_score']!==null ? intval($latest_submission['auto_score']) : '—'); ?></span></span>
                  &nbsp; Computed: <span id="computedVal"><?php echo ($latest_submission['computed_score']!==null ? intval($latest_submission['computed_score']) : '—'); ?></span>
                  &nbsp; Final: <span id="finalVal"><?php echo ($latest_submission['final_score']!==null ? intval($latest_submission['final_score']) : '—'); ?></span>
                  &nbsp; Overridden: <span id="overriddenVal"><?php echo ($latest_submission['is_overridden'] ? 'Yes' : 'No'); ?></span><br/>
                  <div id="instructorNotes" style="margin-top:6px;"><?php echo $latest_submission['instructor_notes'] ? ('<strong>Instructor:</strong> '.h($latest_submission['instructor_notes'])) : ''; ?></div>
                  <div id="submissionContent" style="margin-top:8px;"><?php echo nl2br(h($latest_submission['content'])); ?></div>
                </div>
              <?php else: ?>
                <div id="latestSubmissionInner" class="small text-muted">No submissions yet.</div>
              <?php endif; ?>
            </div>

          </div></div>
        </div>
      </div>

    </div>


<?php require_once('../platformFooter.php'); ?>

<script type="text/javascript">
(function(){
    var lvlMap = <?php echo json_encode($level_statements); ?>;
    var levelTabs = document.getElementsByClassName('level-tab');
    var currentLevel = 'kid';
    var idxMap = { kid:0, teen:0, adult:0 };
    var container = document.getElementById('statementContainer');
    var selectedLevelInput = document.getElementById('selectedLevel');
    var selectedStatementInput = document.getElementById('selectedStatementId');
    var prevBtn = document.getElementById('prevStatement'), nextBtn = document.getElementById('nextStatement');
    var info = document.getElementById('statementIndexInfo');
    var attemptsEl = document.getElementById('statementAttempts');
    var responseEl = document.getElementById('response');

    function renderLevel(lvl) {
        currentLevel = lvl;
        for (var i=0;i<levelTabs.length;i++){
            var t = levelTabs[i];
            if (t.getAttribute('data-level') === lvl) t.className = t.className.replace(/\bselected\b/g,'') + ' selected';
            else t.className = t.className.replace(/\bselected\b/g,'');
        }
        selectedLevelInput.value = lvl;
        if (!idxMap[lvl]) idxMap[lvl] = 0;
        var arr = lvlMap[lvl] || [];
        var idx = idxMap[lvl] || 0;
        if (idx < 0) idx = 0;
        if (idx >= arr.length) idx = arr.length - 1;
        idxMap[lvl] = idx;
        var item = arr[idx];
        if (!item) {
            container.innerHTML = "<em>No problem statement available.</em>";
            selectedStatementInput.value = '';
            info.innerText = '';
            attemptsEl.innerText = '0';
            responseEl.value = '';
        } else {
            container.innerHTML = item.statement.replace(/\n/g,'<br/>');
            selectedStatementInput.value = item.id ? item.id : '';
            info.innerText = (idx+1) + " of " + arr.length;
            // fetch latest submission + attempts for this statement
            fetchLatest(item.id, lvl);
        }
    }

    for (var i=0;i<levelTabs.length;i++){
        (function(t){ t.addEventListener('click', function(ev){ ev.preventDefault(); renderLevel(t.getAttribute('data-level')); }, false); })(levelTabs[i]);
    }
    prevBtn.addEventListener('click', function(ev){ ev.preventDefault(); var arr = lvlMap[currentLevel] || []; if (!arr.length) return; idxMap[currentLevel] = Math.max(0, (idxMap[currentLevel]||0) - 1); renderLevel(currentLevel); }, false);
    nextBtn.addEventListener('click', function(ev){ ev.preventDefault(); var arr = lvlMap[currentLevel] || []; if (!arr.length) return; idxMap[currentLevel] = Math.min(arr.length - 1, (idxMap[currentLevel]||0) + 1); renderLevel(currentLevel); }, false);
    renderLevel('kid');

    /* AJAX: fetch latest submission for statement */
    function fetchLatest(statementId, level) {
        if (!statementId) {
            attemptsEl.innerText = '0';
            responseEl.value = '';
            // clear latest submission display
            document.getElementById('aiScoreVal').innerText = '—';
            document.getElementById('computedVal').innerText = '—';
            document.getElementById('finalVal').innerText = '—';
            document.getElementById('overriddenVal').innerText = 'No';
            document.getElementById('instructorNotes').innerHTML = '';
            document.getElementById('submissionContent').innerHTML = '';
            return;
        }
        var fd = new FormData();
        fd.append('ajax','1');
        fd.append('action','fetch_latest');
        fd.append('statement_id', statementId);
        fd.append('level', level);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        xhr.onreadystatechange = function(){
            if (xhr.readyState !== 4) return;
            if (xhr.status >= 200 && xhr.status < 300) {
                try { var json = JSON.parse(xhr.responseText); } catch (e) { console.error('fetchLatest: bad JSON', xhr.responseText); return; }
                if (!json.success) return;
                var latest = json.latest;
                attemptsEl.innerText = json.attempts || 0;
                if (latest) {
                    document.getElementById('aiScoreVal').innerText = (latest.auto_score !== null ? parseInt(latest.auto_score,10) : '—');
                    document.getElementById('computedVal').innerText = (latest.computed_score !== null ? parseInt(latest.computed_score,10) : '—');
                    document.getElementById('finalVal').innerText = (latest.final_score !== null ? parseInt(latest.final_score,10) : '—');
                    document.getElementById('overriddenVal').innerText = (latest.is_overridden ? 'Yes' : 'No');
                    document.getElementById('instructorNotes').innerHTML = latest.instructor_notes ? ('<strong>Instructor:</strong> ' + latest.instructor_notes) : '';
                    document.getElementById('submissionContent').innerHTML = latest.content ? latest.content.replace(/\n/g,'<br/>') : '';
                    // populate response textarea with last submission content for convenience
                    responseEl.value = latest.content ? latest.content : '';
                } else {
                    // no submission yet
                    document.getElementById('aiScoreVal').innerText = '—';
                    document.getElementById('computedVal').innerText = '—';
                    document.getElementById('finalVal').innerText = '—';
                    document.getElementById('overriddenVal').innerText = 'No';
                    document.getElementById('instructorNotes').innerHTML = '';
                    document.getElementById('submissionContent').innerHTML = '';
                    responseEl.value = '';
                }
            } else {
                console.error('fetchLatest failed', xhr.statusText);
            }
        };
        xhr.send(fd);
    }

    /* AJAX submit handler */
    var form = document.getElementById('ctSubmitForm');
    var submitBtn = document.getElementById('submitBtn');
    form.addEventListener('submit', function(ev){
        ev.preventDefault();
        submitBtn.disabled = true; submitBtn.innerText = 'Submitting...';
        var formData = new FormData(form);
        formData.set('level', selectedLevelInput.value || 'kid');
        formData.set('statement_id', selectedStatementInput.value || '');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            submitBtn.disabled = false; submitBtn.innerText = 'Submit';
            if (xhr.status >= 200 && xhr.status < 300) {
                var text = xhr.responseText.trim();
                var json = null;
                try { json = JSON.parse(text); }
                catch (e) {
                    // recovery attempt
                    var first = text.indexOf('{'), last = text.lastIndexOf('}');
                    if (first !== -1 && last !== -1 && last > first) {
                        try { json = JSON.parse(text.substring(first, last+1)); console.warn('Recovered JSON'); }
                        catch (e2) { alert('Unexpected response from server. Check console.'); console.error(text); return; }
                    } else { alert('Unexpected response from server. Check console.'); console.error(text); return; }
                }
                if (!json.success) { alert(json.error || 'Submission failed'); return; }
                var sub = json.submission;
                // update UI values
                var aiEl = document.getElementById('aiScoreVal');
                var compEl = document.getElementById('computedVal');
                var finalEl = document.getElementById('finalVal');
                var overEl = document.getElementById('overriddenVal');
                var notesEl = document.getElementById('instructorNotes');
                var contentEl = document.getElementById('submissionContent');
                if (aiEl) aiEl.innerText = (sub.auto_score !== null ? parseInt(sub.auto_score,10) : '—');
                if (compEl) compEl.innerText = (sub.computed_score !== null ? parseInt(sub.computed_score,10) : '—');
                if (finalEl) finalEl.innerText = (sub.final_score !== null ? parseInt(sub.final_score,10) : '—');
                if (overEl) overEl.innerText = (sub.is_overridden ? 'Yes' : 'No');
                if (notesEl) notesEl.innerHTML = sub.instructor_notes ? ('<strong>Instructor:</strong> ' + (sub.instructor_notes)) : '';
                if (contentEl) contentEl.innerHTML = (sub.content ? sub.content.replace(/\n/g,'<br/>') : '');

                // update attempts count returned by server
                if (json.attempts !== undefined && attemptsEl) attemptsEl.innerText = json.attempts;

                // highlight
                var aiWrap = document.getElementById('aiScoreVal');
                if (aiWrap) { aiWrap.parentNode.className = 'highlight-score'; setTimeout(function(){ aiWrap.parentNode.className = 'highlight-score'; }, 50); }

                if (json.flagged && json.reasons && json.reasons.length) {
                    alert('Note: submission flagged for review: ' + json.reasons.join(', '));
                }

            } else {
                alert('Submission failed: ' + xhr.statusText);
            }
        };
        xhr.send(formData);
    }, false);

})();
</script>
