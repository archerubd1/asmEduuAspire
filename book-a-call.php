<?php
/**
 * EduuAspire – Book a Strategy Call
 * UI identical to phxlogin.php
 * PHP 5.4 compatible
 */

require_once('config.php');
$page = "book-call";
include_once('head-nav.php');

/* CSRF */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
}

/* SweetAlert helpers */
function phx_decode($param) {
    return isset($_GET[$param]) ? base64_decode(urldecode($_GET[$param])) : '';
}

$successMessage = phx_decode('msg');
$errorMessage   = phx_decode('error');

if ($successMessage) {
    echo "<script>
    document.addEventListener('DOMContentLoaded',function(){
        Swal.fire('Request Submitted','{$successMessage}','success');
        history.replaceState({}, document.title, window.location.pathname);
    });
    </script>";
}

if ($errorMessage) {
    echo "<script>
    document.addEventListener('DOMContentLoaded',function(){
        Swal.fire('Submission Failed','{$errorMessage}','error');
        history.replaceState({}, document.title, window.location.pathname);
    });
    </script>";
}
?>

<!-- ================== PAGE HEADER (UNCHANGED UI) ================== -->
<section id="main-banner-page" class="position-relative page-header service-detail-header section-nav-smooth parallax">
    <div class="overlay overlay-dark opacity-7 z-index-1"></div>
    <div class="container">
        <div class="row"><div class="col-lg-8 offset-lg-2"><p><br><br></p></div></div>
        <div class="gradient-bg title-wrap">
            <div class="row">
                <div class="col-lg-12 whitecolor">
                    <h3 class="float-left">Book a Call - together we CAN </h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                        Speak with our EduuAspire EdTech Experts
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================== FORM SECTION (SAME WRAPPER) ================== -->
<section id="our-services" class="padding bglight">
<div class="container">
<div class="row whitebox shadow-lg rounded-lg p-4">

<!-- Left Image (UNCHANGED) -->
<div class="col-lg-5 col-md-6 mb-4 mb-lg-0 d-flex align-items-center justify-content-center">
    <img src="images/eduu/callus.jpg" alt="Book Strategy Call" class="img-fluid rounded-lg shadow-sm">
</div>

<!-- Right Form -->
<div class="col-lg-7 col-md-6">
<div class="heading_space text-center text-md-left mb-4">
       <p class="text-muted">
        Discuss learning transformation, skilling, platforms & future readiness.
    </p>
</div>

<form action="process-book-call.php" method="post" class="wow fadeInUp" data-wow-delay="200ms" novalidate>

<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
<input type="hidden" name="source_page" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

<div class="form-group">
<label class="text-secondary">
<i class="fa fa-user mr-1"></i> Full Name
</label>
<input type="text" name="full_name" class="form-control" required>
</div>
<div class="form-row">
  <div class="form-group col-md-6">
    <label class="text-secondary">
      <i class="fa fa-envelope mr-1"></i> Email Address
    </label>
    <input type="email" name="email" class="form-control" required>
  </div>

  <div class="form-group col-md-6">
    <label class="text-secondary">
      <i class="fa fa-phone mr-1"></i> Mobile Number
    </label>
    <input type="text" name="phone" class="form-control">
  </div>
</div>


<div class="form-group">
<label class="text-secondary">
<i class="fa fa-building mr-1"></i> Organization / Institution
</label>
<input type="text" name="organization" class="form-control">
</div>
<div class="form-row">
  <div class="form-group col-md-6">
    <label class="text-secondary">
      <i class="fa fa-user-secret mr-1"></i> Your Role
    </label>
    <select name="role" class="form-control">
      <option value="">Select</option>
      <option>Institution Leader</option>
      <option>HR / L&D</option>
      <option>Faculty / Educator</option>
      <option>Learner / Professional</option>
      <option>Government / NGO</option>
    </select>
  </div>

  <div class="form-group col-md-6">
    <label class="text-secondary">
      <i class="fa fa-bullseye mr-1"></i> Area of Interest
    </label>
    <select name="interest_area" class="form-control">
      <option value="">Select</option>
      <option>Campus Transformation</option>
      <option>Employability & Train-to-Hire</option>
      <option>AI-Powered Learning</option>
      <option>EduuAspire Platform Demo</option>
      <option>Human Intelligence Programs</option>
    </select>
  </div>
</div>


<div class="form-group">
<label class="text-secondary">
<i class="fa fa-comment mr-1"></i> Message
</label>
<textarea name="message" class="form-control" rows="2"></textarea>
</div>

<button type="submit" class="btn btn-primary btn-block">
<i class="fa fa-calendar-check mr-1"></i> Request Strategy Call
</button>

</form>

</div>
</div>
</div>
</section>

<?php include_once('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
