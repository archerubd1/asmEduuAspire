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
<div class="card-body">

<div class="container mt-5">
    <h4 class="mb-4 text-primary">
        <i class='bx bx-calendar-event bx-md' style="color:#007bff;"></i> Upcoming Live Sessions
    </h4>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <th>Topic</th>
                    <th>Instructor / Mentor</th>
                    <th>Date & Time</th>
                    <th>Duration</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Data Science Fundamentals</td>
                    <td>Dr. Rajesh Malhotra</td>
                    <td>March 10, 2025 | 6:00 PM IST</td>
                    <td>90 mins</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewProfile('Dr. Rajesh Malhotra', 'Expert in Data Science & AI with 15+ years of experience.', 'profile1.jpg')">
                            <i class='bx bx-user'></i> View Profile
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="enrollSession('Data Science Fundamentals', 'Dr. Rajesh Malhotra', 'March 10, 2025 | 6:00 PM IST')">
                            <i class='bx bx-edit-alt'></i> Enroll
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Artificial Intelligence & Machine Learning</td>
                    <td>Dr. Priya Sharma</td>
                    <td>March 12, 2025 | 7:30 PM IST</td>
                    <td>120 mins</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewProfile('Dr. Priya Sharma', 'AI Researcher & ML Engineer, passionate about deep learning.', 'profile2.jpg')">
                            <i class='bx bx-user'></i> View Profile
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="enrollSession('AI & ML', 'Dr. Priya Sharma', 'March 12, 2025 | 7:30 PM IST')">
                            <i class='bx bx-edit-alt'></i> Enroll
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Cybersecurity Essentials</td>
                    <td>Ms. Sneha Verma</td>
                    <td>March 18, 2025 | 8:00 PM IST</td>
                    <td>75 mins</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewProfile('Ms. Sneha Verma', 'Cybersecurity Analyst specializing in ethical hacking.', 'profile3.jpg')">
                            <i class='bx bx-user'></i> View Profile
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="enrollSession('Cybersecurity Essentials', 'Ms. Sneha Verma', 'March 18, 2025 | 8:00 PM IST')">
                            <i class='bx bx-edit-alt'></i> Enroll
                        </button>
                    </td>
                </tr>
				<tr>
                    <td>Blockchain for Beginners</td>
                    <td>Mr. Rohan Mehta</td>
                    <td>March 20, 2025 | 5:00 PM IST</td>
                    <td>90 mins</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewProfile('Mr. Rohan Mehta', 'Blockchain developer and Web3 strategist.', 'profile4.jpg')">
                            <i class='bx bx-user'></i> View Profile
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="enrollSession('Blockchain for Beginners', 'Mr. Rohan Mehta', 'March 20, 2025 | 5:00 PM IST')">
                            <i class='bx bx-edit-alt'></i> Enroll
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>Python for Data Analysis</td>
                    <td>Dr. Neha Kapoor</td>
                    <td>March 22, 2025 | 4:00 PM IST</td>
                    <td>100 mins</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewProfile('Dr. Neha Kapoor', 'Python expert with a focus on data analytics.', 'profile5.jpg')">
                            <i class='bx bx-user'></i> View Profile
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success" onclick="enrollSession('Python for Data Analysis', 'Dr. Neha Kapoor', 'March 22, 2025 | 4:00 PM IST')">
                            <i class='bx bx-edit-alt'></i> Enroll
                        </button>
                    </td>
                </tr>
							
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for View Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Instructor Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="profileImage" src="" class="rounded-circle mb-3" width="120" height="120" alt="Profile Image">
                <h5 id="profileName"></h5>
                <p id="profileDescription"></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Enrollment Form -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollModalLabel">Enroll in Live Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Topic:</strong> <span id="sessionTopic"></span></p>
                <p><strong>Instructor:</strong> <span id="sessionInstructor"></span></p>
                <p><strong>Date & Time:</strong> <span id="sessionDate"></span></p>
                <hr>
                <form>
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" placeholder="Enter your phone number">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Enrollment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function viewProfile(name, description, image) {
    document.getElementById('profileName').innerText = name;
    document.getElementById('profileDescription').innerText = description;
    document.getElementById('profileImage').src = image;
    var profileModal = new bootstrap.Modal(document.getElementById('profileModal'));
    profileModal.show();
}

function enrollSession(topic, instructor, date) {
    document.getElementById('sessionTopic').innerText = topic;
    document.getElementById('sessionInstructor').innerText = instructor;
    document.getElementById('sessionDate').innerText = date;
    var enrollModal = new bootstrap.Modal(document.getElementById('enrollModal'));
    enrollModal.show();
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
