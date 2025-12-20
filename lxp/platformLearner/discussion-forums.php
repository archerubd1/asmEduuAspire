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
    <h2 class="mb-3">💬 Learner Discussion Forum</h2>
    <p class="text-muted">Join discussions with peers, mentors, and instructors.</p>

    <!-- 🔍 Search & Filter Bar -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" class="form-control w-50" placeholder="Search discussions..." id="searchDiscussions">
        <select class="form-select w-25">
            <option>Filter by Category</option>
            <option>📚 General Discussions</option>
            <option>📖 Course Topics</option>
            <option>🚀 Career & Job Guidance</option>
            <option>💡 Projects & Assignments</option>
            <option>🆘 Help & Support</option>
        </select>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newDiscussionModal">+ Start Discussion</button>
    </div>

    <!-- Discussion List -->
    <div class="list-group">
        <!-- Discussion 1 -->
        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#discussionModal1">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">📖 Best Resources to Learn Python?</h5>
                <small>Posted by <strong>John Doe (Learner)</strong> - 2 hours ago</small>
            </div>
            <p class="mb-1">I’m looking for great Python learning resources. Any recommendations?</p>
            <small>
                <span class="badge bg-primary">Course Topic</span>
                <span class="text-success">✅ 5 Replies</span> | 
                <span class="text-muted">👍 10 Upvotes</span> | 
                <span class="text-danger">🚩 1 Report</span>
            </small>
        </a>

        <!-- Discussion 2 -->
        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#discussionModal2">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">🚀 How to Prepare for Technical Interviews?</h5>
                <small>Posted by <strong>Sarah Lee (Mentor)</strong> - 1 day ago</small>
            </div>
            <p class="mb-1">I’ve mentored many students for FAANG interviews. Here are my top tips!</p>
            <small>
                <span class="badge bg-success">Career Advice</span>
                <span class="text-success">✅ 8 Replies</span> | 
                <span class="text-muted">👍 22 Upvotes</span> | 
                <span class="text-danger">🚩 0 Reports</span>
            </small>
        </a>
    </div>
</div>
<p><br>
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
                            <option>📚 General Discussions</option>
                            <option>📖 Course Topics</option>
                            <option>🚀 Career & Job Guidance</option>
                            <option>💡 Projects & Assignments</option>
                            <option>🆘 Help & Support</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary">Post Discussion</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 📝 Discussion Modal with Replies -->
<div class="modal fade" id="discussionModal1" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📖 Best Resources to Learn Python?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>John Doe (Learner):</strong> I’m looking for great Python learning resources. Any recommendations?</p>
                <hr>
                <h6>💬 Replies</h6>
                <p><strong>Sarah Lee (Mentor):</strong> Check out Python docs & Real Python. They're great!</p>
                <p><strong>Admin:</strong> We also have a Python learning path on EduAspire.</p>

                <hr>
                <h6>➕ Add Your Reply</h6>
                <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                <button class="btn btn-success mt-2">Submit Reply</button>
            </div>
        </div>
    </div>
</div>

<!-- 📝 Discussion Modal for the second topic -->
<div class="modal fade" id="discussionModal2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🚀 How to Prepare for Technical Interviews?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Sarah Lee (Mentor):</strong> I’ve mentored many students for FAANG interviews. Here are my top tips!</p>
                <hr>
                <h6>💬 Replies</h6>
                <p><strong>Jane (Learner):</strong> How important are LeetCode problems?</p>
                <p><strong>Sarah Lee (Mentor):</strong> Extremely important! Practice daily.</p>

                <hr>
                <h6>➕ Add Your Reply</h6>
                <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                <button class="btn btn-success mt-2">Submit Reply</button>
            </div>
        </div>
    </div>
</div>



</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   