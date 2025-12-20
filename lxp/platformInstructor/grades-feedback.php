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
    <?php require_once('instructorNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
          <div class="card">     
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
<p><br><br>
</div>


<!-- Grade Stats Modal -->
<div class="modal fade" id="gradeStatsModal" tabindex="-1" aria-labelledby="gradeStatsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeStatsLabel">Grade Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Student:</strong> <span id="gradeStudent"></span></p>
                <p><strong>Grade:</strong> <span id="gradeValue"></span></p>
                <p><strong>Performance Analysis:</strong></p>
                <ul>
                    <li>Top 10% in the class</li>
                    <li>Improved by 15% compared to last assessment</li>
                    <li>Needs more focus on project execution</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackLabel">Feedback Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Student:</strong> <span id="feedbackStudent"></span></p>
                <p><strong>Feedback:</strong></p>
                <p id="feedbackComment"></p>
            </div>
        </div>
    </div>
</div>

<script>
    function loadGradeStats(student, grade) {
        document.getElementById('gradeStudent').innerText = student;
        document.getElementById('gradeValue').innerText = grade;
    }

    function loadFeedback(student, comment) {
        document.getElementById('feedbackStudent').innerText = student;
        document.getElementById('feedbackComment').innerText = comment;
    }
</script>         













                <?php require_once('../platformFooter.php'); ?>
           
   