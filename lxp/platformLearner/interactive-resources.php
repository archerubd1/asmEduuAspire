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

<div class="container mt-4">
    <h4 class="mb-3">Key Interactive Learning Resources </h4>
    <table class="table table-bordered table-striped">
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
                <td>Interactive Modules</td>
                <td>Hands-on activities, simulations, and scenario-based learning experiences.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Interactive Modules', 'Hands-on activities, simulations, and scenario-based learning experiences.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Gamified Learning</td>
                <td>Learning experiences with points, badges, leaderboards, and challenges.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Gamified Learning', 'Learning experiences with points, badges, leaderboards, and challenges.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Microlearning Nuggets</td>
                <td>Short, interactive lessons designed for quick learning and retention.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Microlearning Nuggets', 'Short, interactive lessons designed for quick learning and retention.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Simulations & Virtual Labs</td>
                <td>Real-world practice in simulated environments, especially useful for technical training.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Simulations & Virtual Labs', 'Real-world practice in simulated environments, especially useful for technical training.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Scenario-Based Learning</td>
                <td>Decision-making exercises where users learn through real-world situations.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Scenario-Based Learning', 'Decision-making exercises where users learn through real-world situations.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>6</td>
                <td>Assessments & Quizzes</td>
                <td>Interactive knowledge checks, self-assessments, and adaptive testing.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Assessments & Quizzes', 'Interactive knowledge checks, self-assessments, and adaptive testing.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>7</td>
                <td>Discussion Forums & Social Learning</td>
                <td>Peer-to-peer discussions, Q&A sessions, and collaboration with mentors.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Discussion Forums & Social Learning', 'Peer-to-peer discussions, Q&A sessions, and collaboration with mentors.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>8</td>
                <td>Webinars & Live Sessions</td>
                <td>Virtual instructor-led training (VILT) with real-time engagement.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Webinars & Live Sessions', 'Virtual instructor-led training (VILT) with real-time engagement.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>9</td>
                <td>AI-Powered Chatbots</td>
                <td>Chatbots for instant feedback, guidance, and query resolution.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('AI-Powered Chatbots', 'Chatbots for instant feedback, guidance, and query resolution.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>10</td>
                <td>AR/VR-Based Learning</td>
                <td>Immersive learning experiences through Augmented and Virtual Reality.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('AR/VR-Based Learning', 'Immersive learning experiences through Augmented and Virtual Reality.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>11</td>
                <td>Interactive Case Studies</td>
                <td>Real-world problem-solving activities that require learner participation.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Interactive Case Studies', 'Real-world problem-solving activities that require learner participation.')">🔍 View Details</button></td>
            </tr>
            <tr>
                <td>12</td>
                <td>Infographics & Interactive Media</td>
                <td>Clickable infographics, animated videos, and interactive storytelling.</td>
                <td><button class="btn btn-primary btn-sm" onclick="showDetails('Infographics & Interactive Media', 'Clickable infographics, animated videos, and interactive storytelling.')">🔍 View Details</button></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    function showDetails(title, description) {
        Swal.fire({
            title: title,
            text: description,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }
</script>






<p><br><br>
</div>
</div>
</div>
 <!-- / Content -->





<?php 
require_once('../platformFooter.php');
?>
