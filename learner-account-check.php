<?php
/**
 * Learner Account Completion Check
 * Uwamp | PHP 5.4 | MySQL 5.x
 */
function isLearnerAccountComplete(mysqli $coni, $userId)
{
    $sql = "
        SELECT 1
        FROM learners
        WHERE user_id = ?
          AND gender IS NOT NULL AND gender <> ''
          AND dob IS NOT NULL
          AND city IS NOT NULL AND city <> ''
          AND state IS NOT NULL AND state <> ''
          AND country IS NOT NULL AND country <> ''
          AND phone IS NOT NULL AND phone <> ''
          AND address IS NOT NULL AND address <> ''
          AND zip IS NOT NULL AND zip <> ''
          AND currency IS NOT NULL AND currency <> ''
          AND status = 'active'
        LIMIT 1
    ";

    $stmt = mysqli_prepare($coni, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $isComplete = (mysqli_stmt_num_rows($stmt) === 1);
    mysqli_stmt_close($stmt);

    return $isComplete;
}
