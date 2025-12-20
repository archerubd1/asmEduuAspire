<?php
/**
 *  Astraal LXP - save_deactivate.php
 * Handles account deactivation
 * PHP 5.4 compatible
 */

mysqli_query($coni, "UPDATE learners SET status = 'inactive' WHERE user_id = $phx_user_id");
mysqli_query($coni, "UPDATE users SET active = 0 WHERE id = $phx_user_id");

echo json_encode(array('status' => 'deactivated', 'message' => 'Account deactivated.'));
exit;
?>
