<?php
/**
 * Astraal – Save Learner Skill Gap
 * PHP 5.4+ Compatible | MySQL 5.x | MyISAM Safe
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$learner_id    = (int) $_SESSION['phx_user_id'];
$learner_login = mysqli_real_escape_string($coni, $_SESSION['phx_user_login']);
$now           = date('Y-m-d H:i:s');

/* -------------------------------------------------
   Helper: safely encode checkbox arrays as JSON
------------------------------------------------- */
function json_or_empty($key) {
    if (isset($_POST[$key]) && is_array($_POST[$key])) {
        return json_encode(array_values($_POST[$key]));
    }
    return json_encode(array());
}

/* -------------------------------------------------
   Fetch active learning intent (MANDATORY)
------------------------------------------------- */
$intentSql = "
    SELECT intent_id, intent_version
    FROM learner_learning_intent
    WHERE learner_id = $learner_id
      AND intent_status = 'active'
    ORDER BY intent_version DESC
    LIMIT 1
";
$intentRes = mysqli_query($coni, $intentSql);

if (!$intentRes || mysqli_num_rows($intentRes) === 0) {
    die('ERROR: No active learning intent found.');
}

$intent = mysqli_fetch_assoc($intentRes);
$intent_id      = (int) $intent['intent_id'];
$intent_version = (int) $intent['intent_version'];

/* -------------------------------------------------
   Read POST values (PHP 5 SAFE)
------------------------------------------------- */
$exposure            = json_or_empty('exposure');
$conceptual_clarity  = json_or_empty('conceptual');
$application_ability = json_or_empty('application');
$tool_readiness      = json_or_empty('tools');
$confidence_level    = json_or_empty('confidence');
$learning_friction   = json_or_empty('friction');

$blockers = '';
if (isset($_POST['blockers'])) {
    $blockers = mysqli_real_escape_string($coni, trim($_POST['blockers']));
}

/* -------------------------------------------------
   Supersede existing active skill gap (if any)
------------------------------------------------- */
$versionSql = "
    SELECT skill_gap_version
    FROM learner_skill_gap
    WHERE learner_id = $learner_id
      AND skill_gap_status = 'active'
    ORDER BY skill_gap_version DESC
    LIMIT 1
";
$versionRes = mysqli_query($coni, $versionSql);

$new_version = 1;

if ($versionRes && mysqli_num_rows($versionRes) > 0) {
    $row = mysqli_fetch_assoc($versionRes);
    $new_version = ((int)$row['skill_gap_version']) + 1;

    mysqli_query($coni, "
        UPDATE learner_skill_gap
        SET skill_gap_status = 'superseded',
            updated_at = '$now'
        WHERE learner_id = $learner_id
          AND skill_gap_status = 'active'
    ");
}

/* -------------------------------------------------
   Insert new skill gap (VERSIONED)
------------------------------------------------- */
$insertSql = "
INSERT INTO learner_skill_gap (
    learner_id,
    learner_login,
    intent_id,
    intent_version,
    exposure,
    conceptual_clarity,
    application_ability,
    tool_readiness,
    confidence_level,
    learning_friction,
    blockers,
    skill_gap_status,
    skill_gap_version,
    created_by,
    created_at,
    updated_at
) VALUES (
    $learner_id,
    '$learner_login',
    $intent_id,
    $intent_version,
    '$exposure',
    '$conceptual_clarity',
    '$application_ability',
    '$tool_readiness',
    '$confidence_level',
    '$learning_friction',
    '$blockers',
    'active',
    $new_version,
    'learner',
    '$now',
    '$now'
)";
$result = mysqli_query($coni, $insertSql);

if (!$result) {
    die('DB ERROR: ' . mysqli_error($coni));
}

/* -------------------------------------------------
   Redirect back to Skill Gap page
------------------------------------------------- */
header("Location: skills-gap.php?saved=1");
exit;
