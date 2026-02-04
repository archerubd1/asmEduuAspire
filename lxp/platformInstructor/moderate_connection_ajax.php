<?php
error_reporting(0);
header('Content-Type: application/json');

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id'])) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$faculty_id = (int)$_SESSION['phx_user_id'];

if (empty($_POST['connection_id']) || empty($_POST['action'])) {
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
    exit;
}

$connection_id = (int)$_POST['connection_id'];
$action = ($_POST['action'] === 'approve') ? 'approved' : 'rejected';

$comment = isset($_POST['comment'])
    ? mysqli_real_escape_string($coni, $_POST['comment'])
    : '';

$update = mysqli_query($coni,"
    UPDATE learner_connections
    SET status='$action',
        review_note='$comment',
        reviewed_by=$faculty_id,
        reviewed_on=NOW()
    WHERE connection_id=$connection_id
");

if (!$update) {
    echo json_encode([
        'status'=>'error',
        'message'=>'Database update failed'
    ]);
    exit;
}

echo json_encode([
    'status'=>'success',
    'new_status'=>$action
]);
exit;
