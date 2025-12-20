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

// Fetch courses assigned to the instructor
$sql = "SELECT c.id, c.name 
        FROM courses c
        JOIN users_to_courses uc ON c.id = uc.courses_ID
        WHERE uc.users_LOGIN = ?";

$stmt = $coni->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $coni->error);
}

$stmt->bind_param("s", $user_name);
$stmt->execute();

// Use bind_result() instead of get_result() for compatibility
$stmt->bind_result($course_id, $course_name);

$courses = [];
while ($stmt->fetch()) {
    $courses[] = ['id' => $course_id, 'name' => $course_name];
}
$stmt->close();
?>

<!-- Layout container -->
<div class="layout-page">
    <?php require_once('instructorNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">

                <!-- Display success or error messages -->
                <?php if (isset($_GET['msg'])): ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            swal.fire("Successful!", "<?= htmlspecialchars(base64_decode($_GET['msg'])); ?>", "success");
                            history.replaceState({}, document.title, window.location.pathname);
                        });
                    </script>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            swal.fire("Invalid Registration!", "<?= htmlspecialchars(base64_decode($_GET['error'])); ?>", "error");
                            history.replaceState({}, document.title, window.location.pathname);
                        });
                    </script>
                <?php endif; ?>

                <div class="col-lg-12 mb-4 order-0">
                    <!-- Accordion for course description -->
                    <div class="accordion mt-3" id="accordionExample">
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="heading3">
                                <button type="button" class="accordion-button bg-label-primary"
                                    data-bs-toggle="collapse" data-bs-target="#accordion1" aria-expanded="true"
                                    aria-controls="accordion1">
                                    <i class="bx bx-cloud-upload"></i> &nbsp;&nbsp; Upload & Manage Course Resources
                                </button>
                            </h4>
                            <div id="accordion1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-12">
                                            <div class="card-body">
                                                <div class="container mt-5">
                                                    <form action="instructorForms.php" method="post" enctype="multipart/form-data">
                                                        <!-- Hidden Input -->
                                                        <input type="hidden" name="processType" value="courseLibrary">
                                                        <input type="hidden" name="userName" value="<?= htmlspecialchars($user_name); ?>">

                                                        <!-- Learning Category Resources -->
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="learning_category" class="form-label">
                                                                    <i class="bx bx-category" style="color: #28a745;"></i> Select Learning Category
                                                                </label>
                                                                <select class="form-control" id="learning_category" name="learning_category" required>
                                                                    <option value="learning_paths">Learning Paths</option>
                                                                    <option value="problem_solving">Problem Solving</option>
                                                                    <option value="coding_problems">Coding Problems</option>
                                                                    <option value="critical_thinking">Critical Thinking</option>
                                                                    <option value="projects">Projects</option>
                                                                    <option value="collaborative_learning">Collaborative Learning</option>
                                                                    <option value="work_life_experience">Work Life Experience</option>
                                                                    <option value="edu_5_lifelong_learning">Edu 5.0 Lifelong Learning</option>
                                                                    <option value="skills_and_competencies">Skills and Competencies</option>
                                                                    <option value="social_learning">Social Learning</option>
                                                                </select>
                                                            </div>

                                                            <!-- Course Name -->
                                                            <div class="col-md-6">
                                                                <label for="course_name" class="form-label">
                                                                    <i class="bx bx-book" style="color: #007bff;"></i> Course Name
                                                                </label>
                                                                <select class="form-control" id="course_name" name="course_name" required>
                                                                    <option value="">Select a Course</option>
                                                                    <?php foreach ($courses as $course): ?>
                                                                        <option value="<?= htmlspecialchars($course['name']); ?>">
                                                                            <?= htmlspecialchars($course['name']); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Type of Resource Uploaded -->
                                                        <div class="mb-3">
                                                            <label for="resource_type" class="form-label">
                                                                <i class="bx bx-file" style="color: #17a2b8;"></i> Type of Resource
                                                            </label>
                                                            <select class="form-control" id="resource_type" name="resource_type" required>
                                                                <option value="pdf">PDF</option>
                                                                <option value="ppt">PPT</option>
                                                                <option value="video">Video</option>
                                                                <option value="assignments">Assignments</option>
                                                                <option value="quizzes">Quizzes</option>
                                                            </select>
                                                        </div>

                                                        <!-- File Upload -->
                                                        <div class="mb-3">
                                                            <label for="files" class="form-label">
                                                                <i class="bx bx-upload" style="color: #ffc107;"></i> Upload Files (PDF, PPT, Video, Assignments, Quizzes)
                                                            </label>
                                                            <input type="file" class="form-control" id="files" name="files[]" multiple required>
                                                        </div>

                                                        <!-- Access Level Selection -->
                                                        <div class="mb-3">
                                                            <label for="access_level" class="form-label">
                                                                <i class="bx bx-lock" style="color: #dc3545;"></i> Access Level
                                                            </label>
                                                            <div class="input-group">
                                                                <label class="input-group-text" for="access_level">
                                                                    <i class="bx bx-cloud-upload" style="color: #6f42c1;"></i>
                                                                </label>
                                                                <select class="form-select" id="access_level" name="access_level" required>
                                                                    <option value="public">Public</option>
                                                                    <option value="private">Private</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Submit Button -->
                                                        <button type="submit" id="courseLibrary" name="courseLibrary" class="btn btn-primary w-100">
                                                            <i class="bx bx-cloud-upload"></i> Upload Files
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <!-- End Accordion -->
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../platformFooter.php'); ?>
</div>
