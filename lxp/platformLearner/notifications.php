<?php
/**
 *  Astraal LXP – Learner Profile (Notifications Tab – Dynamic Load)
 * PHP 5.4 / UwAmp / GoDaddy Compatible
 */

$learner_id = isset($phx_user_id) ? (int)$phx_user_id : 0;

// 1️⃣ Fetch existing preferences from DB
$notifications = array();
$sql = "SELECT type, channel_email, channel_browser, channel_app, frequency
        FROM learner_notifications
        WHERE learner_id = " . (int)$learner_id;
$res = mysqli_query($coni, $sql);

if ($res && mysqli_num_rows($res) > 0) {
  while ($row = mysqli_fetch_assoc($res)) {
    $notifications[$row['type']] = array(
      'email'   => (int)$row['channel_email'],
      'browser' => (int)$row['channel_browser'],
      'app'     => (int)$row['channel_app'],
      'freq'    => $row['frequency']
    );
  }
}

// 2️⃣ Define default values
function notifVal($arr, $key, $ch) {
  if (isset($arr[$key][$ch])) return $arr[$key][$ch] ? 'checked' : '';
  return '';
}
function freqVal($arr) {
  foreach ($arr as $r) if (!empty($r['freq'])) return $r['freq'];
  return 'Anytime';
}

$currentFreq = freqVal($notifications);
?>

<div class="card">
  <h5 class="card-header">Notification Preferences</h5>

  <div class="card-body">
    <form id="notificationsForm" method="POST">
      <table class="table table-striped table-borderless border-bottom align-middle">
        <thead>
          <tr>
            <th class="text-nowrap">Notification Type</th>
            <th class="text-center">✉️ Email</th>
            <th class="text-center">🖥 Browser</th>
            <th class="text-center">📱 App</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>System Alerts</td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="system_email" <?php echo notifVal($notifications, 'System', 'email'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="system_browser" <?php echo notifVal($notifications, 'System', 'browser'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="system_app" <?php echo notifVal($notifications, 'System', 'app'); ?>></td>
          </tr>
          <tr>
            <td>Account Activity</td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="activity_email" <?php echo notifVal($notifications, 'Account', 'email'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="activity_browser" <?php echo notifVal($notifications, 'Account', 'browser'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="activity_app" <?php echo notifVal($notifications, 'Account', 'app'); ?>></td>
          </tr>
          <tr>
            <td>Reminders &amp; Deadlines</td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="reminder_email" <?php echo notifVal($notifications, 'Reminder', 'email'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="reminder_browser" <?php echo notifVal($notifications, 'Reminder', 'browser'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="reminder_app" <?php echo notifVal($notifications, 'Reminder', 'app'); ?>></td>
          </tr>
          <tr>
            <td>AI Nudges &amp; Insights</td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="ai_email" <?php echo notifVal($notifications, 'AI_Nudge', 'email'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="ai_browser" <?php echo notifVal($notifications, 'AI_Nudge', 'browser'); ?>></td>
            <td class="text-center"><input class="form-check-input" type="checkbox" name="ai_app" <?php echo notifVal($notifications, 'AI_Nudge', 'app'); ?>></td>
          </tr>
        </tbody>
      </table>

      <div class="mt-4">
        <label for="sendNotification" class="form-label fw-bold">Notification Frequency</label>
        <select id="sendNotification" name="sendNotification" class="form-select mb-3">
          <option value="Anytime" <?php if ($currentFreq == 'Anytime') echo 'selected'; ?>>Anytime</option>
          <option value="OnlineOnly" <?php if ($currentFreq == 'OnlineOnly') echo 'selected'; ?>>Only when I'm online</option>
          <option value="DailyDigest" <?php if ($currentFreq == 'DailyDigest') echo 'selected'; ?>>Daily Summary</option>
          <option value="WeeklySummary" <?php if ($currentFreq == 'WeeklySummary') echo 'selected'; ?>>Weekly Summary</option>
        </select>

        <button type="submit" class="btn btn-primary me-2">Save changes</button>
        <button type="reset" class="btn btn-outline-secondary">Discard</button>
      </div>
    </form>
  </div>
</div>
