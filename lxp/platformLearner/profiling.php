<?php
/**
 *  Astraal LXP - 360° Learner Profiling Dashboard
 *  With Font Awesome icons for major headings
 */
?>

<!-- Include Font Awesome (if not already in your layout) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container-fluid">
  <!-- =============================== -->
  <!-- 🌐 360° Main Title -->
  <!-- =============================== -->
  <h4 class="fw-bold mt-4 mb-4">
    <i class="fa-solid fa-circle-nodes text-primary me-2"></i>
    360° Learner Profiling Dashboard
  </h4>
  <p class="text-muted mb-4">
    Build your complete learner identity across psychological, behavioral, and skill-based dimensions.  
    Your 360° profile helps personalize your journey, gamify learning, and guide career readiness.
  </p>

  <!-- ==================================== -->
  <!-- 🧠 Psychographics & Preferences -->
  <!-- ==================================== -->
  <h5 class="fw-bold mb-4 mt-5">
    <i class="fa-solid fa-brain text-info me-2"></i>
    Psychographics & Cognitive Preferences
  </h5>
  <p class="text-muted mb-4">
    Explore your motivations, learning styles, and engagement preferences.  
    These insights fuel AI-driven personalization and adaptive learning engine.
  </p>

  <div class="row g-4 mb-5">
    <?php
    $psychos = array(
      array('Learning Motivation','bxs-bulb','primary','Understand what drives your curiosity — growth, mastery, or recognition.','../assets/img/360_learnerMotivation.png','motivation'),
      array('Learning Style','bxs-book-reader','info','Discover how you best absorb content — Visual, Auditory, Reading/Writing, or Kinesthetic.','../assets/img/360_learningStyle.png','vark'),
      array('Cognitive Pattern','bxs-brain','warning','Assess your focus span, pace, and optimal learning rhythm.','../assets/img/360_cognitivePattern.png','cognitive'),
      array('Engagement Type','bxs-group','success','Find out if you’re a collaborative or independent learner.','../assets/img/360_engagementType.png','engagement'),
      array('Gamification Persona','bxs-trophy','danger','Identify your motivational archetype — Achiever, Explorer, or Socializer.','../assets/img/360_gamificationPersona.png','gamification'),
      array('Emotional Intelligence','bxs-heart','secondary','Measure your empathy, adaptability, and learning resilience.','../assets/img/360_emotionalIntelligence.png','emotional')
    );

    foreach ($psychos as $p) {
      list($title,$icon,$color,$desc,$img,$slug) = $p;
      echo '
      <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body text-center">
            <i class="bx '.$icon.' text-'.$color.' mb-3" style="font-size:2rem;"></i>
            <h6 class="fw-bold">'.$title.'</h6>
            <p class="text-muted small">'.$desc.'</p>
            <div class="progress mb-2" style="height:8px;">
              <div class="progress-bar bg-'.$color.'" style="width:0%"></div>
            </div>
            <small class="text-muted d-block mb-3">Not yet started</small>
            <div class="d-flex justify-content-center gap-2">
              <button class="btn btn-sm btn-outline-'.$color.'" onclick="showImageModal(\''.$img.'\')">
                Know More
              </button>
              <button class="btn btn-sm btn-'.$color.'" onclick="startAssessment(\''.$slug.'\', \''.$title.'\')">
                Assess Yours
              </button>
            </div>
          </div>
        </div>
      </div>';
    }
    ?>
  </div>

  <!-- ============================== -->
  <!-- 🧩 Core 360° Profiling Section -->
  <!-- ============================== -->
  <hr class="my-5">
  <h5 class="fw-bold mb-4">
    <i class="fa-solid fa-layer-group text-warning me-2"></i>
    Core 360° Profiling Dimensions
  </h5>
  <p class="text-muted mb-4">
    Capture your learning habits, skill strengths, and behavioral insights.  
    Together, these parameters form your 360° Learner Profile.
  </p>

  <div class="row g-4">
    <?php
    $core_sections = array(
      array('Learning Personality','bxs-user-voice','primary','Discover how you process and internalize new knowledge.','personality'),
      array('Skill Competency Map','bxs-bar-chart-alt-2','info','Visualize your strengths, competencies, and growth areas.','skills'),
      array('Behavioral Intelligence','bxs-analyse','warning','Understand your engagement, focus, and consistency patterns.','behavior'),
      array('Motivation & Growth Drivers','bxs-rocket','success','Recognize what sustains your learning progress.','growth'),
      array('Gamified Engagement','bxs-joystick','danger','Track your XP, badges, and gamified progression.','engagement360'),
      array('Career Readiness','bxs-briefcase-alt-2','secondary','Align your learning trajectory with career goals.','career')
    );

    foreach ($core_sections as $sec) {
      list($title,$icon,$color,$desc,$slug) = $sec;
      echo '
      <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body text-center">
            <i class="bx '.$icon.' text-'.$color.' mb-3" style="font-size:2rem;"></i>
            <h6 class="fw-bold">'.$title.'</h6>
            <p class="text-muted small">'.$desc.'</p>
            <div class="progress mb-2" style="height:8px;">
              <div class="progress-bar bg-'.$color.'" style="width:0%"></div>
            </div>
            <small class="text-muted d-block mb-3">Not yet started</small>
            <div class="d-flex justify-content-center gap-2">
              <button class="btn btn-sm btn-outline-'.$color.'" onclick="launchDetail(\''.$title.'\')">
                Learn More
              </button>
              <button class="btn btn-sm btn-'.$color.'" onclick="startAssessment(\''.$slug.'\', \''.$title.'\')">
                Assess Yours
              </button>
            </div>
          </div>
        </div>
      </div>';
    }
    ?>
  </div>
</div>

<!-- ============================== -->
<!-- ✅ SweetAlert2 Interaction Logic -->
<!-- ============================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showImageModal(imagePath){
  Swal.fire({
    imageUrl: imagePath,
    imageAlt: 'Profile Insight',
    showConfirmButton: false,
    width: 'auto',
    padding: 0,
    backdrop: `rgba(0,0,0,0.8) center top no-repeat`,
    customClass: { image: 'swal2-full-image' }
  });
}

function launchDetail(topic){
  Swal.fire({
    title: topic,
    text: 'You haven’t started this profiling yet. Begin now to contribute to your 360° learner identity.',
    icon: 'info',
    confirmButtonText: 'Begin',
    showCancelButton: true,
    cancelButtonText: 'Later',
    allowOutsideClick: false
  });
}

function startAssessment(slug, title){
  Swal.fire({
    title: 'Start ' + title + ' Assessment?',
    text: 'This will take you to your personalized assessment for "' + title + '".',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Start Now',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#aaa'
  }).then((result) => {
    if(result.isConfirmed){
      window.location.href = slug + '.php';
    }
  });
}
</script>

<style>
.swal2-full-image {
  max-width: 120vw !important;
  max-height: 90vh !important;
  object-fit: contain;
  border-radius: 8px;
}
</style>
