<?php
/**
 * Astraal LXP - Registration Page (Core Fields Only)
 * Compatible with eFront 3.15 users table
 */

include_once('config.php'); // ✅ Start session and connect DB
$page = "lxp";
include_once('head-nav.php'); // ✅ Then include UI header

// Handle SweetAlert Messages
if (isset($_REQUEST['msg'])) {
    $successMessage = base64_decode(urldecode($_GET['msg']));
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire("Registration Successful!", "' . htmlspecialchars($successMessage, ENT_QUOTES) . '", "success");
            history.replaceState({}, document.title, window.location.origin + window.location.pathname);
        });
    </script>';
}

if (isset($_REQUEST['error'])) {
    $errorMessage = base64_decode(urldecode($_GET['error']));
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire("Registration Failed!", "' . htmlspecialchars($errorMessage, ENT_QUOTES) . '", "error");
            history.replaceState({}, document.title, window.location.origin + window.location.pathname);
        });
    </script>';
}
?>

<!-- ===========================================================
     PAGE HEADER SECTION
=========================================================== -->
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
                    <h3 class="float-left">Sign Up / Register</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                        Create your PHX LXP account
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===========================================================
     REGISTRATION FORM SECTION
=========================================================== -->
<section id="our-services" class="padding bglight">
    <div class="container">
        <div class="row whitebox shadow-lg rounded-lg p-4">

            <!-- Left Image -->
            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0 d-flex align-items-center justify-content-center">
                <img src="images/career-solution.jpg" alt="PHX Platform Registration" class="img-fluid rounded-lg shadow-sm">
            </div>

            <!-- Registration Form -->
            <div class="col-lg-7 col-md-6">
                <div class="heading_space text-center text-md-left mb-4">
                    <h3 class="darkcolor font-normal">Register with <span class="text-primary">PHX LXP</span></h3>
                    <p class="text-muted">Join Phoenix LXP today! Fill in the required details below to create your learning account.</p>
                </div>

                <form action="verifyRegistration.php" method="post" class="wow fadeInUp" data-wow-delay="200ms" novalidate>
                    <input type="hidden" name="processType" value="RegistrationProcess">

                    <div class="row">
                        <!-- Full Name -->
                        <div class="form-group col-md-6">
                            <label><i class="fa fa-user mr-1"></i> Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group col-md-6">
                            <label><i class="fa fa-envelope mr-1"></i> Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Mobile -->
                        <div class="form-group col-md-6">
                            <label><i class="fa fa-phone mr-1"></i> Mobile Number</label>
                            <input type="text" name="mobile" class="form-control" placeholder="Enter your mobile number" required>
                        </div>

                        <!-- Username -->
                        <div class="form-group col-md-6">
                            <label><i class="fa fa-user mr-1"></i> Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="form-group col-md-6">
                            <label><i class="fa fa-lock mr-1"></i> Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter a secure password" required>
                        </div>

                        <!-- User Type -->
                        <div class="form-group col-md-6">
                            <label><i class="fa fa-users mr-1"></i> User Type</label>
                            <select name="usertype" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="student">Student</option>
                                <option value="professor">Instructor / Mentor</option>
                                <option value="professional">Professional</option>
                                <option value="institute">Institute</option>
                                <option value="corporate">Corporate</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label for="terms">I agree to the terms and conditions</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-user-plus mr-1"></i> Register
                    </button>

                    <!-- Login Redirect -->
                    <div class="text-center mt-3">
                        <p class="text-secondary">Already have an account? 
                            <a href="phxlogin.php" class="text-primary">Login Now</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if (file_exists('footer.php')) include_once('footer.php'); ?>

<!-- JS Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

