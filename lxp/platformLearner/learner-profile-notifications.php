<?php
/**
 *  Astraal LXP Learner Profile - Modular (PHP 5.4 Compatible)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

// Session Data
$phx_user_id    = isset($_SESSION['phx_user_id']) ? (int)$_SESSION['phx_user_id'] : 0;
$phx_user_login = isset($_SESSION['phx_user_login']) ? $_SESSION['phx_user_login'] : '';
$phx_user_name  = isset($_SESSION['phx_user_name']) ? $_SESSION['phx_user_name'] : '';

// Fetch User Info
$userQuery = "
  SELECT name, surname, email 
  FROM users 
  WHERE login = '" . mysqli_real_escape_string($coni, $phx_user_login) . "'
  LIMIT 1
";
$userResult = mysqli_query($coni, $userQuery);
$userData = mysqli_fetch_assoc($userResult);

$userName    = isset($userData['name']) ? $userData['name'] : '';
$userSurname = isset($userData['surname']) ? $userData['surname'] : '';
$userEmail   = isset($userData['email']) ? $userData['email'] : '';

$page = "profile";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
  <?php require_once('learnersNav.php'); ?>
  
  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="card border-0 shadow-sm p-4">

        <!-- ========================= -->
        <!-- ✅ Page Title -->
        <!-- ========================= -->
        <h4 class="fw-bold mb-4 text-start">
          <i class="fa-solid fa-user-circle text-primary me-2"></i>
          <span class="text-muted fw-light"><?php echo htmlspecialchars($userName); ?></span>
          My Profile
        </h4>

        <!-- ========================= -->
        <!-- ✅ Tabs Navigation -->
        <!-- ========================= -->
        <ul class="nav nav-pills flex-column flex-md-row align-items-start mb-4 ps-1" style="gap:25px;">
         
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="pill" href="#notifications">
              <i class="fa-solid fa-bell me-2"></i> Notification Preferences
            </a>
          </li>
         
        </ul>

        <!-- ========================= -->
        <!-- ✅ Tab Content -->
        <!-- ========================= -->
        <div class="tab-content text-start">
          
          

          <!-- Notifications -->
          <div class="tab-pane fade show active" id="notifications">
            
              <?php include('notifications.php'); ?>
           
          </div>

         

        </div>
      </div>
    </div>
  
  
  
  


<script src="profile.js"></script>
<?php require_once('../platformFooter.php'); ?>

<!-- ========================= -->
<!-- ✅ Style Alignment Tweaks -->
<!-- ========================= -->
<style>
  .content-wrapper {
    text-align: left !important;
  }
  .card {
    text-align: left;
  }
  .nav-pills .nav-link {
    border-radius: 6px;
    padding: 8px 16px;
  }
  .nav-pills .nav-link i {
    font-size: 1rem;
  }
  .nav-pills .nav-link.active {
    background-color: var(--bs-primary);
    color: #fff;
  }
  .nav-pills .nav-link:hover {
    background-color: #e9ecef;
  }
</style>
