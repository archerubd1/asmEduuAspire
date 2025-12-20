<?php
/**
 *  Astraal LXP - Learner Learning Paths
 * Refactored for new session guard architecture
 * PHP 5.4 compatible (UwAmp / GoDaddy)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ unified session management

$page = "profile";
require_once('learnerHead_Nav2.php');

// -----------------------------------------------------------------------------
// Validate session
// -----------------------------------------------------------------------------
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('learnersNav.php');   ?>

           <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

           <div class="container-xxl flex-grow-1 container-p-y">
 <div class="card">
 

<div class="container mt-5">
    <h4 class="mb-4">🚀 Skills & Career Roadmap</h4>
    <p class="text-muted">Track your skill progression and align your learning with career goals.</p>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>📌 Skill Category</th>
                    <th>🎯 Targeted Skills</th>
                    <th>📈 Proficiency Level</th>
                    <th>💼 Career Roles</th>
                    <th>🛠 Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>💻 Programming & Software Development</td>
                    <td>Python, JavaScript, Java, C++, Web Development</td>
                    <td><span class="badge bg-warning">Intermediate</span></td>
                    <td>Software Engineer, Web Developer, AI Specialist</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="showInfo('Programming & Software Development', 'Master coding skills for web, AI, and software development.')">View Path</button>
                    </td>
                </tr>
                <tr>
                    <td>📊 Data Science & AI</td>
                    <td>Machine Learning, Deep Learning, Data Analytics</td>
                    <td><span class="badge bg-success">Advanced</span></td>
                    <td>Data Scientist, AI Engineer, Research Analyst</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="showInfo('Data Science & AI', 'Learn AI, ML, and data analytics to become a top industry expert.')">View Path</button>
                    </td>
                </tr>
                <tr>
                    <td>🌐 Cybersecurity & Ethical Hacking</td>
                    <td>Network Security, Ethical Hacking, Cryptography</td>
                    <td><span class="badge bg-danger">Beginner</span></td>
                    <td>Cybersecurity Analyst, Ethical Hacker, Security Consultant</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="showInfo('Cybersecurity', 'Secure systems and learn ethical hacking best practices.')">View Path</button>
                    </td>
                </tr>
                <tr>
                    <td>📈 Business & Project Management</td>
                    <td>Agile, Scrum, Leadership, Communication</td>
                    <td><span class="badge bg-primary">Intermediate</span></td>
                    <td>Project Manager, Business Analyst, Consultant</td>
                    <td>
                        <button class="btn btn-secondary btn-sm" onclick="showInfo('Business & Management', 'Develop leadership and project management skills for business success.')">View Path</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ SweetAlert Function for Informational Popups -->
<script>
    function showInfo(title, description) {
        Swal.fire({
            title: `🚀 ${title}`,
            text: description,
            icon: "info",
            confirmButtonText: "Got It!"
        });
    }
</script>
<p><br>

<div class="container mt-5">
    <h4 class="mb-4">🌟 Career Roadmap Explorer</h4>
    <p class="text-muted">Discover career opportunities and required skills for success.</p>

    <div class="row">
        <!-- Software Development Path -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://source.unsplash.com/400x250/?programming,software" class="card-img-top" alt="Software Development">
                <div class="card-body">
                    <h5 class="card-title">💻 Software Development</h5>
                    <p class="card-text">Master coding skills to build software, websites, and applications.</p>
                    <button class="btn btn-primary" onclick="showInfo('Software Development', 'Learn Python, Java, JavaScript, and full-stack development to become a Software Engineer.')">Explore</button>
                </div>
            </div>
        </div>

        <!-- Data Science Path -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://source.unsplash.com/400x250/?data,analytics" class="card-img-top" alt="Data Science">
                <div class="card-body">
                    <h5 class="card-title">📊 Data Science & AI</h5>
                    <p class="card-text">Analyze data, build AI models, and develop machine learning applications.</p>
                    <button class="btn btn-success" onclick="showInfo('Data Science & AI', 'Become a Data Scientist by mastering Python, Machine Learning, and Deep Learning.')">Explore</button>
                </div>
            </div>
        </div>

        <!-- Cybersecurity Path -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://source.unsplash.com/400x250/?cybersecurity,hacking" class="card-img-top" alt="Cybersecurity">
                <div class="card-body">
                    <h5 class="card-title">🔒 Cybersecurity & Ethical Hacking</h5>
                    <p class="card-text">Protect systems, secure networks, and prevent cyber threats.</p>
                    <button class="btn btn-warning" onclick="showInfo('Cybersecurity', 'Learn Ethical Hacking, Network Security, and Cryptography to defend against cyber attacks.')">Explore</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- Business Management Path -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="https://source.unsplash.com/400x250/?business,management" class="card-img-top" alt="Business Management">
                <div class="card-body">
                    <h5 class="card-title">📈 Business & Management</h5>
                    <p class="card-text">Develop leadership, communication, and business strategy skills.</p>
                    <button class="btn btn-info" onclick="showInfo('Business & Management', 'Master Agile, Scrum, and Leadership skills to excel in project management.')">Explore</button>
                </div>
            </div>
        </div>
    </div>
</div>




<p><br><p><br>  
</div>





</div>








</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   