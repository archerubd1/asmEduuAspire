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
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <div class="card"> 
        <div class="container mt-5">
          <h2 class="mb-3">📢 Instructor Discussion Forum</h2>
          <p class="text-muted">Engage with fellow instructors on best practices, curriculum development, and pedagogy.</p>

          <!-- 🔍 Search & Filter Bar -->
          <div class="d-flex justify-content-between mb-3">
              <input type="text" class="form-control w-50" placeholder="Search discussions..." id="searchDiscussions">
              <select class="form-select w-25">
                  <option>Filter by Category</option>
                  <option>📚 Teaching Strategies</option>
                  <option>📖 Curriculum Development</option>
                  <option>📊 Assessment Techniques</option>
                  <option>🎓 Student Engagement</option>
                  <option>🏆 Professional Development</option>
              </select>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newDiscussionModal">+ Start Discussion</button>
          </div>

          <!-- Discussion List -->
          <div class="list-group">
              <!-- Discussion 1 -->
              <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#discussionModal1">
                  <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">📖 Best Practices for Online Assessments?</h5>
                      <small>Posted by <strong>Dr. Emily Carter</strong> - 3 hours ago</small>
                  </div>
                  <p class="mb-1">How do you ensure fair and effective online exams for students?</p>
                  <small>
                      <span class="badge bg-primary">Assessment Techniques</span>
                      <span class="text-success">✅ 6 Replies</span> | 
                      <span class="text-muted">👍 12 Upvotes</span> | 
                      <span class="text-danger">🚩 0 Reports</span>
                  </small>
              </a>

              <!-- Discussion 2 -->
              <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#discussionModal2">
                  <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">🎓 Strategies to Improve Student Engagement?</h5>
                      <small>Posted by <strong>Prof. Mark Johnson</strong> - 1 day ago</small>
                  </div>
                  <p class="mb-1">What methods have worked best for keeping students actively engaged?</p>
                  <small>
                      <span class="badge bg-success">Student Engagement</span>
                      <span class="text-success">✅ 10 Replies</span> | 
                      <span class="text-muted">👍 20 Upvotes</span> | 
                      <span class="text-danger">🚩 0 Reports</span>
                  </small>
              </a>
          </div>
        </div>
        <br>
      </div>

      <!-- 📌 Start a New Discussion Modal -->
      <div class="modal fade" id="newDiscussionModal" tabindex="-1">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">+ Start a New Discussion</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <form>
                          <div class="mb-3">
                              <label class="form-label">📌 Discussion Title</label>
                              <input type="text" class="form-control" placeholder="Enter a clear and concise title">
                          </div>

                          <div class="mb-3">
                              <label class="form-label">💬 Discussion Content</label>
                              <textarea class="form-control" rows="4" placeholder="Write your discussion details..."></textarea>
                          </div>

                          <div class="mb-3">
                              <label class="form-label">📂 Select Category</label>
                              <select class="form-select">
                                  <option>📚 Teaching Strategies</option>
                                  <option>📖 Curriculum Development</option>
                                  <option>📊 Assessment Techniques</option>
                                  <option>🎓 Student Engagement</option>
                                  <option>🏆 Professional Development</option>
                              </select>
                          </div>

                          <button type="button" class="btn btn-primary">Post Discussion</button>
                      </form>
                  </div>
              </div>
          </div>

  </div>
</div>

<?php 
require_once('../platformFooter.php');
?>
