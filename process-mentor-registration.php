<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: mentor-registration.php');
    exit;
}

/* CSRF */
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: mentor-registration.php?error='.urlencode(base64_encode('Invalid session')));
    exit;
}

/* Helpers */
function clean($v) {
    return trim(strip_tags($v));
}

/* Arrays → CSV */
$mentor_offerings = isset($_POST['mentor_offerings'])
    ? implode(',', $_POST['mentor_offerings'])
    : '';

$delivery_formats = isset($_POST['delivery_formats'])
    ? implode(',', $_POST['delivery_formats'])
    : '';

/* Insert */
$stmt = $coni->prepare("
INSERT INTO eduuaspire_mentors (
full_name,email,phone,location,
primary_profession,total_experience_years,current_organization,highest_qualification,
mentor_offerings,mentor_level,delivery_formats,
days_per_week,hours_per_day,preferred_weekdays,
expected_hourly_rate,open_to_revenue_share,
onboarding_intent,linkedin_profile,portfolio_link,
source_page,ip_address
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
"sssssisisssiddsssssss",
clean($_POST['full_name']),
clean($_POST['email']),
clean($_POST['phone']),
clean($_POST['location']),
clean($_POST['primary_profession']),
$_POST['total_experience_years'],
clean($_POST['current_organization']),
clean($_POST['highest_qualification']),
$mentor_offerings,
$_POST['mentor_level'],
$delivery_formats,
$_POST['days_per_week'],
$_POST['hours_per_day'],
$_POST['preferred_weekdays'],
$_POST['expected_hourly_rate'],
$_POST['open_to_revenue_share'],
clean($_POST['onboarding_intent']),
clean($_POST['linkedin_profile']),
clean($_POST['portfolio_link']),
$_POST['source_page'],
$_SERVER['REMOTE_ADDR']
);

if ($stmt->execute()) {
    header('Location: mentor-registration.php?msg='.urlencode(base64_encode('Your application is under review.')));
} else {
    header('Location: mentor-registration.php?error='.urlencode(base64_encode('Submission failed')));
}
exit;
