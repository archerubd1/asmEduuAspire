<?php
/**
 * Phoenix LXP — Head Navigation
 * PHP 5.4 Safe | UwAmp Local & GoDaddy Production
 */

if (!isset($base_url)) {
    $is_localhost = in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));
    $base_url = $is_localhost ? 'http://localhost/asmEduuAspire' : 'https://eduuaspire.online';
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
  'lxp',
  'adaptive-learning',
  'ai-learning',
  'immersive-classrooms',
  'curriculum-digital',
  'ar-vr-learning',
  'gamification',
  'blockchain-credentialing',
  'cloud-platforms',
  'data-driven',
  'learning',
  'on-demand',
  'career-counseling',
  'resilience-programs',
  'global-competency'
);
$isFuturePathwaysActive = in_array($page, $futurePathwaysPages);


$programsServicesPages = array(
  'future-skills',
  'bootcamps',
  'train-to-hire',
  'pathways',
  'entrepreneurship',
  'pre-corporate',
  'soft-skills',
  'digital-workplace',
  'internship-support',
  'post-campus',
  'tet-refresher',
  'nep-refresher',
  'cpd',
  'academic-support',
  'innovation-teaching'
);

$isProgramsServicesActive = in_array($page, $programsServicesPages);
$eduuAspirePages = array(
  'brain-gym',
  'blindfold',
  'mind-gym',
  'super-memory',
  'neuro-habits',
  'digital-lit',
  'media-literacy',
  'financial-literacy',
  'information-literacy',
  '3rs-8cs-3ms',
  'ai-genai-literacy',
  'future-work-careers',
  'human-skills',
  'innovation',
  'lifelong-learning'
);

$isEduuAspireActive = in_array($page, $eduuAspirePages);




$aboutUsPages = array(
    // Who We Serve
    'schools-institutions',
    'industries-corporates',
    'hospitality-retail',
    'ngos-states',
    'home-tutoring',

    // Resources
    'research-insights',
    'blogs-articles',
    'case-studies',
    'intern-opportunities',
    'faculty-resources',

    // Know Us
    'who-we-are',
    'leadership-team',
    'collaborations',
    'careers',
    'join-as-mentor'
);

$isAboutUsActive = in_array($page, $aboutUsPages);


?>

<!-- Home -->
<li class="nav-item">
<a class="nav-link <?php echo ($page=='home')?'active':''; ?>" href="<?php echo $base_url; ?>/index.php">
<i class="fas fa-home mr-1"></i>Home
</a>
</li>

<!-- ================= FUTURE PATHWAYS ================= -->
<li class="nav-item dropdown static <?php echo $isFuturePathwaysActive ? 'active' : ''; ?>">
<a class="nav-link dropdown-toggle <?php echo $isFuturePathwaysActive?'active':''; ?>" href="" data-toggle="dropdown">
<i class="fas fa-road mr-1"></i>Solutions
</a>

<ul class="dropdown-menu megamenu" style="padding:15px;min-width:750px;">
<li><div class="container" style="max-width:100%;">
<div class="row">

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6 style="font-weight:bold;"><i class="fas fa-laptop-code mr-2"></i>EdTech Solutions</h6>
<a class="dropdown-item <?php echo ($page=='lxp')?'active':''; ?>" href="<?php echo $base_url; ?>/lxp.php"><i class="fas fa-layer-group mr-2"></i>Learning Experience Platforms</a>
<a class="dropdown-item <?php echo ($page=='adaptive-learning')?'active':''; ?>" href="<?php echo $base_url; ?>/adaptive-learning.php"><i class="fas fa-sliders-h mr-2"></i>Adaptive & Personalized Learning</a>
<a class="dropdown-item <?php echo ($page=='ai-learning')?'active':''; ?>" href="<?php echo $base_url; ?>/ai-learning.php"><i class="fas fa-brain mr-2"></i>AI-Powered Analytics</a>
<a class="dropdown-item <?php echo ($page=='immersive-classrooms')?'active':''; ?>" href="<?php echo $base_url; ?>/immersive-classrooms.php"><i class="fas fa-vr-cardboard mr-2"></i>Immersive Classrooms</a>
<a class="dropdown-item <?php echo ($page=='curriculum-digital')?'active':''; ?>" href="<?php echo $base_url; ?>/curriculum-digital.php"><i class="fas fa-book-reader mr-2"></i>Curriculum Digitization</a>
</div>

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6 style="font-weight:bold;"><i class="fas fa-microchip mr-2"></i>Future Tech in Education</h6>
<a class="dropdown-item <?php echo ($page=='ar-vr-learning')?'active':''; ?>" href="<?php echo $base_url; ?>/ar-vr-learning.php"><i class="fas fa-cube mr-2"></i>AR/VR Learning</a>
<a class="dropdown-item <?php echo ($page=='gamification')?'active':''; ?>" href="<?php echo $base_url; ?>/gamification.php"><i class="fas fa-gamepad mr-2"></i>Gamification & Simulation</a>
<a class="dropdown-item <?php echo ($page=='blockchain-credentialing')?'active':''; ?>" href="<?php echo $base_url; ?>/blockchain-edu.php"><i class="fas fa-link mr-2"></i>Blockchain Credentialing</a>
<a class="dropdown-item <?php echo ($page=='cloud-platforms')?'active':''; ?>" href="<?php echo $base_url; ?>/cloud-platforms.php"><i class="fas fa-cloud mr-2"></i>Cloud Platforms</a>
<a class="dropdown-item <?php echo ($page=='data-driven')?'active':''; ?>" href="<?php echo $base_url; ?>/data-driven.php"><i class="fas fa-chart-line mr-2"></i>Data-Driven Systems</a>
</div>

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6 style="font-weight:bold;"><i class="fas fa-infinity mr-2"></i>Lifelong Learning & Support</h6>
<a class="dropdown-item <?php echo ($page=='learning')?'active':''; ?>" href="<?php echo $base_url; ?>/learning.php"><i class="fas fa-network-wired mr-2"></i>Learning Ecosystem</a>
<a class="dropdown-item <?php echo ($page=='on-demand')?'active':''; ?>" href="<?php echo $base_url; ?>/on-demand.php"><i class="fas fa-headset mr-2"></i>On-Demand Support</a>
<a class="dropdown-item <?php echo ($page=='career-counseling')?'active':''; ?>" href="<?php echo $base_url; ?>/career-counseling.php"><i class="fas fa-user-tie mr-2"></i>Career Counseling</a>
<a class="dropdown-item <?php echo ($page=='resilience-programs')?'active':''; ?>" href="<?php echo $base_url; ?>/resilience-programs.php"><i class="fas fa-shield-alt mr-2"></i>Resilience Programs</a>
<a class="dropdown-item <?php echo ($page=='global-competency')?'active':''; ?>" href="<?php echo $base_url; ?>/global-competency.php"><i class="fas fa-globe mr-2"></i>Global Competency</a>
</div>

</div>
</div></li>
</ul>
</li>


<!-- ================= PROGRAMS & SERVICES ================= -->
<li class="nav-item dropdown static <?php echo $isProgramsServicesActive ? 'active' : ''; ?>">
<a class="nav-link dropdown-toggle <?php echo $isProgramsServicesActive ? 'active' : ''; ?>" href="" data-toggle="dropdown">
<i class="fas fa-cogs mr-1"></i>Services
</a>

<ul class="dropdown-menu megamenu" style="padding:15px; min-width:900px;">
<li><div class="container" style="max-width:100%;">
<div class="row">

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-briefcase mr-2"></i>Skilling & Employability</h6>
<a class="dropdown-item <?php echo ($page=='future-skills')?'active':''; ?>" href="future-skills.php"><i class="fas fa-award mr-2"></i>21st Century & Future-Ready Skills</a>
<a class="dropdown-item <?php echo ($page=='bootcamps')?'active':''; ?>" href="bootcamps.php"><i class="fas fa-code mr-2"></i>Coding Bootcamps & Digital Fluency</a>
<a class="dropdown-item <?php echo ($page=='train-to-hire')?'active':''; ?>" href="train-to-hire.php"><i class="fas fa-handshake mr-2"></i>Train-to-Hire & Campus Placements</a>
<a class="dropdown-item <?php echo ($page=='pathways')?'active':''; ?>" href="pathways.php"><i class="fas fa-route mr-2"></i>Career Pathways & Employability Labs</a>
<a class="dropdown-item <?php echo ($page=='entrepreneurship')?'active':''; ?>" href="entrepreneurship.php"><i class="fas fa-lightbulb mr-2"></i>Entrepreneurship & Start-up Readiness</a>
</div>

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-building mr-2"></i>Campus-to-Corporate</h6>
<a class="dropdown-item <?php echo ($page=='pre-corporate')?'active':''; ?>" href="pre-corporate.php"><i class="fas fa-university mr-2"></i>Pre-Corporate Readiness Programs</a>
<a class="dropdown-item <?php echo ($page=='soft-skills')?'active':''; ?>" href="soft-skills.php"><i class="fas fa-comments mr-2"></i>Communication & Workplace Etiquette</a>
<a class="dropdown-item <?php echo ($page=='digital-workplace')?'active':''; ?>" href="digital-workplace.php"><i class="fas fa-desktop mr-2"></i>Digital Workplace Skills</a>
<a class="dropdown-item <?php echo ($page=='internship-support')?'active':''; ?>" href="internship-support.php"><i class="fas fa-user-graduate mr-2"></i>Internship & Industry Bridge</a>
<a class="dropdown-item <?php echo ($page=='post-campus')?'active':''; ?>" href="post-campus.php"><i class="fas fa-rocket mr-2"></i>Post-Campus Transition Support</a>
</div>

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-chalkboard-teacher mr-2"></i>Faculty & Educator Development</h6>
<a class="dropdown-item <?php echo ($page=='tet-refresher')?'active':''; ?>" href="tet-refresher.php"><i class="fas fa-sync mr-2"></i>TET Refresher & Pedagogy Excellence</a>
<a class="dropdown-item <?php echo ($page=='nep-refresher')?'active':''; ?>" href="nep-refresher.php"><i class="fas fa-balance-scale mr-2"></i>NEP 2020 Aligned Refresher Programs</a>
<a class="dropdown-item <?php echo ($page=='cpd')?'active':''; ?>" href="cpd.php"><i class="fas fa-user-cog mr-2"></i>Continuous Professional Development</a>
<a class="dropdown-item <?php echo ($page=='academic-support')?'active':''; ?>" href="academic-support.php"><i class="fas fa-flask mr-2"></i>Academic & Research Mentorship</a>
<a class="dropdown-item <?php echo ($page=='innovation-teaching')?'active':''; ?>" href="innovation-teaching.php"><i class="fas fa-lightbulb mr-2"></i>Innovation in Teaching Practices</a>
</div>

</div>
</div></li>
</ul>
</li>



<!-- ================= EDUUASPIRE PRODUCTS ================= -->
<li class="nav-item dropdown" style="position:relative;" <?php echo ($page=='products') ? 'active' : ''; ?>">
  <a class="nav-link dropdown-toggle" <?php echo ($page=='products') ? 'active' : ''; ?>"  href="#" data-toggle="dropdown">Products</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:300px; left:0;">
    <a class="dropdown-item <?php echo ($page=='core-lms')?'active':''; ?>" href="#core-lms.php">
<i class="fas fa-server mr-2"></i>EduuAspire Core LMS <small class="text-muted">((eLearning Platform)</small>
</a>

<a class="dropdown-item <?php echo ($page=='lxp-platform')?'active':''; ?>" href="#lxp-platform.php">
<i class="fas fa-compass mr-2"></i>EduuAspire LXP <small class="text-muted">(Experience Layer)</small>
</a>

<a class="dropdown-item <?php echo ($page=='ai-studio')?'active':''; ?>" href="#ai-studio.php">
<i class="fas fa-robot mr-2"></i>EduuAspire AI Studio <small class="text-muted">(AI & GenAI Modules)</small>
</a>

<a class="dropdown-item <?php echo ($page=='human-intelligence')?'active':''; ?>" href="#human-intelligence.php">
<i class="fas fa-brain mr-2"></i>EduuAspire Human Intelligence Suite <small class="text-muted">(Human & cognitive capability building)</small>
</a>

<a class="dropdown-item <?php echo ($page=='credentials')?'active':''; ?>" href="#credentials.php">
<i class="fas fa-certificate mr-2"></i>EduuAspire Credentials & Trust Layer <small class="text-muted">(Digital credentials, analytics & verification)</small>
</a>
  </ul>
</li>


<!-- ================= EDUUASPIRE 21.0 ================= -->
<!-- ================= EDUUASPIRE 21.0 ================= -->
<li class="nav-item dropdown static <?php echo $isEduuAspireActive ? 'active' : ''; ?>">
<a class="nav-link dropdown-toggle <?php echo $isEduuAspireActive ? 'active' : ''; ?>" href="" data-toggle="dropdown">
<i class="fas fa-brain mr-1"></i>EduuAspire 21.0
</a>

<ul class="dropdown-menu megamenu" style="padding:15px; min-width:900px;">
<li><div class="container" style="max-width:100%;">
<div class="row">

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-dna mr-2"></i>Edu-K (Educational Kinesiology)</h6>
<a class="dropdown-item <?php echo ($page=='brain-gym')?'active':''; ?>" href="brain-gym.php"><i class="fas fa-brain mr-2"></i>Brain Gym</a>
<a class="dropdown-item <?php echo ($page=='blindfold')?'active':''; ?>" href="blindfold.php"><i class="fas fa-eye-slash mr-2"></i>Blindfold Experience</a>
<a class="dropdown-item <?php echo ($page=='mind-gym')?'active':''; ?>" href="mind-gym.php"><i class="fas fa-dumbbell mr-2"></i>Mind Gym Programs</a>
<a class="dropdown-item <?php echo ($page=='super-memory')?'active':''; ?>" href="super-memory.php"><i class="fas fa-memory mr-2"></i>Super Memory Training</a>
<a class="dropdown-item <?php echo ($page=='neuro-habits')?'active':''; ?>" href="neuro-habits.php"><i class="fas fa-project-diagram mr-2"></i>Neuro Habits & Cognitive Mastery</a>
</div>

<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-graduation-cap mr-2"></i>21st Century Skills</h6>
<a class="dropdown-item <?php echo ($page=='digital-lit')?'active':''; ?>" href="digital-literacy.php"><i class="fas fa-laptop mr-2"></i>Digital Literacy</a>
<a class="dropdown-item <?php echo ($page=='media-literacy')?'active':''; ?>" href="media-literacy.php"><i class="fas fa-photo-video mr-2"></i>Media Literacy</a>
<a class="dropdown-item <?php echo ($page=='financial-literacy')?'active':''; ?>" href="financial-literacy.php"><i class="fas fa-wallet mr-2"></i>Financial Literacy</a>
<a class="dropdown-item <?php echo ($page=='information-literacy')?'active':''; ?>" href="information-literacy.php"><i class="fas fa-database mr-2"></i>Information Literacy</a>
<a class="dropdown-item <?php echo ($page=='3rs-8cs-3ms')?'active':''; ?>" href="3rs.php"><i class="fas fa-project-diagram mr-2"></i>3Rs 8Cs 3Ms' Literacy</a>
</div>
<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-forward mr-2"></i>Future-Ready Pathways (21.0+)</h6>

<a class="dropdown-item <?php echo ($page=='ai-genai-literacy')?'active':''; ?>" href="ai-genai.php">
<i class="fas fa-robot mr-2"></i>AI, GenAI & Algorithmic Literacy
</a>

<a class="dropdown-item <?php echo ($page=='future-work-careers')?'active':''; ?>" href="future-work-careers.php">
<i class="fas fa-briefcase mr-2"></i>Future of Work, Careers & Skills
</a>

<a class="dropdown-item <?php echo ($page=='human-skills')?'active':''; ?>" href="human-skills.php">
<i class="fas fa-heart mr-2"></i>Human Skills in a Tech-Driven World
</a>

<a class="dropdown-item <?php echo ($page=='innovation')?'active':''; ?>" href="innovation-design.php">
<i class="fas fa-lightbulb mr-2"></i>Innovation & Design Thinking
</a>

<a class="dropdown-item <?php echo ($page=='lifelong-learning')?'active':''; ?>" href="lifelong-learning.php">
<i class="fas fa-infinity mr-2"></i>Lifelong Learning & Entrepreneurship
</a>
</div>


</div>
</div></li>
</ul>
</li>


<!-- About Us -->
<!-- ================= ABOUT US ================= -->
<!-- ================= ABOUT US ================= -->
<li class="nav-item dropdown static <?php echo $isAboutUsActive ? 'active' : ''; ?>">
<a class="nav-link dropdown-toggle <?php echo $isAboutUsActive ? 'active' : ''; ?>" href="" data-toggle="dropdown">
<i class="fas fa-info-circle mr-1"></i>About Us
</a>

<ul class="dropdown-menu megamenu" style="padding:15px; min-width:900px;">
<li>
<div class="container" style="max-width:100%;">
<div class="row">

<!-- ================= WHO WE SERVE ================= -->
<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-users mr-2"></i>Who We Serve</h6>

<a class="dropdown-item <?php echo ($page=='schools-institutions')?'active':''; ?>" href="#schools-institutions.php">
<i class="fas fa-school mr-2"></i>Schools & Institutions
</a>

<a class="dropdown-item <?php echo ($page=='industries-corporates')?'active':''; ?>" href="#industries-corporates.php">
<i class="fas fa-industry mr-2"></i>Industries & Corporates
</a>

<a class="dropdown-item <?php echo ($page=='hospitality-retail')?'active':''; ?>" href="#hospitality-retail.php">
<i class="fas fa-hotel mr-2"></i>Hospitality & Retail
</a>

<a class="dropdown-item <?php echo ($page=='ngos-states')?'active':''; ?>" href="#ngos-states.php">
<i class="fas fa-hands-helping mr-2"></i>NGOs & States
</a>

<a class="dropdown-item <?php echo ($page=='home-tutoring')?'active':''; ?>" href="#home-tutoring.php">
<i class="fas fa-home mr-2"></i>Home Tutoring & Mentoring
</a>
</div>

<!-- ================= RESOURCES ================= -->
<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-book mr-2"></i>Resources</h6>

<a class="dropdown-item <?php echo ($page=='research-insights')?'active':''; ?>" href="research-insights.php">
<i class="fas fa-search mr-2"></i>Research & Insights
</a>

<a class="dropdown-item <?php echo ($page=='blogs-articles')?'active':''; ?>" href="blogs-articles.php">
<i class="fas fa-blog mr-2"></i>Blogs & Articles
</a>

<a class="dropdown-item <?php echo ($page=='case-studies')?'active':''; ?>" href="case-studies.php">
<i class="fas fa-file-alt mr-2"></i>Case Studies & Whitepapers
</a>

<a class="dropdown-item <?php echo ($page=='intern-opportunities')?'active':''; ?>" href="intern-opportunities.php">
<i class="fas fa-user-clock mr-2"></i>Internship Opportunities
</a>

<a class="dropdown-item <?php echo ($page=='faculty-resources')?'active':''; ?>" href="#faculty-resources.php">
<i class="fas fa-chalkboard mr-2"></i>Faculty Resources
</a>
</div>

<!-- ================= KNOW US ================= -->
<div class="col-lg-4 col-md-6 col-sm-12" style="padding:5px 15px;">
<h6><i class="fas fa-id-card mr-2"></i>Know Us</h6>

<a class="dropdown-item <?php echo ($page=='who-we-are')?'active':''; ?>" href="#who-we-are.php">
<i class="fas fa-compass mr-2"></i>Who We Are
</a>

<a class="dropdown-item <?php echo ($page=='leadership-team')?'active':''; ?>" href="#leadership-team.php">
<i class="fas fa-users-cog mr-2"></i>Leadership Team
</a>

<a class="dropdown-item <?php echo ($page=='collaborations')?'active':''; ?>" href="#collaborations.php">
<i class="fas fa-handshake mr-2"></i>Collaborations & Partnerships
</a>

<a class="dropdown-item <?php echo ($page=='careers')?'active':''; ?>" href="#careers.php">
<i class="fas fa-briefcase mr-2"></i>Careers @ EduuAspire
</a>

<a class="dropdown-item <?php echo ($page=='join-as-mentor')?'active':''; ?>" href="#join-as-mentor.php">
<i class="fas fa-user-plus mr-2"></i>Join Us as a Mentor
</a>
</div>

</div>
</div>
</li>
</ul>
</li>





<!-- Brain Decode 
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Brain's Gym</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:300px; left:0;">
    <a class="dropdown-item" href="blindfold.php" style="font-size:0.85rem; white-space:normal;">Blindfold Experience</a>
    <a class="dropdown-item" href="mind-gym.php" style="font-size:0.85rem; white-space:normal;">Mind Gym Programs</a>
    <a class="dropdown-item" href="super-memory.php" style="font-size:0.85rem; white-space:normal;">Super Memory Training</a>
    <a class="dropdown-item" href="neuro-habits.php" style="font-size:0.85rem; white-space:normal;">Neuro Habits & Cognitive Mastery</a>
  </ul>
</li>-->    




<!-- About Us 
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">About Us</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:300px; left:0;">
    <a class="dropdown-item" href="collaborations.php" style="font-size:0.85rem; white-space:normal;">Collaborations & Partnerships</a>
    <a class="dropdown-item" href="who-we-are.php" style="font-size:0.85rem; white-space:normal;">Who We Are</a>
    <a class="dropdown-item" href="leadership-team.php" style="font-size:0.85rem; white-space:normal;">Leadership Team</a>
  </ul>
</li>

<!-- Resources 
<li class="nav-item dropdown" style="position:relative;">
  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">Resources</a>
  <ul class="dropdown-menu" style="padding:10px; min-width:220px; left:0;">
   
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
