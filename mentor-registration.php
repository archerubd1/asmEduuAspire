<?php
/**
 * EduuAspire – Mentor Registration
 * PHP 5.4 Safe | LXP-aligned Mentor Onboarding
 */

require_once('config.php');
$page = "mentor-registration";
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
        Swal.fire('Application Submitted','{$successMessage}','success');
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

<!-- ================== PAGE HEADER ================== -->
<section id="main-banner-page" class="position-relative page-header service-detail-header section-nav-smooth parallax">
    <div class="overlay overlay-dark opacity-7 z-index-1"></div>
    <div class="container">
        <div class="row"><div class="col-lg-8 offset-lg-2"><p><br><br></p></div></div>
        <div class="gradient-bg title-wrap">
            <div class="row">
                <div class="col-lg-12 whitecolor">
                    <h3 class="float-left">Join EduuAspire as a Mentor</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                        Mentor Application & Verification
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================= FORM ================= -->
<section class="padding bglight">
<div class="container">
<div class="row whitebox shadow-lg rounded-lg p-4">
<div class="col-lg-12">

<form action="process-mentor-registration.php" method="post" novalidate>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
<input type="hidden" name="source_page" value="<?php echo $_SERVER['REQUEST_URI']; ?>">

<!-- BASIC -->
<h5 class="mb-3"><i class="fa fa-user"></i> Basic Information</h5>
<div class="form-row">
<div class="form-group col-md-6">
<label>Full Name *</label>
<input type="text" name="full_name" class="form-control" required>
</div>
<div class="form-group col-md-6">
<label>Email *</label>
<input type="email" name="email" class="form-control" required>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label>Phone</label>
<input type="text" name="phone" class="form-control">
</div>
<div class="form-group col-md-6">
<label>Location</label>
<input type="text" name="location" class="form-control">
</div>
</div>

<hr>

<!-- PROFESSIONAL -->
<h5 class="mb-3"><i class="fa fa-briefcase"></i> Professional Snapshot</h5>
<div class="form-row">
<div class="form-group col-md-6">
<label>Primary Profession</label>
<input type="text" name="primary_profession" class="form-control">
</div>
<div class="form-group col-md-6">
<label>Total Experience (Years)</label>
<input type="number" name="total_experience_years" class="form-control">
</div>
</div>

<div class="form-row">
<div class="form-group col-md-6">
<label>Current Organization</label>
<input type="text" name="current_organization" class="form-control">
</div>
<div class="form-group col-md-6">
<label>Highest Qualification</label>
<input type="text" name="highest_qualification" class="form-control">
</div>
</div>

<hr>

<!-- OFFERINGS -->
<h5 class="mb-3"><i class="fa fa-sitemap"></i> EduuAspire Offering Alignment *</h5>
<div class="row">
<div class="col-md-6">
<label><input type="checkbox" name="mentor_offerings[]" value="home_tutoring"> Home Tutoring</label><br>
<label><input type="checkbox" name="mentor_offerings[]" value="career_mentoring"> Career Mentoring</label><br>
<label><input type="checkbox" name="mentor_offerings[]" value="pre_corporate"> Pre-Corporate & Employability</label><br>
<label><input type="checkbox" name="mentor_offerings[]" value="faculty_mentoring"> Faculty Mentoring</label>
</div>
<div class="col-md-6">
<label><input type="checkbox" name="mentor_offerings[]" value="ai_genai"> AI & GenAI Skills</label><br>
<label><input type="checkbox" name="mentor_offerings[]" value="human_skills"> Human Skills (21.0)</label><br>
<label><input type="checkbox" name="mentor_offerings[]" value="entrepreneurship"> Entrepreneurship</label><br>
<label><input type="checkbox" name="mentor_offerings[]" value="lifelong_learning"> Lifelong Learning</label>
</div>
</div>

<hr>

<!-- LEVEL -->
<h5 class="mb-3"><i class="fa fa-chart-line"></i>
 Mentor Capability Level *</h5>
<select name="mentor_level" class="form-control" required>
<option value="">Select</option>
<option value="subject_tutor">Subject Tutor</option>
<option value="career_mentor">Career Mentor</option>
<option value="industry_mentor">Industry Mentor</option>
<option value="thought_partner">Thought Partner</option>
</select>

<hr>

<!-- DELIVERY -->
<h5 class="mb-3"><i class="fa fa-cogs"></i>
 Delivery Formats</h5>
<label><input type="checkbox" name="delivery_formats[]" value="1to1"> 1:1 Mentoring</label><br>
<label><input type="checkbox" name="delivery_formats[]" value="group"> Small Group</label><br>
<label><input type="checkbox" name="delivery_formats[]" value="masterclass"> Masterclass</label><br>
<label><input type="checkbox" name="delivery_formats[]" value="project"> Project-Based</label>

<hr>

<!-- AVAILABILITY -->
<h5 class="mb-3"><i class="fa fa-clock"></i> Availability</h5>
<div class="form-row">
<div class="form-group col-md-4">
<label>Days / Week</label>
<input type="number" name="days_per_week" class="form-control">
</div>
<div class="form-group col-md-4">
<label>Hours / Day</label>
<input type="number" step="0.5" name="hours_per_day" class="form-control">
</div>
<div class="form-group col-md-4">
<label>Preferred Days</label>
<select name="preferred_weekdays" class="form-control">
<option>Weekdays</option>
<option>Weekends</option>
<option>Flexible</option>
</select>
</div>
</div>

<hr>

<!-- COMMERCIAL -->
<h5 class="mb-3"><i class="fa fa-rupee-sign"></i> Commercial Readiness</h5>
<div class="form-row">
<div class="form-group col-md-6">
<label>Expected Hourly Rate</label>
<input type="number" step="0.01" name="expected_hourly_rate" class="form-control">
</div>
<div class="form-group col-md-6">
<label>Open to Revenue Share?</label>
<select name="open_to_revenue_share" class="form-control">
<option value="yes">Yes</option>
<option value="no">No</option>
</select>
</div>
</div>

<hr>

<!-- ALIGNMENT -->
<h5 class="mb-3"><i class="fa fa-bullseye"></i> Platform Alignment</h5>
<textarea name="onboarding_intent" class="form-control" rows="3"
placeholder="Why do you want to mentor with EduuAspire?"></textarea>

<div class="form-row mt-3">
<div class="form-group col-md-6">
<label>LinkedIn Profile</label>
<input type="text" name="linkedin_profile" class="form-control">
</div>
<div class="form-group col-md-6">
<label>Portfolio / Website</label>
<input type="text" name="portfolio_link" class="form-control">
</div>
</div>

<button type="submit" class="btn btn-primary btn-block mt-4">
<i class="fa fa-paper-plane"></i> Submit Mentor Application
</button>

</form>

</div>
</div>
</div>
</section>

<?php include_once('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>