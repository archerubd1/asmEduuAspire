<?php
$page = "lxp";
include_once('head-nav.php');
include_once('config.php');

// Check for success or informational message and display SweetAlert if exists
if (isset($_REQUEST['msg'])) {
    $successMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("Successful!", "' . $successMessage . '", "success");
                // Remove the message from the URL without reloading the page
                var urlWithoutMsg = window.location.origin + window.location.pathname;
                history.replaceState({}, document.title, urlWithoutMsg);
            });
          </script>';
}

// Check for error message and display SweetAlert if exists
if (isset($_REQUEST['error'])) {
    $errorMessage = base64_decode(urldecode($_GET['error']));
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                swal.fire("Invalid Login!!", "' . $errorMessage . '", "error");
                // Remove the message from the URL without reloading the page
                var urlWithoutError = window.location.origin + window.location.pathname;
                history.replaceState({}, document.title, urlWithoutError);
            });
          </script>';
}
?>


<!--Page Header-->
<section id="main-banner-page" class="position-relative page-header service-detail-header section-nav-smooth parallax" 
         style="background-size: 20% auto; background-repeat: no-repeat; background-position: center;">
    <div class="overlay overlay-dark opacity-7 z-index-1"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2"><p><br><br></p></div>
        </div>
        <div class="gradient-bg title-wrap">
            <div class="row">
                <div class="col-lg-12 col-md-12 whitecolor">
                    <h3 class="float-left">Recover Password</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                       Forgot Password
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Forgot Password Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            <!-- Image Section -->
            <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 400px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100" src="images/forgot-password.jpg" alt="EduuAspire LXP Forgot Password" style="object-fit: cover;">
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-7">
                <div class="section-title position-relative mb-4">
                    <h6 class="d-inline-block position-relative text-secondary text-uppercase pb-2">Forgot Password</h6>
                    <h1 class="display-4"></h1>
                </div>
                <p>Reset your password to regain access to your personalized learning experience on EduuAspire LXP.</p>

                <div class="forgot-password-form">
                    <form action="verifyForgotPassword.php" method="post">
					<!-- Hidden Input -->
					<input type="hidden" name="processType" value="ForgotPassword">
                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="text-secondary"><i class="fas fa-envelope"></i> Registered Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your registered email address" required>
                        </div>

                        <!-- Submit Button -->
                        <button id="resetPassword" name="resetPassword" type="submit" class="btn btn-primary btn-block">Reset Password</button>

                        <!-- Back to Login Link -->
                        <div class="text-center mt-3">
                            <a href="phxlogin.php" class="text-secondary">Back to Login</a>
                        </div>
                    </form>
                </div>

                <!-- Optional Register Section -->
                <div class="register-section text-center mt-4">
                    <p class="text-secondary">Don't have an account? <a href="register.php" class="text-primary">Register Now</a></p>
                </div>

                <!-- Statistics Section -->
                <div class="row pt-3 mx-0">
                    <!-- Registered Users -->
                    <div class="col-3 px-0">
                        <div class="bg-success text-center p-4">
                            <h1 class="text-white" data-toggle="counter-up">9,640</h1>
                            <h6 class="text-uppercase text-white">Registered<span class="d-block">Users</span></h6>
                        </div>
                    </div>

                    <!-- Daily Logins -->
                    <div class="col-3 px-0">
                        <div class="bg-primary text-center p-4">
                            <h1 class="text-white" data-toggle="counter-up">1,340</h1>
                            <h6 class="text-uppercase text-white">Daily<span class="d-block">Logins</span></h6>
                        </div>
                    </div>

                    <!-- Courses Available -->
                    <div class="col-3 px-0">
                        <div class="bg-secondary text-center p-4">
                            <h1 class="text-white" data-toggle="counter-up">500</h1>
                            <h6 class="text-uppercase text-white">Available<span class="d-block">Courses</span></h6>
                        </div>
                    </div>

                    <!-- Support Satisfaction -->
                    <div class="col-3 px-0">
                        <div class="bg-warning text-center p-4">
                            <h1 class="text-white" data-toggle="counter-up">918</h1>
                            <h6 class="text-uppercase text-white">5-Star<span class="d-block">Ratings</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Forgot Password End -->















   
<?php

// Debug include
if (file_exists('footer.php')) {
    include_once('footer.php');
   // echo "footer.php included successfully.";
} else {
   // echo "Error: footer.php not found.";
}
?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>