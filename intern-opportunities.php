<?php 
$page="intern-opportunities";
include_once('head-nav.php');
?>

<!-- Page Header -->
<section id="main-banner-page" class="position-relative page-header service-header parallax section-nav-smooth">
   <div class="container">
      <div class="row">
         <div class="col-lg-8 offset-lg-2">
            <div class="page-titles whitecolor text-center padding_top padding_bottom">
               <h2 class="font-light">Explore</h2>
               <h2 class="fontbold">Internship Opportunities</h2>
               <h2 class="font-light">Learn • Build • Innovate</h2>
               <h3 class="font-light">Hands-on learning across domains and technology stacks</h3>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Page Header ends -->

<!-- Internship Opportunities -->
<section id="internships" class="bglight padding">
   <div class="container">
      <div class="row">
         
         <!-- Internship Card 1 -->
         <div class="col-lg-4 col-md-6 mb-4">
            <div class="news_item shadow text-center">
               <img src="images/bb.jpg" alt="Full Stack Development" class="img-responsive">
               <div class="news_desc p-3">
                  <h3 class="darkcolor">Full Stack Development</h3>
                  <p>Work with MERN/MEAN stacks, APIs, and modern frameworks to build scalable web applications.</p>
                  <button class="button gradient-btn" data-toggle="modal" data-target="#applyModal" data-domain="Full Stack Development">Apply Now</button>
               </div>
            </div>
         </div>

         <!-- Internship Card 2 -->
         <div class="col-lg-4 col-md-6 mb-4">
            <div class="news_item shadow text-center">
               <img src="images/bb.jpg" alt="AI & Machine Learning" class="img-responsive">
               <div class="news_desc p-3">
                  <h3 class="darkcolor">AI & Machine Learning</h3>
                  <p>Hands-on projects in data science, deep learning, NLP, and computer vision using Python frameworks.</p>
                  <button class="button gradient-btn" data-toggle="modal" data-target="#applyModal" data-domain="AI & Machine Learning">Apply Now</button>
               </div>
            </div>
         </div>

         <!-- Internship Card 3 -->
         <div class="col-lg-4 col-md-6 mb-4">
            <div class="news_item shadow text-center">
               <img src="images/bb.jpg" alt="Blockchain Development" class="img-responsive">
               <div class="news_desc p-3">
                  <h3 class="darkcolor">Blockchain Development</h3>
                  <p>Explore smart contracts, decentralized apps, and blockchain credentialing systems.</p>
                  <button class="button gradient-btn" data-toggle="modal" data-target="#applyModal" data-domain="Blockchain Development">Apply Now</button>
               </div>
            </div>
         </div>

         <!-- Internship Card 4 -->
         <div class="col-lg-4 col-md-6 mb-4">
            <div class="news_item shadow text-center">
               <img src="images/cr.jpg" alt="Cloud & DevOps" class="img-responsive">
               <div class="news_desc p-3">
                  <h3 class="darkcolor">Cloud & DevOps</h3>
                  <p>Get trained in AWS, Azure, Docker, Kubernetes, and CI/CD pipelines for scalable deployments.</p>
                  <button class="button gradient-btn" data-toggle="modal" data-target="#applyModal" data-domain="Cloud & DevOps">Apply Now</button>
               </div>
            </div>
         </div>

         <!-- Internship Card 5 -->
         <div class="col-lg-4 col-md-6 mb-4">
            <div class="news_item shadow text-center">
               <img src="images/w23.jpg" alt="Cybersecurity" class="img-responsive">
               <div class="news_desc p-3">
                  <h3 class="darkcolor">Cybersecurity</h3>
                  <p>Work on penetration testing, security audits, ethical hacking, and building secure systems.</p>
                  <button class="button gradient-btn" data-toggle="modal" data-target="#applyModal" data-domain="Cybersecurity">Apply Now</button>
               </div>
            </div>
         </div>

         <!-- Internship Card 6 -->
         <div class="col-lg-4 col-md-6 mb-4">
            <div class="news_item shadow text-center">
               <img src="images/be.jpg" alt="UI/UX & Design" class="img-responsive">
               <div class="news_desc p-3">
                  <h3 class="darkcolor">UI/UX & Design</h3>
                  <p>Design intuitive interfaces, prototypes, and user experiences for digital-first products.</p>
                  <button class="button gradient-btn" data-toggle="modal" data-target="#applyModal" data-domain="UI/UX & Design">Apply Now</button>
               </div>
            </div>
         </div>

      </div>
   </div>
</section>
<!-- Internship Opportunities ends -->

<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="applyModalLabel">Apply for Internship</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
           <form action="save-internship-application.php" method="POST" enctype="multipart/form-data">
   <input type="hidden" id="internshipDomain" name="internshipDomain">
               <div class="form-group">
                  <label>Your Name</label>
                  <input type="text" name="name" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>Phone</label>
                  <input type="text" name="phone" class="form-control" required>
               </div>
               <div class="form-group">
                  <label>Resume (PDF)</label>
                  <input type="file" name="resume" class="form-control" accept=".pdf" required>
               </div>
               <div class="form-group">
                  <label>Why are you interested in this internship?</label>
                  <textarea name="message" class="form-control" rows="3" required></textarea>
               </div>
               <button type="submit" class="button gradient-btn">Submit Application</button>
            </form>
         </div>
      </div>
   </div>
</div>

<script>
// Pass internship domain to modal
$('#applyModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var domain = button.data('domain');
  var modal = $(this);
  modal.find('#internshipDomain').val(domain);
  modal.find('.modal-title').text('Apply for ' + domain);
});
</script>

<?php 
include_once('footer.php');
?>
