<?php
/**
 * ct_autoscore.php
 * Lightweight autoscorer for ct_submissions
 * PHP 5.4 + MySQL 5.x compatible (mysqli)
 *
 * Usage:
 *   GET/POST ?submission_id=123
 * Returns JSON:
 *  { success:true, score: 78.5, flagged:0, signals: {...}, msg:"OK" }
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php'); // loads $coni (or adjust)
date_default_timezone_set(@date_default_timezone_get());

/* --- DB connection --- */
$mysqli = null;
if (isset($coni) && $coni instanceof mysqli) $mysqli = $coni;
elseif (isset($GLOBALS['coni']) && $GLOBALS['coni'] instanceof mysqli) $mysqli = $GLOBALS['coni'];
elseif (isset($GLOBALS['mysqli']) && $GLOBALS['mysqli'] instanceof mysqli) $mysqli = $GLOBALS['mysqli'];
elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof mysqli) $mysqli = $GLOBALS['conn'];
elseif (isset($conn) && $conn instanceof mysqli) $mysqli = $conn;

if (!($mysqli instanceof mysqli)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success'=>false,'msg'=>'Database connection missing.'));
    exit;
}

/* read submission_id */
$submission_id = isset($_REQUEST['submission_id']) ? trim($_REQUEST['submission_id']) : '';
if ($submission_id === '' || !ctype_digit($submission_id)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success'=>false,'msg'=>'Missing or invalid submission_id'));
    exit;
}
$submission_id = (int)$submission_id;

/* fetch submission row */
$sql = "SELECT id, user_id, subassignment_id, attempt_no, content_text, computed_score, created_at FROM ct_submissions WHERE id = ? LIMIT 1";
if (!($stmt = $mysqli->prepare($sql))) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success'=>false,'msg'=>'Prepare failed: '.$mysqli->error));
    exit;
}
$stmt->bind_param('i', $submission_id);
$stmt->execute();
$stmt->bind_result($id, $user_id, $subassignment_id, $attempt_no, $content_text, $old_score, $created_at);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success'=>false,'msg'=>'Submission not found'));
    exit;
}
$stmt->close();

/* ---------- autoscore heuristics (same dimension model) ---------- */
$signals = array();
$text = trim($content_text);
$signals['char_count'] = mb_strlen($text,'UTF-8');

/* normalize text */
$norm = preg_replace('/\s+/u', ' ', $text);
$norm = trim($norm);

/* tokenization */
$words = array();
if ($norm !== '') $words = preg_split('/\s+/u', $norm);
$word_count = count($words);
$signals['word_count'] = $word_count;

/* sentence count */
$sentences = preg_split('/[\.!\?]+[\s]*/u', $norm, -1, PREG_SPLIT_NO_EMPTY);
$sentence_count = count($sentences);
$signals['sentence_count'] = $sentence_count;

/* unique words ratio */
$lower_words = array();
foreach ($words as $w) {
    $clean = preg_replace('/[^\p{L}\p{N}\'-]/u', '', $w);
    if ($clean === '') continue;
    $lower_words[] = mb_strtolower($clean, 'UTF-8');
}
$unique = count(array_unique($lower_words));
$signals['unique_words'] = $unique;
$signals['unique_ratio'] = ($word_count>0 ? round($unique / $word_count, 3) : 0);

/* average word length */
$avg_word_len = 0;
if ($word_count > 0) {
    $total_letters = 0;
    foreach ($words as $w) $total_letters += mb_strlen(preg_replace('/[^\p{L}]/u', '', $w), 'UTF-8');
    $avg_word_len = ($word_count > 0 ? $total_letters / $word_count : 0);
}
$signals['avg_word_len'] = round($avg_word_len,3);

/* repeated-char gibberish */
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

/* vowel/consonant ratio */
$vowels = preg_match_all('/[aeiouAEIOU]/u', $text);
$consonants = preg_match_all('/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]/u', $text);
$signals['vowels'] = intval($vowels);
$signals['consonants'] = intval($consonants);
$signals['vowel_ratio'] = ($vowels + $consonants > 0 ? round($vowels / max(1, ($vowels+$consonants)),3) : 0);

/* profanity */
$profane_list = array('fuck','shit','bitch','asshole','damn','crap');
$lower_text = mb_strtolower($text,'UTF-8');
$profane_found = array();
foreach ($profane_list as $pw) {
    if (strpos($lower_text, $pw) !== false) $profane_found[] = $pw;
}
$signals['profane'] = $profane_found;

/* dimensions (same formulas as player) */
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

/* combine */
$score = round(($clarity * 0.30) + ($reasoning * 0.50) + ($creativity * 0.20), 2);

/* penalties */
$flag_reasons = array();
$flagged = 0;
if ($max_run >= 5) { $score -= 30; $flag_reasons[] = 'gibberish'; $signals['gibberish_flag'] = 1; $flagged = 1; }
if ($signals['vowel_ratio'] < 0.25 && $word_count > 3) { $score -= 25; $flag_reasons[] = 'gibberish'; $signals['gibberish_flag'] = 1; $flagged = 1; }
if (!empty($profane_found)) { $score -= 20; $flag_reasons[] = 'profanity'; $signals['profane'] = $profane_found; $flagged = 1; }
if ($score < 0) $score = 0;
$score = round($score, 2);
if ($score < 35) { $flagged = 1; $flag_reasons[] = 'low_score'; }

/* write back to ct_submissions: computed_score, signals JSON, updated_at, auto_score, final_score */
$now = date('Y-m-d H:i:s');
$signals_json = json_encode($signals);

$upd_sql = "UPDATE ct_submissions SET computed_score = ?, auto_score = ?, final_score = ?, signals = ?, updated_at = ? WHERE id = ? LIMIT 1";
if ($upd_stmt = $mysqli->prepare($upd_sql)) {
    // types: d d d s s i => 'dddssi'
    $upd_stmt->bind_param('dddssi', $score, $score, $score, $signals_json, $now, $submission_id);
    $upd_stmt->execute();
    $upd_stmt->close();
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success'=>false,'msg'=>'Update prepare failed: '.$mysqli->error));
    exit;
}

/* If flagged, insert into ct_review_queue */
if ($flagged) {
    $reason_text = implode(',', $flag_reasons);
    $rq_sql = "INSERT INTO ct_review_queue (submission_id, user_id, reason, created_at, status) VALUES (?, ?, ?, ?, 'open')";
    if ($rq_stmt = $mysqli->prepare($rq_sql)) {
        $rq_stmt->bind_param('iiss', $submission_id, $user_id, $reason_text, $now);
        $rq_stmt->execute();
        $rq_stmt->close();
    }
}

/* Return JSON */
header('Content-Type: application/json; charset=utf-8');
echo json_encode(array(
    'success' => true,
    'score' => $score,
    'flagged' => intval($flagged),
    'signals' => $signals,
    'msg' => 'Autoscore applied'
));
exit;
