<?php 
$page = "lxp";
include_once('config.php');
include_once('head-nav.php');


// Check connection
if ($coni->connect_error) {
    die("Connection failed: " . $coni->connect_error);
}

// Fixed course ID = 1 (you can later make it dynamic with $_GET['id'])
$course_id = 5;

$sql = "SELECT * FROM course_metadata WHERE course_id = ?";
$stmt = $coni->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
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
                    <h3 class="float-left">Course Detail</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                        <li class="breadcrumb-item hover-light"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item hover-light">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Page Header ends -->

<!-- Services us -->
<section id="our-services" class="padding bglight">
    <div class="container">
        <div class="row whitebox top15">
   
		<!-- Course Meta Data  -->
        <div class="col-lg-8 col-md-7">
            <div class="widget heading_space text-center text-md-left">

                <!-- Course Name -->
                <h3 class="darkcolor font-normal bottom30 d-flex align-items-center justify-content-between" style="display:flex; align-items:center; justify-content:space-between;">
    <span>
        <i class="fa fa-file-text-o mr-2 text-warning"></i>
        <?php echo htmlspecialchars($course['title']); ?> - Overview
    </span>
    <div class="eny_profile" style="display:flex; align-items:center; gap:10px;">
        <div class="profile_photo" style="display:flex; align-items:center;">
            <img src="images/team-3.jpg" alt="Instructor Profile" style="width:50px; height:50px; border-radius:50%; object-fit:cover; border:2px solid #ffc107;">
        </div>
        <div class="profile_text" style="text-align:right;">
            <strong style="font-size:14px; color:#333;">Dr. Rina Patel</strong><br>
            <span style="font-size:12px; color:#777;">Lead Instructor</span>
        </div>
    </div>
</h3>

				
                <!-- Course Description -->
                <p class="bottom30">
                    <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                </p>

                <!-- Accordion -->
                <div id="accordion" class="mb-4">

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapsePrelude">
                                <span><i class="fa fa-book mr-2 text-primary"></i> Prelude</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapsePrelude" class="collapse show" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['prelude'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapsePrerequisites">
                                <span><i class="fa fa-list-ul mr-2 text-info"></i> Prerequisites</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapsePrerequisites" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['prerequisites'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapseAudience">
                                <span><i class="fa fa-users mr-2 text-success"></i> Target Audience</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapseAudience" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['audience'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapseObjectives">
                                <span><i class="fa fa-bullseye mr-2 text-warning"></i> Learning Objectives</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapseObjectives" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['objectives'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapseOutcomes">
                                <span><i class="fa fa-graduation-cap mr-2 text-danger"></i> Learning Outcomes</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapseOutcomes" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['outcomes'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapseTakeaways">
                                <span><i class="fa fa-check-circle mr-2 text-success"></i> Key Takeaways</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapseTakeaways" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['takeaways'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-2">
                        <div class="card-header p-3">
                            <a class="d-flex justify-content-between align-items-center text-dark text-decoration-none"
                               data-toggle="collapse" href="#collapseSkills">
                                <span><i class="fa fa-cogs mr-2 text-secondary"></i> Key Skills Gained</span>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                        <div id="collapseSkills" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($course['skills'])); ?></p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Accordion -->

                <!-- Custom CSS -->
                <style>
                    #accordion .card-header {
                        background: #f8f9fa;
                        cursor: pointer;
                    }
                    #accordion .card-header:hover {
                        background: #e9ecef;
                    }
                    #accordion .fa-chevron-down {
                        transition: transform 0.3s;
                    }
                    #accordion .collapse.show ~ .card-header .fa-chevron-down {
                        transform: rotate(180deg);
                    }
                </style>
            </div>
        </div>
        <!-- Course Meta Data  -->

        <!-- Sidebar: Delivery Methodology & Closing Remarks -->
        <div class="col-lg-4 col-md-5">
            <div class="widget heading_space text-left">

                <!-- Delivery Methodology -->
                <h4 class="text-capitalize darkcolor bottom20">
                    <i class="fa fa-cogs text-info mr-2"></i> Delivery Methodology
                </h4>
                <p><?php echo nl2br(htmlspecialchars($course['delivery_method'])); ?></p>

                <!-- Closing Remarks -->
                <h4 class="text-capitalize darkcolor top30 bottom20">
                    <i class="fa fa-quote-left text-secondary mr-2"></i> Why this Course
                </h4>
                <p><?php echo nl2br(htmlspecialchars($course['closing'])); ?></p>

                <!-- Duration -->
                <p class="mt-3">
                    <i class="fa fa-clock-o text-info mr-2"></i>
                    <strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?>
                </p>

                <?php if (!empty($course['brochure_path'])): ?>
                    <a href="<?php echo htmlspecialchars($course['brochure_path']); ?>" class="button btnsecondary gradient-btn top30">Download Brochure</a>
                <?php endif; ?>

                <p><br></p>
                <h4 class="text-capitalize darkcolor bottom20">Speak to Our Guide.</h4>
                <div class="contact-table colorone d-table bottom15">
                    <div class="d-table-cell cells">
                        <span class="icon-cell"><i class="fas fa-mobile-alt"></i></span>
                    </div>
                    <div class="d-table-cell cells">
                        <p class="bottom0">+92-0900-10072 <span class="d-block">+92-0900-10072</span></p>
                    </div>
                </div>
            </div>
			
			 
                        <form class="getin_form wow fadeInUp" data-wow-delay="400ms">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="text" placeholder="First Name:" required id="first_name1" name="first_name">
                                        <label for="first_name1" class="d-none"></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="tel" placeholder="Company Name" id="company-name1">
                                        <label for="company-name1" class="d-none"></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <input class="form-control" type="email" placeholder="Email:" required id="email1" name="email">
                                        <label for="email1" class="d-none"></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <textarea class="form-control"  placeholder="Request a FreeConsultation" required id="FreeConsultation1"></textarea>
                                        <label for="FreeConsultation1" class="d-none"></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" class="button gradient-btn w-100" id="submit_btn1">Free Consultation</button>
                                </div>
                            </div>
                        </form>
                    
					
        </div>
		
	<div><p><<br><br></p></div>
	
	
           <!-----------------   FAQ for TET – Confidence & Exam Readiness    -------------------------------------->
<div class="col-md-12 text-center animated wow fadeIn" data-wow-delay="300ms">
    <h2 class="heading bottom30 darkcolor font-light2">Frequently Asked 
        <span class="font-normal">Questions</span>
        <span class="divider-center"></span>
    </h2>
    <div class="col-md-8 offset-md-2">
        <p class="heading_space">
            Get answers to common questions about building exam confidence, managing stress, 
            strengthening exam temperament, and becoming fully prepared to perform your best in TET.
        </p>
    </div>
</div>

<h2 class="d-none">Tabs</h2>

<div class="col-md-12 col-sm-12">
    <div id="accordion">

        <!-- FAQ 1 -->
        <div class="card">
            <div class="card-header">
                <a class="card-link darkcolor" data-toggle="collapse" href="#collapseOneCR">
                    What is the purpose of the “Confidence & Exam Readiness” program?
                </a>
            </div>
            <div id="collapseOneCR" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        This program helps educators build mental preparedness, emotional stability, 
                        and exam confidence. It focuses on developing calmness, clarity, and problem-solving 
                        ability under pressure, ensuring teachers perform with consistency during the TET exam.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link darkcolor" data-toggle="collapse" href="#collapseTwoCR">
                    How does the program help reduce exam anxiety?
                </a>
            </div>
            <div id="collapseTwoCR" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        Educators are guided through relaxation techniques, mindset management, 
                        confidence-building exercises, and structured breathing routines. 
                        The program also uses mock exam simulations to help participants become 
                        comfortable with the real exam environment, removing fear and hesitation.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link darkcolor" data-toggle="collapse" href="#collapseThreeCR">
                    What tools are provided to improve exam readiness?
                </a>
            </div>
            <div id="collapseThreeCR" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        The program includes timed practice sets, mock test simulations, 
                        stress-management strategies, memory techniques, doubt-clearing sessions, 
                        and strategic revision plans to help educators approach TET with confidence 
                        and improved speed and accuracy.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ 4 -->
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link darkcolor" data-toggle="collapse" href="#collapseFourCR">
                    Is this program suitable for educators appearing for TET after a long gap?
                </a>
            </div>
            <div id="collapseFourCR" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        Absolutely. The program is designed to help returning educators rebuild 
                        confidence, refresh exam discipline, regain momentum, and prepare effectively 
                        even if they have been away from competitive exams for several years.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ 5 -->
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link darkcolor" data-toggle="collapse" href="#collapseFiveCR">
                    How does this program improve my TET score?
                </a>
            </div>
            <div id="collapseFiveCR" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        By strengthening concentration, reducing stress, and improving exam temperament, 
                        educators make fewer mistakes and perform more consistently. The program also helps 
                        identify scoring areas and build strategies to maximize marks while managing time effectively.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ 6 -->
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link darkcolor" data-toggle="collapse" href="#collapseSixCR">
                    Are personalized mentoring and feedback included?
                </a>
            </div>
            <div id="collapseSixCR" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        Yes. Educators receive one-on-one mentoring, personalized performance analysis, 
                        and guidance on mental preparation. Mentors help participants recognize patterns, 
                        break limiting beliefs, and build a confident, exam-ready mindset.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ 7 -->
        <div class="card">
            <div class="card-header">
                <a class="collapsed card-link darkcolor" data-toggle="collapse" href="#collapseSevenCR">
                    Does this program integrate with subject and pedagogy preparation?
                </a>
            </div>
            <div id="collapseSevenCR" class="collapse" data-parent="#accordion">
                <div class="card-body">
                    <p>
                        Yes. Confidence and exam readiness are directly connected to strong subject clarity 
                        and pedagogical understanding. The program complements the other TET preparation modules 
                        by helping educators apply their knowledge more confidently in real exam conditions.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
<!-----------------   FAQ for TET – Confidence & Exam Readiness Ends   -------------------------------------->

			
			
			
        </div>
    </div>
</section>
<!-- Faq ends -->






<?php include_once('footer.php'); ?>
