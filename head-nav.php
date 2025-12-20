<?php
/**
 * Phoenix LXP — Head Navigation
 * PHP 5.4 Safe | UwAmp Local & GoDaddy Production
 */

if (!isset($base_url)) {
    $is_localhost = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));
    $base_url = $is_localhost ? 'http://localhost/eduuaspire.online' : 'https://eduuaspire.online';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>EduuAspire Powered By astraal Mind Solutions | converting potential to intellectual capital - Future Learning</title>
<link href="<?php echo $base_url; ?>/images/eduuFavicon.png" rel="icon">
<meta name="description" content="EduuAspire Powered by astraal mind empowers learners and professionals from campus to corporate.">
<meta name="author" content="EduuAspire Powered by astraal Mind Solutions">

<!-- CSS (absolute URLs for both localhost + GoDaddy) -->
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/all.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/animate.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/morphext.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/jquery.fancybox.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/tooltipster.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/cubeportfolio.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/revolution/navigation.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/revolution/settings.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/style.css">

<!-- Loader failsafe for PHP 5.4 browsers -->
<script type="text/javascript">
setTimeout(function(){
  var l=document.getElementsByClassName('loader');
  if(l.length){l[0].style.display='none';}
},4000);
</script>
</head>

<body>
<!-- PreLoader -->
<div class="loader">
  <div class="loader-inner"><div class="cssload-loader"></div></div>
</div>
<!-- Header -->
<header class="site-header" id="header">
  <nav class="navbar navbar-expand-lg transparent-bg static-nav">
    <div class="container">
      <a class="navbar-brand" href="<?php echo $base_url; ?>/index.php"><span class="brand-text"><img src="images/ealxp_logo.png" ></span></a>

      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
<?php
$page = isset($page) ? $page : '';
$futurePathwaysPages = array(
  'EdTech','lxp','adaptive-learning','ai-learning','immersive-classrooms',
  'curriculum-digital','ar-vr-learning','gamification','blockchain-edu',
  'cloud-platforms','data-driven','6-60-learning','on-demand',
  'career-counseling','resilience-programs','global-competency'
);
$isFuturePathwaysActive = in_array($page, $futurePathwaysPages);
?>
<!-- Home -->
<li class="nav-item"><a class="nav-link <?php echo ($page=='home')?'active':''; ?>" href="<?php echo $base_url; ?>/index.php">Home</a></li>

<!-- Future Pathways -->
<li class="nav-item dropdown static">
  <a class="nav-link dropdown-toggle <?php echo $isFuturePathwaysActive?'active':''; ?>" href="" data-toggle="dropdown">Future Pathways</a>
  <ul class="dropdown-menu megamenu" style="padding:15px;min-width:750px;">
    <li><div class="container" style="max-width:100%;">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
          <h6 style="font-weight:bold;">EdTech Solutions</h6>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/lxp.php">Learning Experience Platforms</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/adaptive-learning.php">Adaptive & Personalized Learning</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/ai-learning.php">AI-Powered Analytics</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/immersive-classrooms.php">Immersive Classrooms</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/curriculum-digital.php">Curriculum Digitization</a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
          <h6 style="font-weight:bold;">Future Tech in Education</h6>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/ar-vr-learning.php">AR/VR Learning</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/gamification.php">Gamification & Simulation</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/blockchain-edu.php">Blockchain Credentialing</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/cloud-platforms.php">Cloud Platforms</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/data-driven.php">Data-Driven Systems</a>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
          <h6 style="font-weight:bold;">Lifelong Learning & Support</h6>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/learning.php">Learning Ecosystem</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/on-demand.php">On-Demand Support</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/career-counseling.php">Career Counseling</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/resilience-programs.php">Resilience Programs</a>
          <a class="dropdown-item" href="<?php echo $base_url; ?>/global-competency.php">Global Competency</a>
        </div>
      </div>
    </div></li>
  </ul>
</li>

<!-- Programs & Services -->
<li class="nav-item dropdown static">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Programs & Services
  </a>
  <ul class="dropdown-menu megamenu" style="padding:15px; min-width:900px;">
    <li>
      <div class="container" style="max-width:100%;">
        <div class="row">
          
          <!-- Skilling & Employability -->
          <div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
            <h6 style="font-size:0.95rem; font-weight:bold; margin-bottom:8px; color:333;">
              <i>Skilling & Employability</i>
            </h6>
            <a class="dropdown-item" href="future-skills.php" style="font-size:0.82rem; white-space:normal;">21st Century & Future-Ready Skills</a>
            <a class="dropdown-item" href="bootcamps.php" style="font-size:0.82rem; white-space:normal;">Coding Bootcamps & Digital Fluency</a>
            <a class="dropdown-item" href="train-to-hire.php" style="font-size:0.82rem; white-space:normal;">Train-to-Hire & Campus Placements</a>
            <a class="dropdown-item" href="pathways.php" style="font-size:0.82rem; white-space:normal;">Career Pathways & Employability Labs</a>
            <a class="dropdown-item" href="entrepreneurship.php" style="font-size:0.82rem; white-space:normal;">Entrepreneurship & Start-up Readiness</a>
          </div>

          <!-- Campus-to-Corporate -->
          <div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
            <h6 style="font-size:0.95rem; font-weight:bold; margin-bottom:8px; color:333;">
              <i>Campus-to-Corporate</i>
            </h6>
            <a class="dropdown-item" href="pre-corporate.php" style="font-size:0.82rem; white-space:normal;">Pre-Corporate Readiness Programs</a>
            <a class="dropdown-item" href="soft-skills.php" style="font-size:0.82rem; white-space:normal;">Communication & Workplace Etiquette</a>
            <a class="dropdown-item" href="digital-workplace.php" style="font-size:0.82rem; white-space:normal;">Digital Workplace Skills</a>
            <a class="dropdown-item" href="internship-support.php" style="font-size:0.82rem; white-space:normal;">Internship & Industry Bridge</a>
            <a class="dropdown-item" href="post-campus.php" style="font-size:0.82rem; white-space:normal;">Post-Campus Transition Support</a>
          </div>

          <!-- Faculty Development -->
          <div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
            <h6 style="font-size:0.95rem; font-weight:bold; margin-bottom:8px; color:333;">
              <i>Faculty & Educator Development</i>
            </h6>
            <a class="dropdown-item" href="tet-refresher.php" style="font-size:0.82rem; white-space:normal;">TET Refresher & Pedagogy Excellence</a>
            <a class="dropdown-item" href="nep-refresher.php" style="font-size:0.82rem; white-space:normal;">NEP 2020 Aligned Refresher Programs</a>
            <a class="dropdown-item" href="cpd.php" style="font-size:0.82rem; white-space:normal;">Continuous Professional Development</a>
            <a class="dropdown-item" href="academic-support.php" style="font-size:0.82rem; white-space:normal;">Academic & Research Mentorship</a>
            <a class="dropdown-item" href="innovation-teaching.php" style="font-size:0.82rem; white-space:normal;">Innovation in Teaching Practices</a>
          </div>

        </div>
      </div>
    </li>
  </ul>
</li>


<!-- Brain Decode -->
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Brain's Gym</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:300px; left:0;">
    <a class="dropdown-item" href="blindfold.php" style="font-size:0.85rem; white-space:normal;">Blindfold Experience</a>
    <a class="dropdown-item" href="mind-gym.php" style="font-size:0.85rem; white-space:normal;">Mind Gym Programs</a>
    <a class="dropdown-item" href="super-memory.php" style="font-size:0.85rem; white-space:normal;">Super Memory Training</a>
    <a class="dropdown-item" href="neuro-habits.php" style="font-size:0.85rem; white-space:normal;">Neuro Habits & Cognitive Mastery</a>
  </ul>
</li>
<!-- About Us -->
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">About Us</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:300px; left:0;">
    <a class="dropdown-item" href="collaborations.php" style="font-size:0.85rem; white-space:normal;">Collaborations & Partnerships</a>
    <a class="dropdown-item" href="who-we-are.php" style="font-size:0.85rem; white-space:normal;">Who We Are</a>
    <a class="dropdown-item" href="leadership-team.php" style="font-size:0.85rem; white-space:normal;">Leadership Team</a>
  </ul>
</li>

<!-- Resources -->
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Resources</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:220px; left:0;">
    <a class="dropdown-item" href="research-insights.php" style="font-size:0.85rem; white-space:normal;">Research & Insights</a>
    <a class="dropdown-item" href="blogs-articles.php" style="font-size:0.85rem; white-space:normal;">Blogs & Articles</a>
    <a class="dropdown-item" href="case-studies.php" style="font-size:0.85rem; white-space:normal;">Case Studies & Whitepapers</a>
	 <a class="dropdown-item" href="reach-us.php" style="font-size:0.85rem; white-space:normal;">Reach Us</a>
   
    <a class="dropdown-item" href="intern-opportunities.php" style="font-size:0.85rem; white-space:normal;">Internship Opportunities</a>
  </ul>
</li>

<!-- Contact Us 
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Contact Us</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:220px; left:0;">
    <a class="dropdown-item" href="reach-us.php" style="font-size:0.85rem; white-space:normal;">Reach Us</a>
   
    <a class="dropdown-item" href="intern-opportunities.php" style="font-size:0.85rem; white-space:normal;">Internship Opportunities</a>
  </ul>
</li>   ----->

        </ul>
      </div>
    </div>
  </nav>
</header>
<!-- End Header -->
