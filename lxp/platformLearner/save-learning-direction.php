<?php
/**
 * Astraal – Save Learning Direction
 * Robust | Trace-aware | Milestone-safe
 * PHP 5.x Compatible | MyISAM Safe
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');
require_once('learning_direction_engine.php');
require_once('milestone-engine.php');

if (!isset($_SESSION['phx_user_id'])) {
    die('Not logged in');
}

$learner_id = (int) $_SESSION['phx_user_id'];

/* ================= FETCH ACTIVE INTENT ================= */
$intent = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT *
  FROM learner_learning_intent
  WHERE learner_id = $learner_id
    AND intent_status = 'active'
  ORDER BY intent_version DESC
  LIMIT 1
"));

/* ================= FETCH ACTIVE SKILL GAP ================= */
$skill_gap = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT *
  FROM learner_skill_gap
  WHERE learner_id = $learner_id
    AND skill_gap_status = 'active'
  ORDER BY skill_gap_version DESC
  LIMIT 1
"));

if (!$intent || !$skill_gap) {
    die('Intent or Skill Gap missing');
}

/* ================= DETERMINE LEARNING DIRECTION ================= */

$result = determineLearningDirection($intent, $skill_gap);

if (
    !is_array($result) ||
    empty($result['direction_code']) ||
    !isset($result['reasons'])
) {
    die('Learning Direction Engine returned invalid result');
}

$direction_code = mysqli_real_escape_string(
    $coni,
    $result['direction_code']
);

$direction_reason = mysqli_real_escape_string(
    $coni,
    implode('; ', $result['reasons'])
);

$verRes = mysqli_query($coni,"
  SELECT MAX(direction_version) AS max_ver
  FROM learner_learning_direction
  WHERE learner_id = $learner_id
");
$verRow = mysqli_fetch_assoc($verRes);
$next_version = ($verRow && $verRow['max_ver']) ? ((int)$verRow['max_ver'] + 1) : 1;


/* ================= PREPARE SAFE VALUES ================= */
$direction_code_safe   = mysqli_real_escape_string($coni, $direction_code);
$direction_reason_safe = mysqli_real_escape_string(
    $coni,
    implode('; ', $reasons)
);

$trace_json_safe = mysqli_real_escape_string(
    $coni,
    json_encode($trace)
);

/* ================= CALCULATE NEXT VERSION ================= */
$verRow = mysqli_fetch_assoc(mysqli_query($coni,"
  SELECT MAX(direction_version) AS max_ver
  FROM learner_learning_direction
  WHERE learner_id = $learner_id
"));

$next_version = ($verRow && $verRow['max_ver'])
    ? ((int)$verRow['max_ver'] + 1)
    : 1;

/* ================= SUPERSEDE PREVIOUS ACCEPTED ================= */
mysqli_query($coni,"
  UPDATE learner_learning_direction
  SET direction_status = 'superseded',
      updated_at = NOW()
  WHERE learner_id = $learner_id
    AND direction_status = 'accepted'
");

/* ================= INSERT NEW ACCEPTED DIRECTION ================= */
$insertDir = mysqli_query($coni,"
  INSERT INTO learner_learning_direction
  (
    learner_id,
    intent_id, intent_version,
    skill_gap_id, skill_gap_version,
    direction_code, direction_reason,
    direction_status, direction_version,
    created_at, updated_at, accepted_at,
    created_by, accepted_by
  )
  VALUES
  (
    $learner_id,
    {$intent['intent_id']}, {$intent['intent_version']},
    {$skill_gap['skill_gap_id']}, {$skill_gap['skill_gap_version']},
    '$direction_code', '$direction_reason',
    'accepted', $next_version,
    NOW(), NOW(), NOW(),
    'system', 'learner'
  )
");



if (!$insertDir) {
    die('Direction insert failed: '.mysqli_error($coni));
}

$direction_id = mysqli_insert_id($coni);

if (!$direction_id) {
    die('Direction ID not generated');
}

/* ================= GENERATE MILESTONES ================= */
$milestones = generateMilestonesForDirection($direction_code);

if (!is_array($milestones) || count($milestones) === 0) {
    die('Milestone engine returned empty set for direction: '.$direction_code);
}

/* ================= INSERT MILESTONES ================= */
$order = 1;
foreach ($milestones as $m) {

    $status = ($order === 1) ? 'active' : 'not_started';
    $activated_at = ($order === 1) ? 'NOW()' : 'NULL';

    $sql = "
      INSERT INTO learner_milestones
      (
        learner_id,
        direction_id,
        milestone_code,
        milestone_title,
        milestone_description,
        milestone_order,
        milestone_status,
        activated_at,
        created_at,
        updated_at
      )
      VALUES
      (
        $learner_id,
        $direction_id,
        '".mysqli_real_escape_string($coni,$m['code'])."',
        '".mysqli_real_escape_string($coni,$m['title'])."',
        '".mysqli_real_escape_string($coni,$m['desc'])."',
        $order,
        '$status',
        $activated_at,
        NOW(),
        NOW()
      )
    ";

    if (!mysqli_query($coni, $sql)) {
        die('Milestone insert failed: '.mysqli_error($coni));
    }

    $order++;
}

/* ================= REDIRECT ================= */
header("Location: learning-milestones.php");
exit;
