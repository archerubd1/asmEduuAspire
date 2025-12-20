<?php
/**
 * Astraal LXP - Instructor Adaptive learning Paths
 * Refactored for new session-guard workflow (PHP 5.4 compatible)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php'); // ✅ ensures unified phx_user_* sessions

// Ensure session is active and valid
if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

$page = "ganification";
require_once('instructorHead_Nav2.php');
?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('instructorNav.php');   ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
             

<div class="card">
<div class="container mt-5">
    <h4 class="mb-4">📚 Learning Resources</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Resource Type</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Course Materials</td>
                    <td>PDFs, slides, and reference notes for courses.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Course Materials', 'Includes downloadable PDFs, slides, and notes for reference.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Video Tutorials</td>
                    <td>Pre-recorded lectures and demonstrations.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Video Tutorials', 'Watch recorded sessions with step-by-step explanations.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Interactive Modules</td>
                    <td>Hands-on activities and exercises.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Interactive Modules', 'Engage in interactive simulations and exercises.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>eBooks & Guides</td>
                    <td>Downloadable digital books and manuals.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('eBooks & Guides', 'Access comprehensive guides and reference materials.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Case Studies</td>
                    <td>Real-world examples and industry applications.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Case Studies', 'Explore real-world use cases and problem-solving strategies.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Whitepapers</td>
                    <td>Research papers and in-depth reports.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Whitepapers', 'Deep dive into technical research and emerging trends.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Assessments & Quizzes</td>
                    <td>Self-assessment tests to gauge understanding.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Assessments & Quizzes', 'Test your knowledge with quizzes and assessments.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Discussion Forums</td>
                    <td>Peer and mentor-led discussions.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Discussion Forums', 'Engage in discussions and ask questions in the community.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Webinars & Live Sessions</td>
                    <td>Recorded & upcoming live learning events.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Webinars & Live Sessions', 'Join live webinars or access recorded learning events.')">🔍 View Details</button></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>Infographics</td>
                    <td>Visual summaries of key concepts.</td>
                    <td><button class="btn btn-primary btn-sm" onclick="showDetails('Infographics', 'Learn through engaging visual representations.')">🔍 View Details</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<p><br><br>
</div>

<script>
    function showDetails(title, description) {
        Swal.fire({
            title: title,
            text: description,
            icon: 'info',
            confirmButtonText: 'Got it!'
        });
    }
</script>




  
</div>
</div>
 <!-- / Content -->





<?php 
require_once('../platformFooter.php');
?>
