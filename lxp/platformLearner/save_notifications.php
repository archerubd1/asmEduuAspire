<?php
/**
 *  Astraal LXP – save_notifications.php
 * Handles notification preference updates for learners
 * PHP 5.4 / UwAmp / GoDaddy compatible
 */

mysqli_set_charset($coni, 'utf8mb4');

// --- Helper function for safe values ---
function safe($val, $conn) {
    return mysqli_real_escape_string($conn, trim($val));
}

// --- Ensure learner ID is set ---
if (!isset($phx_user_id) || !$phx_user_id) {
    echo json_encode(array('status' => 'error', 'message' => 'Session invalid'));
    exit;
}

$learner_id = (int)$phx_user_id;

// --- Read POST values ---
$frequency = isset($_POST['sendNotification']) ? safe($_POST['sendNotification'], $coni) : 'Anytime';

// Map of notifications (title → type)
$notificationTypes = array(
    'System Alerts'       => 'System',
    'Account Activity'    => 'Account',
    'Reminders & Deadlines' => 'Reminder',
    'AI Nudges & Insights' => 'AI_Nudge'
);

// --- Check if any notifications exist for this learner ---
$check = mysqli_query($coni, "SELECT COUNT(*) AS cnt FROM learner_notifications WHERE learner_id = $learner_id");
$row   = mysqli_fetch_assoc($check);
$hasExisting = $row && $row['cnt'] > 0;

// --- If none exist, insert defaults ---
if (!$hasExisting) {
    foreach ($notificationTypes as $title => $type) {
        $ins = sprintf("
            INSERT INTO learner_notifications
            (learner_id, title, message, type, channel_email, channel_browser, channel_app, frequency, is_read, created_on, updated_on)
            VALUES (%d, '%s', NULL, '%s', 1, 1, 0, '%s', 0, NOW(), NOW())
        ",
        $learner_id,
        safe($title, $coni),
        safe($type, $coni),
        safe($frequency, $coni)
        );
        mysqli_query($coni, $ins);
    }
}

// --- Loop through defined notifications and update accordingly ---
foreach ($notificationTypes as $title => $type) {
    $emailKey   = '';
    $browserKey = '';
    $appKey     = '';

    // Match the POST keys exactly as in notifications.php
    if ($type == 'System') {
        $emailKey   = 'system_email';
        $browserKey = 'system_browser';
        $appKey     = 'system_app';
    } elseif ($type == 'Account') {
        $emailKey   = 'activity_email';
        $browserKey = 'activity_browser';
        $appKey     = 'activity_app';
    } elseif ($type == 'Reminder') {
        $emailKey   = 'reminder_email';
        $browserKey = 'reminder_browser';
        $appKey     = 'reminder_app';
    } elseif ($type == 'AI_Nudge') {
        $emailKey   = 'ai_email';
        $browserKey = 'ai_browser';
        $appKey     = 'ai_app';
    }

    $email   = isset($_POST[$emailKey]) ? 1 : 0;
    $browser = isset($_POST[$browserKey]) ? 1 : 0;
    $app     = isset($_POST[$appKey]) ? 1 : 0;

    // Check if row exists
    $qCheck = sprintf(
        "SELECT notification_id FROM learner_notifications WHERE learner_id = %d AND type = '%s' LIMIT 1",
        $learner_id,
        safe($type, $coni)
    );
    $rCheck = mysqli_query($coni, $qCheck);

    if ($rCheck && mysqli_num_rows($rCheck) > 0) {
        // Update existing record
        $rowN = mysqli_fetch_assoc($rCheck);
        $nid  = (int)$rowN['notification_id'];

        $upd = sprintf("
            UPDATE learner_notifications
            SET channel_email = %d,
                channel_browser = %d,
                channel_app = %d,
                frequency = '%s',
                updated_on = NOW()
            WHERE notification_id = %d
        ",
        $email, $browser, $app,
        safe($frequency, $coni),
        $nid
        );
        mysqli_query($coni, $upd);
    } else {
        // Insert new record
        $ins2 = sprintf("
            INSERT INTO learner_notifications
            (learner_id, title, message, type, channel_email, channel_browser, channel_app, frequency, is_read, created_on, updated_on)
            VALUES (%d, '%s', NULL, '%s', %d, %d, %d, '%s', 0, NOW(), NOW())
        ",
        $learner_id,
        safe($title, $coni),
        safe($type, $coni),
        $email, $browser, $app,
        safe($frequency, $coni)
        );
        mysqli_query($coni, $ins2);
    }
}

echo json_encode(array('status' => 'success', 'message' => 'Notification preferences saved successfully.'));
exit;
?>
