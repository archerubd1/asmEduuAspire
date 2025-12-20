<?php
/**
 *  Astraal LXP - save_account.php
 * Handles account tab updates
 * PHP 5.4 compatible
 */

mysqli_set_charset($coni, 'utf8mb4');

function safe($val, $conn) {
    return mysqli_real_escape_string($conn, trim($val));
}

$photoFileName = null;

// --- Profile Photo Upload ---
if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    $file_type     = $_FILES['upload']['type'];
    $file_size     = $_FILES['upload']['size'];

    if (in_array($file_type, $allowed_types) && $file_size <= 800 * 1024) {
        $ext = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
        $photoFileName = 'profile_' . $phx_user_id . '_' . time() . '.' . $ext;
        $uploadDir = '../../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        move_uploaded_file($_FILES['upload']['tmp_name'], $uploadDir . $photoFileName);
    }
}

// --- Sanitize inputs ---
$firstName    = safe($_POST['firstName'], $coni);
$lastName     = safe($_POST['lastName'], $coni);
$email        = safe($_POST['email'], $coni);
$organization = safe($_POST['organization'], $coni);
$phone        = safe($_POST['phone'], $coni);
$address      = safe($_POST['address'], $coni);
$city         = safe($_POST['city'], $coni);
$state        = safe($_POST['state'], $coni);
$zip          = safe($_POST['zip'], $coni);
$country      = safe($_POST['country'], $coni);
$language     = safe($_POST['language'], $coni);
$timezone     = safe($_POST['timezone'], $coni);
$currency     = safe($_POST['currency'], $coni);
$gender       = safe($_POST['gender'], $coni);
$dob          = safe($_POST['dob'], $coni);

// --- Update USERS table ---
$userUpdate = "
    UPDATE users
    SET name = '$firstName',
        surname = '$lastName',
        email = '$email'
    WHERE id = $phx_user_id
    LIMIT 1
";
mysqli_query($coni, $userUpdate);

// --- Update LEARNERS table ---
$learnerUpdate = "
    UPDATE learners
    SET organization = '$organization',
        phone = '$phone',
        address = '$address',
        city = '$city',
        state = '$state',
        zip = '$zip',
        country = '$country',
        language = '$language',
        timezone = '$timezone',
        currency = '$currency',
        gender = '$gender',
        dob = '$dob'
        " . ($photoFileName ? ", profile_photo = '$photoFileName'" : "") . "
    WHERE user_id = $phx_user_id
    LIMIT 1
";
mysqli_query($coni, $learnerUpdate);

echo json_encode(array('status' => 'success', 'message' => 'Profile updated successfully.'));
exit;
?>
