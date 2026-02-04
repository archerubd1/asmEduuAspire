<?php
/**
 * Astraal LXP - Learner Profile (Account)
 * PHP 5.4 Compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

// --------------------------------------------------
// Session
// --------------------------------------------------
$phx_user_id    = isset($_SESSION['phx_user_id']) ? (int)$_SESSION['phx_user_id'] : 0;
$phx_user_login = isset($_SESSION['phx_user_login']) ? $_SESSION['phx_user_login'] : '';

// --------------------------------------------------
// Fetch User Info
// --------------------------------------------------
$userSql = "
    SELECT name, surname, email
    FROM users
    WHERE login = ?
    LIMIT 1
";

$stmt = mysqli_prepare($coni, $userSql);
mysqli_stmt_bind_param($stmt, "s", $phx_user_login);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $userName, $userSurname, $userEmail);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Page context
$page = "profile";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="card border-0 shadow-sm p-4">

        <!-- ========================= -->
        <!-- Page Title -->
        <!-- ========================= -->
        <h4 class="fw-bold mb-4 text-start">
          <i class="fa-solid fa-user-circle text-primary me-2"></i>
          <span class="text-muted fw-light">
            <?php echo htmlspecialchars($userName); ?>
          </span>
          My Account Profile
        </h4>

        <!-- ========================= -->
        <!-- Account Section ONLY -->
        <!-- ========================= -->
        <?php require_once('account.php'); ?>

      </div>
    </div>
  
<script src="profile.js"></script>
<?php require_once('../platformFooter.php'); ?>

<style>
  .content-wrapper {
    text-align: left !important;
  }
  .card {
    text-align: left;
  }
</style>
