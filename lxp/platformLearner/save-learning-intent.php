<?php
/**
 * Astraal – Save Learning Intent
 * Versioned | PHP 5.x | MySQL 5.x | MyISAM Safe
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

/* ---------------- SECURITY ---------------- */

if (!isset($_SESSION['phx_user_id'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$learner_id = (int) $_SESSION['phx_user_id'];
$user_role  = isset($_SESSION['phx_user_role']) ? $_SESSION['phx_user_role'] : 'learner';

/* Only learners can submit */
if ($user_role !== 'learner') {
    header("Location: learning-intent.php?error=" .
        urlencode(base64_encode("You are not allowed to modify learning intent.")));
    exit;
}

/* ---------------- HELPERS ---------------- */

function clean_text($val, $conn) {
    return mysqli_real_escape_string($conn, trim($val));
}

function pipe_array($arr) {
    if (!is_array($arr)) return '';
    return implode('|', array_map('trim', $arr));
}

/* ---------------- VALIDATION ---------------- */

$errors = array();

/* Reference Axis (mandatory) */
if (empty($_POST['reference_axis'])) {
    $errors[] = "Please select what you are currently thinking about learning.";
}

/* At least one context stage */
if (empty($_POST['context_stage']) || !is_array($_POST['context_stage'])) {
    $errors[] = "Please indicate where you are right now.";
}

/* At least one motivation */
if (empty($_POST['motivation_reason']) || !is_array($_POST['motivation_reason'])) {
    $errors[] = "Please select at least one motivation.";
}

/* At least one direction */
if (empty($_POST['intent_direction']) || !is_array($_POST['intent_direction'])) {
    $errors[] = "Please indicate what you are moving towards.";
}

/* AI comfort is mandatory */
if (empty($_POST['ai_comfort']) || !is_array($_POST['ai_comfort'])) {
    $errors[] = "Please indicate your comfort with AI-assisted learning.";
}

/* If errors, redirect */
if (!empty($errors)) {
    header("Location: learning-intent.php?error=" .
        urlencode(base64_encode(implode(' ', $errors))));
    exit;
}

/* ---------------- DATA PREP ---------------- */

$reference_axis   = clean_text($_POST['reference_axis'], $coni);
$reference_label  = isset($_POST['reference_label'])
                    ? clean_text($_POST['reference_label'], $coni)
                    : '';

$context_stage        = pipe_array($_POST['context_stage']);
$motivation_reason    = pipe_array($_POST['motivation_reason']);
$cognitive_preference = isset($_POST['cognitive_preference'])
                        ? pipe_array($_POST['cognitive_preference'])
                        : '';
$intent_direction     = pipe_array($_POST['intent_direction']);
$constraints          = isset($_POST['constraints'])
                        ? pipe_array($_POST['constraints'])
                        : '';
$ai_comfort            = pipe_array($_POST['ai_comfort']);
$context_notes         = isset($_POST['context_notes'])
                        ? clean_text($_POST['context_notes'], $coni)
                        : '';

$now = date('Y-m-d H:i:s');

/* ---------------- VERSIONING ---------------- */

/* Find current active intent (if any) */
$check_sql = "
SELECT intent_id, intent_version
FROM learner_learning_intent
WHERE learner_id = {$learner_id}
  AND intent_status = 'active'
LIMIT 1
";
$check_res = mysqli_query($coni, $check_sql);
$existing  = mysqli_fetch_assoc($check_res);

$new_version = 1;

/* Supersede old intent */
if ($existing && isset($existing['intent_id'])) {

    $new_version = ((int)$existing['intent_version']) + 1;

    mysqli_query(
        $coni,
        "UPDATE learner_learning_intent
         SET intent_status = 'superseded'
         WHERE intent_id = {$existing['intent_id']}"
    );
}

/* ---------------- INSERT NEW INTENT ---------------- */

$insert_sql = "
INSERT INTO learner_learning_intent
(
    learner_id,
    reference_axis,
    reference_label,
    context_stage,
    motivation_reason,
    cognitive_preference,
    intent_direction,
    constraints,
    ai_comfort,
    context_notes,
    intent_version,
    intent_status,
    created_by,
    created_at
)
VALUES
(
    {$learner_id},
    '{$reference_axis}',
    '{$reference_label}',
    '{$context_stage}',
    '{$motivation_reason}',
    '{$cognitive_preference}',
    '{$intent_direction}',
    '{$constraints}',
    '{$ai_comfort}',
    '{$context_notes}',
    {$new_version},
    'active',
    'learner',
    '{$now}'
)
";

if (!mysqli_query($coni, $insert_sql)) {

    header("Location: learning-intent.php?error=" .
        urlencode(base64_encode("Failed to save learning intent. Please try again.")));
    exit;
}

/* ---------------- SUCCESS ---------------- */

header("Location: learning-intent.php?msg=" .
    urlencode(base64_encode("Your learning intent has been saved successfully.")));
exit;
