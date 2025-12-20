<?php
/**
 *  Astraal LXP - Learner Profile (Account Tab)
 * PHP 5.4 / UwAmp / GoDaddy Compatible
 */

$phx_user_id = isset($phx_user_id) ? (int)$phx_user_id : 0;

// -----------------------------------------------------------------------------
// FETCH from USERS table (core identity)
// -----------------------------------------------------------------------------
$userQuery = "
  SELECT name, surname, email 
  FROM users 
  WHERE login = '" . mysqli_real_escape_string($coni, $phx_user_login) . "'
  LIMIT 1
";
$userResult = mysqli_query($coni, $userQuery);
$userData   = mysqli_fetch_assoc($userResult);

$userName    = isset($userData['name']) ? $userData['name'] : '';
$userSurname = isset($userData['surname']) ? $userData['surname'] : '';
$userEmail   = isset($userData['email']) ? $userData['email'] : '';

// -----------------------------------------------------------------------------
// FETCH from LEARNERS table (extended details)
// -----------------------------------------------------------------------------
$learnerQuery = "
  SELECT nationality, timezone, gender, dob, city, state, country, language,
         phone, organization, profile_photo, address, zip, currency, status
  FROM learners 
  WHERE user_id = " . (int)$phx_user_id . "
  LIMIT 1
";
$learnerResult = mysqli_query($coni, $learnerQuery);
$learnerData   = mysqli_fetch_assoc($learnerResult);

// Safe assignments
$nationality  = isset($learnerData['nationality']) ? $learnerData['nationality'] : '';
$timezone     = isset($learnerData['timezone']) ? $learnerData['timezone'] : 'Asia/Kolkata';
$gender       = isset($learnerData['gender']) ? $learnerData['gender'] : '';
$dob          = isset($learnerData['dob']) ? $learnerData['dob'] : '';
$city         = isset($learnerData['city']) ? $learnerData['city'] : '';
$state        = isset($learnerData['state']) ? $learnerData['state'] : '';
$country      = isset($learnerData['country']) ? $learnerData['country'] : 'India';
$language     = isset($learnerData['language']) ? $learnerData['language'] : 'en';
$phone        = isset($learnerData['phone']) ? $learnerData['phone'] : '';
$organization = isset($learnerData['organization']) ? $learnerData['organization'] : '';
$address      = isset($learnerData['address']) ? $learnerData['address'] : '';
$zip          = isset($learnerData['zip']) ? $learnerData['zip'] : '';
$currency     = isset($learnerData['currency']) ? $learnerData['currency'] : 'INR';

// -----------------------------------------------------------------------------
// PROFILE PHOTO LOGIC
// -----------------------------------------------------------------------------
$photoFile = isset($learnerData['profile_photo']) ? $learnerData['profile_photo'] : '';
if (!empty($photoFile) && file_exists('../../uploads/' . $photoFile)) {
  $photoSrc = '../../uploads/' . $photoFile;
} else {
  $photoSrc = '../assets/img/avatars/1.png';
}
?>

<!-- ========================================================= -->
<!-- ACCOUNT TAB (Sneat Layout) -->
<!-- ========================================================= -->
<div class="card">
  <h5 class="card-header">Profile Details</h5>

  <div class="card-body">
    <div class="d-flex align-items-start align-items-sm-center gap-4">
      <img src="<?php echo htmlspecialchars($photoSrc); ?>" 
           alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
      <div class="button-wrapper">
        <label for="upload" class="btn btn-primary me-2 mb-4">
          <span class="d-none d-sm-block">Upload new photo</span>
          <i class="bx bx-upload d-block d-sm-none"></i>
          <input type="file" id="upload" name="upload" hidden accept="image/png, image/jpeg, image/gif" />
        </label>
        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
          <span class="d-none d-sm-block">Reset</span>
        </button>
        <p class="text-muted mb-0">Allowed JPG, PNG, or GIF. Max 800KB</p>
      </div>
    </div>
  </div>

  <div class="card-body">
    <form id="formAccountSettings" method="POST" enctype="multipart/form-data">
      <div class="row">

        <!-- name, surname, email come from USERS -->
        <div class="mb-3 col-md-6">
          <label for="firstName" class="form-label">First Name</label>
          <input class="form-control" type="text" id="firstName" name="firstName"
                 value="<?php echo htmlspecialchars($userName); ?>" autofocus />
        </div>

        <div class="mb-3 col-md-6">
          <label for="lastName" class="form-label">Last Name</label>
          <input class="form-control" type="text" id="lastName" name="lastName"
                 value="<?php echo htmlspecialchars($userSurname); ?>" />
        </div>

        <div class="mb-3 col-md-6">
          <label for="email" class="form-label">E-mail</label>
          <input class="form-control" type="text" id="email" name="email"
                 value="<?php echo htmlspecialchars($userEmail); ?>" />
        </div>

        <!-- Extended learner fields -->
        <div class="mb-3 col-md-6">
          <label for="gender" class="form-label">Gender</label>
          <select id="gender" name="gender" class="form-select">
            <option value="">Select</option>
            <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label for="dob" class="form-label">Date of Birth</label>
          <input class="form-control" type="date" id="dob" name="dob"
                 value="<?php echo htmlspecialchars($dob); ?>" />
        </div>

        <div class="mb-3 col-md-6">
          <label for="organization" class="form-label">Organization</label>
          <input type="text" class="form-control" id="organization" name="organization"
                 value="<?php echo htmlspecialchars($organization); ?>" placeholder="Organization" />
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label" for="phone">Phone Number</label>
          <div class="input-group input-group-merge">
            <span class="input-group-text">IN (+91)</span>
            <input type="text" id="phone" name="phone" class="form-control"
                   value="<?php echo htmlspecialchars($phone); ?>" placeholder="9876543210" />
          </div>
        </div>

        <div class="mb-3 col-md-6">
          <label for="address" class="form-label">Address</label>
          <input type="text" class="form-control" id="address" name="address"
                 value="<?php echo htmlspecialchars($address); ?>" placeholder="Address" />
        </div>

        <div class="mb-3 col-md-6">
          <label for="city" class="form-label">City</label>
          <input type="text" class="form-control" id="city" name="city"
                 value="<?php echo htmlspecialchars($city); ?>" placeholder="City" />
        </div>

        <div class="mb-3 col-md-6">
          <label for="state" class="form-label">State</label>
          <input class="form-control" type="text" id="state" name="state"
                 value="<?php echo htmlspecialchars($state); ?>" placeholder="State" />
        </div>

        <div class="mb-3 col-md-6">
          <label for="zip" class="form-label">ZIP Code</label>
          <input type="text" class="form-control" id="zip" name="zip"
                 maxlength="6" value="<?php echo htmlspecialchars($zip); ?>" placeholder="403001" />
        </div>

        <div class="mb-3 col-md-6">
          <label for="country" class="form-label">Country</label>
          <select id="country" name="country" class="form-select">
            <option value="India" <?php if ($country == 'India') echo 'selected'; ?>>India</option>
            <option value="USA" <?php if ($country == 'USA') echo 'selected'; ?>>United States</option>
            <option value="UK" <?php if ($country == 'UK') echo 'selected'; ?>>United Kingdom</option>
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label for="language" class="form-label">Language</label>
          <select id="language" name="language" class="form-select">
            <option value="en" <?php if ($language == 'en') echo 'selected'; ?>>English</option>
            <option value="hi" <?php if ($language == 'hi') echo 'selected'; ?>>Hindi</option>
            <option value="kn" <?php if ($language == 'kn') echo 'selected'; ?>>Kannada</option>
            <option value="mr" <?php if ($language == 'mr') echo 'selected'; ?>>Marathi</option>
            <option value="ko" <?php if ($language == 'ko') echo 'selected'; ?>>Konkani</option>
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label for="timezone" class="form-label">Timezone</label>
          <select id="timezone" name="timezone" class="form-select">
            <option value="Asia/Kolkata" <?php if ($timezone == 'Asia/Kolkata') echo 'selected'; ?>>Asia/Kolkata (IST)</option>
            <option value="Asia/Dubai" <?php if ($timezone == 'Asia/Dubai') echo 'selected'; ?>>Asia/Dubai (GST)</option>
          </select>
        </div>

        <div class="mb-3 col-md-6">
          <label for="currency" class="form-label">Currency</label>
          <select id="currency" name="currency" class="form-select">
            <option value="INR" <?php if ($currency == 'INR') echo 'selected'; ?>>INR (₹)</option>
            <option value="USD" <?php if ($currency == 'USD') echo 'selected'; ?>>USD ($)</option>
          </select>
        </div>

      </div>

      <button type="submit" class="btn btn-primary me-2">Save changes</button>
      <button type="reset" class="btn btn-outline-secondary">Cancel</button>
    </form>
  </div>
</div>
