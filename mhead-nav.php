<?php
/**
 * EduuAspire — Head Navigation (Realigned)
 * PHP 5.4 Safe | Local + Production
 */

if (!isset($base_url)) {
    $is_localhost = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));
    $base_url = $is_localhost ? 'http://localhost/asmEduuAspire' : 'https://eduuaspire.online';
}

$page = isset($page) ? $page : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>EduuAspire | Future-Ready Learning & Human Intelligence</title>

<link rel="icon" href="<?php echo $base_url; ?>/images/eduuFavicon.png">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/all.min.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/css/style.css">


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
<style>
/* Force active state for parent dropdown */
.navbar-nav .nav-item.active > .nav-link,
.navbar-nav .nav-link.active {
  color: #00e5ff !important;        /* match your cyan highlight */
  font-weight: 600;
}

/* Optional: underline or indicator */
.navbar-nav .nav-item.active > .nav-link::after {
  content: '';
  display: block;
  margin: 4px auto 0;
  width: 60%;
  height: 2px;
  background: #00e5ff;
}
</style>

<style>
.navbar-nav .nav-link.active { color:#00e5ff !important; font-weight:600; }
.floating-cta {
  position:fixed; right:20px; bottom:20px; z-index:9999;
}
</style>
</head>

<body>

<!-- Floating CTA -->
<a href="<?php echo $base_url; ?>/book-a-call.php"
   class="btn btn-info floating-cta shadow-lg">
  <i class="fas fa-calendar-check mr-1"></i> Book a Strategy Call
</a>

<header class="site-header">

<nav class="navbar navbar-expand-lg transparent-bg static-nav">
<div class="container">

<a class="navbar-brand" href="<?php echo $base_url; ?>/index.php">
  <img src="<?php echo $base_url; ?>/images/ealxp_logo.png" alt="EduuAspire">
</a>

<div class="collapse navbar-collapse">
<ul class="navbar-nav ml-auto">

<!-- Home -->
<li class="nav-item">
  <a class="nav-link <?php echo ($page=='home')?'active':''; ?>" href="<?php echo $base_url; ?>/index.php">Home</a>
</li>

<!-- Who It's For -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" style="position:relative;" data-toggle="dropdown">Who It’s For</a>
<div class="dropdown-menu">
<a class="dropdown-item" href="#">Institutions & Universities</a>
<a class="dropdown-item" href="#">Corporates & Industries</a>
<a class="dropdown-item" href="#">Learners & Professionals</a>
<a class="dropdown-item" href="#">NGOs & Government</a>
</div>
</li>

<!-- Solutions -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" style="position:relative;" data-toggle="dropdown">Solutions</a>
<div class="dropdown-menu">
<a class="dropdown-item" href="#">Future-Ready Campus Transformation</a>
<a class="dropdown-item" href="#">Employability & Train-to-Hire</a>
<a class="dropdown-item" href="#">AI-Powered Learning Systems</a>
<a class="dropdown-item" href="#">Lifelong Learning & Support</a>
</div>
</li>

<!-- Programs -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" style="position:relative;" data-toggle="dropdown">Programs</a>
<div class="dropdown-menu">
<a class="dropdown-item" href="#">Future Skills Programs</a>
<a class="dropdown-item" href="#">Faculty Development (NEP-Aligned)</a>
<a class="dropdown-item" href="#">Campus-to-Corporate Programs</a>
<a class="dropdown-item" href="#">Leadership & Human Intelligence</a>
</div>
</li>

<!-- Platform -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle"style="position:relative;"  data-toggle="dropdown">Platform</a>
<div class="dropdown-menu">
<a class="dropdown-item" href="#">EduuAspire LMS</a>
<a class="dropdown-item" href="#">EduuAspire LXP</a>
<a class="dropdown-item" href="#">AI & GenAI Studio</a>
<a class="dropdown-item" href="#">Credentials & Trust Layer</a>
</div>
</li>

<!-- EduuAspire 21.0 -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" data-toggle="dropdown">EduuAspire 21.0</a>
<div class="dropdown-menu p-3" style="min-width:320px;">
<p class="small text-muted mb-2">
<strong>EduuAspire 21.0</strong> is our proprietary human-intelligence framework that powers all learning, skilling, and future-readiness programs.
</p>
<hr>
<strong class="dropdown-header">Framework</strong>
<a class="dropdown-item" href="#">Educational Kinesiology</a>
<a class="dropdown-item" href="#">Cognitive & Neural Capability</a>

<strong class="dropdown-header">Capability Layer</strong>
<a class="dropdown-item" href="#">21st-Century Literacies</a>
<a class="dropdown-item" href="#">Human Skills & Mindsets</a>

<strong class="dropdown-header">Premium Offerings</strong>
<a class="dropdown-item" href="#">Advanced Intelligence Labs</a>
</div>
</li>

<!-- About -->
<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle" data-toggle="dropdown">About</a>
<div class="dropdown-menu">
<a class="dropdown-item" href="#">Who We Are</a>
<a class="dropdown-item" href="#">Leadership</a>
<a class="dropdown-item" href="#">Collaborations</a>
<a class="dropdown-item" href="#">Careers</a>
<a class="dropdown-item" href="#">Resources</a>
</div>
</li>



</ul>
</div>
</div>
</nav>
</header>
