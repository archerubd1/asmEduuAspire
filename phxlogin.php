<?php
/**
 * Phoenix LXP - Login Page (Aligned with eFront Users Table)
 * PHP 5.4 Compatible (UwAmp + GoDaddy)
 */

require_once('config.php');
$page = "lxp";
include_once('head-nav.php');

// -------------------------------------------------------------
// ✅ PHP 5.4-Safe CSRF Token Generator
// -------------------------------------------------------------
if (empty($_SESSION['csrf_token'])) {
    if (function_exists('random_bytes')) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    } else {
        $_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
    }
}

// -------------------------------------------------------------
// ✅ SweetAlert Message Handling
// -------------------------------------------------------------
function phx_decode($param) {
    return isset($_GET[$param]) ? base64_decode(urldecode($_GET[$param])) : '';
}

$successMessage = phx_decode('msg');
$errorMessage   = phx_decode('error');

if ($successMessage) {
    echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire("Login Successful!", "' . addslashes($successMessage) . '", "success");
            history.replaceState({}, document.title, window.location.pathname);
        });
    </script>';
}
if ($errorMessage) {
    echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire("Login Failed!", "' . addslashes($errorMessage) . '", "error");
            history.replaceState({}, document.title, window.location.pathname);
        });
    </script>';
}
?>

<!-- ================== PAGE HEADER ================== -->
<section id="main-banner-page" class="position-relative page-header service-detail-header section-nav-smooth parallax"
    style="background-size: 20% auto; background-repeat: no-repeat; background-position: center;">
    <div class="overlay overlay-dark opacity-7 z-index-1"></div>
    <div class="container">
        <div class="row"><div class="col-lg-8 offset-lg-2"><p><br><br></p></div></div>
        <div class="gradient-bg title-wrap">
            <div class="row">
                <div class="col-lg-12 col-md-12 whitecolor">
                    <h3 class="float-left">Sign In / Sign Up</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                       Sign in to your edu5.0 Learning Experience Portal
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================== LOGIN SECTION ================== -->
<section id="our-services" class="padding bglight">
    <div class="container">
        <div class="row whitebox shadow-lg rounded-lg p-4">

            <!-- Left Image -->
            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0 d-flex align-items-center justify-content-center">
                <img src="images/career-solution.jpg" alt="PHX Platform Login" class="img-fluid rounded-lg shadow-sm">
            </div>

            <!-- Login Form -->
            <div class="col-lg-7 col-md-6">
                <div class="heading_space text-center text-md-left mb-4">
                    <h3 class="darkcolor font-normal">Login to <span class="text-primary">edu5.0 LXP</span></h3>
                    <p class="text-muted">Access your personalized dashboard and courses securely.</p>
                </div>

                <form action="verifyLogin.php" method="post" onsubmit="RememberMe();" class="wow fadeInUp" data-wow-delay="200ms" novalidate>
                    <input type="hidden" name="processLogin" value="loginProcess">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <!-- Username / Email -->
                    <div class="form-group">
                        <label for="username" class="text-secondary">
                            <i class="fa fa-user mr-1"></i> Username or Email
                        </label>
                        <input type="text" id="username" name="username" class="form-control"
                               placeholder="Enter your username or email" required autofocus>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="text-secondary">
                            <i class="fa fa-lock mr-1"></i> Password
                        </label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Enter your password" required>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="newWorkflowLOGIN" name="newWorkflowLOGIN" class="btn btn-primary btn-block">
                        <i class="fa fa-sign-in mr-1"></i> Sign In
                    </button>

                    <div class="text-center mt-3">
                        <a href="forgot-password.php" class="text-secondary small">Forgot Password?</a>
                    </div>
                </form>

                <!-- Register Link -->
                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Don’t have an account?
                        <a href="register.php" class="text-primary font-weight-bold">Register Here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (file_exists('footer.php')) include_once('footer.php'); ?>

<!-- ================== JS ================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/main.js"></script>
<script type="text/javascript">
function RememberMe() {
    var username = document.getElementById("username").value;
    var remember = document.getElementById("remember").checked;
    if (remember && username) {
        localStorage.setItem("phx_user", username);
    } else {
        localStorage.removeItem("phx_user");
    }
}

window.onload = function() {
    var storedUser = localStorage.getItem("phx_user");
    if (storedUser) {
        document.getElementById("username").value = storedUser;
        document.getElementById("remember").checked = true;
    }
};
</script>
