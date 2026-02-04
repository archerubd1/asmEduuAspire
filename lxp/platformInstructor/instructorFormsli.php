<?php
/**
 * Instructor Forms – Course Intelligence (Legacy Safe)
 * PHP 5.4 / MySQL 5.x compatible
 */

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    die('Unauthorized access');
}

/* Ensure correct process */
if (!isset($_POST['processType']) || $_POST['processType'] != 'saveCourseIntelligence') {
    return;
}

/* -----------------------------
   Extract + sanitize inputs
------------------------------ */
$efront_course_id = isset($_POST['efront_course_id'])
    ? (int)$_POST['efront_course_id']
    : 0;

$supported_directions = isset($_POST['supported_directions'])
    ? $_POST['supported_directions']
    : '[]';

$milestone_support = isset($_POST['milestone_support'])
    ? $_POST['milestone_support']
    : '[]';

$bucket_roles = isset($_POST['bucket_roles'])
    ? $_POST['bucket_roles']
    : '[]';

/* Optional / future-proof */
$skill_tags = isset($_POST['skill_tags'])
    ? $_POST['skill_tags']
    : '[]';

$difficulty_role = isset($_POST['difficulty_role'])
    ? $_POST['difficulty_role']
    : 'foundation';

$crowd_flag = isset($_POST['crowd_flag']) ? 1 : 0;

$exit_condition = isset($_POST['exit_condition'])
    ? trim($_POST['exit_condition'])
    : '';

$active = 1;

/* -----------------------------
   Server-side guardrails
------------------------------ */
if (
    $efront_course_id === 0 ||
    $exit_condition === '' ||
    json_decode($supported_directions, true) === array() ||
    json_decode($bucket_roles, true) === array()
) {
    header("Location: course_intelligence_designer.php?error=" .
        urlencode(base64_encode("Design guardrails not satisfied.")));
    exit;
}

/* -----------------------------
   Check if intelligence exists
------------------------------ */
$exists = false;

$sql = "SELECT efront_course_id
        FROM lxp_course_intelligence
        WHERE efront_course_id = ?";

$stmt = $coni->prepare($sql);
$stmt->bind_param("i", $efront_course_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $exists = true;
}
$stmt->close();

/* -----------------------------
   INSERT or UPDATE
------------------------------ */
if ($exists === false) {

    /* 🔹 INSERT */
    $sql = "
        INSERT INTO lxp_course_intelligence (
            efront_course_id,
            supported_directions,
            milestone_support,
            bucket_roles,
            skill_tags,
            difficulty_role,
            crowd_flag,
            active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $coni->prepare($sql);
    $stmt->bind_param(
        "isssssii",
        $efront_course_id,
        $supported_directions,
        $milestone_support,
        $bucket_roles,
        $skill_tags,
        $difficulty_role,
        $crowd_flag,
        $active
    );

} else {

    /* 🔹 UPDATE (Refine Course Design) */
    $sql = "
        UPDATE lxp_course_intelligence
        SET
            supported_directions = ?,
            milestone_support    = ?,
            bucket_roles         = ?,
            difficulty_role      = ?,
            crowd_flag           = ?,
            active               = 1
        WHERE efront_course_id = ?
    ";

    $stmt = $coni->prepare($sql);
    $stmt->bind_param(
        "ssssii",
        $supported_directions,
        $milestone_support,
        $bucket_roles,
        $difficulty_role,
        $crowd_flag,
        $efront_course_id
    );
}

/* -----------------------------
   Execute
------------------------------ */
if (!$stmt->execute()) {
    $stmt->close();
    header("Location: course_intelligence_designer.php?error=" .
        urlencode(base64_encode("Database error while saving intelligence.")));
    exit;
}

$stmt->close();

/* -----------------------------
   Success redirect
------------------------------ */
header("Location: course_intelligence_designer.php?msg=" .
    urlencode(base64_encode("Course intelligence saved successfully.")));
exit;
