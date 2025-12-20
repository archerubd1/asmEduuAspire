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
    <h3 class="mb-3">🎮 AI-Driven Gamification & Personalization</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Category</th>
                <th>Recommended Action</th>
                <th>AI Justification</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gamification -->
            <tr>
                <td>🏆 New Badges Available</td>
                <td>Problem Solver Badge</td>
                <td>You solved 50+ coding challenges.</td>
                <td><button class="btn btn-primary btn-sm">Claim Badge</button></td>
            </tr>
            <tr>
                <td>🔥 Streak Reward</td>
                <td>7-Day Learning Streak Bonus</td>
                <td>You've completed daily learning for 7 days.</td>
                <td><button class="btn btn-success btn-sm">Collect XP</button></td>
            </tr>
            <tr>
                <td>📊 Leaderboard Challenge</td>
                <td>Top 10 in AI & Data Science</td>
                <td>You're in the top 10% based on learning hours.</td>
                <td><button class="btn btn-info btn-sm">View Rank</button></td>
            </tr>
            <tr>
                <td>🎯 XP Points Boost</td>
                <td>Extra XP for Completing a Quiz</td>
                <td>Earn 100 XP by acing the next assessment.</td>
                <td><button class="btn btn-dark btn-sm">Take Quiz</button></td>
            </tr>

            <!-- Personalization -->
            <tr>
                <td>📚 AI-Based Course Suggestion</td>
                <td>Deep Learning with TensorFlow</td>
                <td>Matched to your career goals and past activity.</td>
                <td><button class="btn btn-outline-primary btn-sm">Start Course</button></td>
            </tr>
            <tr>
                <td>🔄 Skill Progress Tracking</td>
                <td>90% Proficiency in Cybersecurity</td>
                <td>AI recommends you take a certification exam.</td>
                <td><button class="btn btn-outline-success btn-sm">Get Certified</button></td>
            </tr>
            <tr>
                <td>📢 Career Goal Optimization</td>
                <td>Align Courses to Your Resume</td>
                <td>AI suggests adding completed skills to your portfolio.</td>
                <td><button class="btn btn-outline-warning btn-sm">Update Resume</button></td>
            </tr>
        </tbody>
    </table>
</div>



  
</div>
<p><br>

<div class="card">



  <div class="container mt-4">
    <h3 class="mb-3">🤖 AI-Powered Learning Recommendations</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Category</th>
                <th>Recommended Learning Path</th>
                <th>AI Justification</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Learning Paths -->
            <tr>
                <td>📚 Active Courses</td>
                <td>Full Stack Web Development</td>
                <td>Based on your recent projects in front-end development.</td>
                <td><button class="btn btn-primary btn-sm">Enroll Now</button></td>
            </tr>
            <tr>
                <td>🔥 Curated Courses</td>
                <td>Data Science with Python</td>
                <td>High-demand skill in your industry + past AI course completed.</td>
                <td><button class="btn btn-success btn-sm">Start Learning</button></td>
            </tr>
            <tr>
                <td>⚡ Skills Booster</td>
                <td>Advanced Critical Thinking & Decision Making</td>
                <td>Enhance strategic thinking for leadership roles.</td>
                <td><button class="btn btn-info btn-sm">Boost Skill</button></td>
            </tr>
            <tr>
                <td>🎯 Level Up Courses</td>
                <td>Machine Learning & AI</td>
                <td>You're ready for advanced AI concepts based on your progress.</td>
                <td><button class="btn btn-dark btn-sm">Upgrade Now</button></td>
            </tr>
            <tr>
                <td>⭐ Crowd Favorites</td>
                <td>Cybersecurity Essentials</td>
                <td>Trending topic + matched with your career path.</td>
                <td><button class="btn btn-warning btn-sm">Explore</button></td>
            </tr>

            <!-- Specific Skills -->
            <tr>
                <td>🧠 Problem Solving Skills</td>
                <td>Logical & Analytical Reasoning</td>
                <td>Improve structured thinking for real-world problem-solving.</td>
                <td><button class="btn btn-outline-primary btn-sm">Improve</button></td>
            </tr>
            <tr>
                <td>💻 Coding Ground</td>
                <td>Competitive Programming & DSA</td>
                <td>Practice algorithmic challenges and improve coding speed.</td>
                <td><button class="btn btn-outline-success btn-sm">Start Coding</button></td>
            </tr>
            <tr>
                <td>🧩 Critical Thinking</td>
                <td>Decision-Making Strategies</td>
                <td>Enhance reasoning & judgment for career success.</td>
                <td><button class="btn btn-outline-info btn-sm">Enroll</button></td>
            </tr>
            <tr>
                <td>📊 Project Management</td>
                <td>Agile & Scrum Mastery</td>
                <td>Ideal for leadership & management roles.</td>
                <td><button class="btn btn-outline-dark btn-sm">Join</button></td>
            </tr>
            <tr>
                <td>🤝 Collaborative Learning</td>
                <td>Team-Based Learning & Problem-Solving</td>
                <td>Improve teamwork & cross-functional communication.</td>
                <td><button class="btn btn-outline-warning btn-sm">Join Group</button></td>
            </tr>
            <tr>
                <td>🌍 Work Life Experience</td>
                <td>Remote Work & Productivity</td>
                <td>Optimize work-life balance and remote collaboration.</td>
                <td><button class="btn btn-outline-secondary btn-sm">Start</button></td>
            </tr>
            <tr>
                <td>🔬 Edu 5.0 Lifelong Learning</td>
                <td>Continuous Upskilling in Emerging Tech</td>
                <td>Stay updated with evolving industry trends.</td>
                <td><button class="btn btn-outline-primary btn-sm">Explore</button></td>
            </tr>
            <tr>
                <td>🎓 Skills & Competencies</td>
                <td>Soft Skills for Career Growth</td>
                <td>Enhance leadership, negotiation, and adaptability.</td>
                <td><button class="btn btn-outline-success btn-sm">Develop</button></td>
            </tr>
            <tr>
                <td>🧑‍🏫 Mentorship & Social Learning</td>
                <td>Find a Mentor in AI & Tech</td>
                <td>Connect with industry experts for guidance.</td>
                <td><button class="btn btn-outline-info btn-sm">Find Mentor</button></td>
            </tr>
        </tbody>
    </table>
</div>


<p><br>



</div>








</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   