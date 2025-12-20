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
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"><?php echo $get['name']; ?></span> Learning & Course Analytics
    </h4>

    <div class="row">
        <div class="col-md-12">
		
		
						<!-- Tabs Navigation -->
        <ul class="nav nav-pills flex-column flex-md-row mb-3" style="gap: 15px;">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#course"><i class="bx bx-folder"></i> Course Analytics</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#learning"><i class="bx bx-book"></i> Learning Behavior</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#skills"><i class="bx bx-bulb"></i> Skills Mastery</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#assessments"><i class="bx bx-task"></i> Assessments</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#collaboration"><i class="bx bx-group"></i> Collaboration</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#coding"><i class="bx bx-code"></i> Coding & Problem-Solving</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#projects"><i class="bx bx-briefcase"></i> Projects</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#thinking"><i class="bx bx-brain"></i> Critical Thinking</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#gamification"><i class="bx bx-trophy"></i> Gamification</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#personalization"><i class="bx bx-user-check"></i> Personalization</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#career"><i class="bx bx-briefcase-alt"></i> Career Readiness</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#worklife"><i class="bx bx-time-five"></i> Work-Life Balance</a></li>
        </ul>


          <!-- Tab Content -->
        <div class="tab-content">

            <!-- Course Analytics -->
            <div class="tab-pane fade show active" id="course">
                <div class="card">
                    <h5 class="card-header">📊 Course Analytics</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Total Courses Enrolled</td><td>Number of courses the learner has enrolled in.</td></tr>
                                <tr><td>Course Completion Rate (%)</td><td>Percentage of courses completed vs. enrolled.</td></tr>
                                <tr><td>Average Course Progress (%)</td><td>Average progress across all enrolled courses.</td></tr>
                                <tr><td>Time Spent per Course (hrs)</td><td>Total study time logged for each course.</td></tr>
                                <tr><td>Daily/Weekly Study Hours</td><td>Learning consistency based on study hours.</td></tr>
                                <tr><td>Course Engagement Rate</td><td>Number of lessons completed vs. total available.</td></tr>
                                <tr><td>Active vs. Inactive Courses</td><td>List of ongoing and inactive courses.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			
			<!-- 2️⃣ Learning Behavior -->
            <div class="tab-pane fade" id="learning">
                <div class="card">
                    <h5 class="card-header">📚 Learning Behavior</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Time Spent on Learning Platform</td><td>Total time learner spends using the platform.</td></tr>
                                <tr><td>Study Streak (Days)</td><td>Number of consecutive days of learning.</td></tr>
                                <tr><td>Preferred Learning Method</td><td>Video, text-based, quizzes, hands-on, etc.</td></tr>
                                <tr><td>Peak Learning Hours</td><td>Time of the day when the learner is most active.</td></tr>
                                <tr><td>Skipped vs. Completed Lessons</td><td>Tracks skipped vs. fully completed lessons.</td></tr>
                                <tr><td>Revisited Lessons (%)</td><td>Percentage of lessons revisited for review.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3️⃣ Skills Mastery -->
            <div class="tab-pane fade" id="skills">
                <div class="card">
                    <h5 class="card-header">🎯 Skills Mastery</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Top Hard Skills Gained</td><td>Programming, AI, cybersecurity, etc.</td></tr>
                                <tr><td>Top Soft Skills Gained</td><td>Leadership, communication, time management, etc.</td></tr>
                                <tr><td>Skill Proficiency Levels (%)</td><td>Beginner, intermediate, advanced for each skill.</td></tr>
                                <tr><td>Industry-Relevant Skills Progress</td><td>Matches skills with career goals.</td></tr>
                                <tr><td>Peer Skill Comparison</td><td>Compares skill growth with other learners.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			
			

            <!-- 4️⃣ Assessments -->
            <div class="tab-pane fade" id="assessments">
                <div class="card">
                    <h5 class="card-header">📝 Assessments & Performance Tracking</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Total Assessments Taken</td><td>Number of quizzes, exams, and assignments.</td></tr>
                                <tr><td>Assessment Success Rate (%)</td><td>Percentage of passed vs. failed assessments.</td></tr>
                                <tr><td>Average Quiz Score (%)</td><td>Mean score of all quizzes.</td></tr>
                                <tr><td>Top Performing Subjects</td><td>Areas where the learner excels.</td></tr>
                                <tr><td>Areas of Improvement</td><td>Subjects that require more focus.</td></tr>
                                <tr><td>Time Taken per Assessment (mins)</td><td>Average time spent per quiz/test.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5️⃣ Collaboration & Peer Learning -->
            <div class="tab-pane fade" id="collaboration">
                <div class="card">
                    <h5 class="card-header">🤝 Collaborative Learning & Peer Engagement</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Discussions Participated In</td><td>Number of forum discussions joined.</td></tr>
                                <tr><td>Peer Reviews Given</td><td>Feedback provided on assignments and projects.</td></tr>
                                <tr><td>Group Projects Completed</td><td>Number of projects done collaboratively.</td></tr>
                                <tr><td>Mentorship & Coaching Sessions</td><td>Track mentor interactions.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			
			
			
			<!-- 6️⃣ Coding & Problem-Solving -->
            <div class="tab-pane fade" id="coding">
                <div class="card">
                    <h5 class="card-header">💻 Coding & Problem-Solving</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Total Coding Challenges Completed</td><td>Number of coding problems solved.</td></tr>
                                <tr><td>Coding Languages Mastered</td><td>Python, JavaScript, Java, etc.</td></tr>
                                <tr><td>Algorithmic Problem-Solving Rate</td><td>Accuracy and speed in solving problems.</td></tr>
                                <tr><td>Code Quality Score</td><td>Measures best practices, efficiency, and readability.</td></tr>
                                <tr><td>Project-Based Learning</td><td>Number of real-world coding projects completed.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 7️⃣ Projects & Practical Applications -->
            <div class="tab-pane fade" id="projects">
                <div class="card">
                    <h5 class="card-header">🛠️ Projects & Practical Applications</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Total Projects Completed</td><td>Hands-on learning via project-based education.</td></tr>
                                <tr><td>Project Complexity Levels</td><td>Beginner, intermediate, advanced projects.</td></tr>
                                <tr><td>Industry-Specific Projects Done</td><td>AI, Web Dev, Data Science, etc.</td></tr>
                                <tr><td>Project Peer Reviews</td><td>Number of feedback received from peers.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 8️⃣ Critical Thinking & Problem-Solving Skills -->
            <div class="tab-pane fade" id="thinking">
                <div class="card">
                    <h5 class="card-header">🧠 Critical Thinking & Problem-Solving</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Logical Reasoning Tests Completed</td><td>Measures problem-solving ability.</td></tr>
                                <tr><td>Creativity & Innovation Index</td><td>Tracks unique solutions provided.</td></tr>
                                <tr><td>Decision-Making Scenarios Attempted</td><td>Business, strategy, and leadership challenges.</td></tr>
                                <tr><td>Problem-Solving Speed</td><td>Time taken to solve challenges.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

			<!-- 9️⃣ Gamification & Motivation -->
            <div class="tab-pane fade" id="gamification">
                <div class="card">
                    <h5 class="card-header">🏆 Gamification & Motivation</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Total Badges Earned</td><td>Progress and achievement-based badges.</td></tr>
                                <tr><td>Leaderboard Ranking</td><td>Position among peers in learning engagement.</td></tr>
                                <tr><td>Daily Learning Streaks</td><td>Number of consecutive learning days.</td></tr>
                                <tr><td>XP Points Collected</td><td>Learning points for motivation.</td></tr>
                                <tr><td>Completion Rewards</td><td>Certificates, incentives, and perks.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 🔟 Personalized Learning & Adaptive Insights -->
            <div class="tab-pane fade" id="personalization">
                <div class="card">
                    <h5 class="card-header">🎯 Personalized Learning & Adaptive Insights</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Preferred Learning Style</td><td>Text, video, interactive, practice-based.</td></tr>
                                <tr><td>AI-Powered Learning Recommendations</td><td>Suggested courses, lessons, and exercises.</td></tr>
                                <tr><td>Adaptive Learning Difficulty</td><td>Adjusts difficulty based on performance.</td></tr>
                                <tr><td>Personal Learning Goals Progress (%)</td><td>How much of personal learning goals are achieved.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 1️⃣1️⃣ Career Readiness & Job Market Alignment -->
            <div class="tab-pane fade" id="career">
                <div class="card">
                    <h5 class="card-header">💼 Career Readiness & Job Market Alignment</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Career Goal Match (%)</td><td>How well skills align with career goals.</td></tr>
                                <tr><td>Industry Skills Demand Match</td><td>Skills aligned to market demand.</td></tr>
                                <tr><td>Job Readiness Score (%)</td><td>Measures skills required for job roles.</td></tr>
                                <tr><td>Resume & Portfolio Completion (%)</td><td>Tracks completeness of resume & projects.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 1️⃣2️⃣ Work-Life Balance & Productivity -->
            <div class="tab-pane fade" id="worklife">
                <div class="card">
                    <h5 class="card-header">⚖️ Work-Life Balance & Productivity</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead><tr><th>Data Point</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr><td>Learning vs. Break Time Ratio</td><td>Tracks study-to-break balance.</td></tr>
                                <tr><td>Stress Level Indicator</td><td>Based on session lengths and engagement.</td></tr>
                                <tr><td>Focus Sessions Completed</td><td>Pomodoro-style deep work sessions tracked.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>




        </div> <!-- End of tab-content -->





<!-- Chart Container -->
        <div class="card">
            <h5 class="card-header">📈 Overall Learning & Progress</h5>
            <div class="card-body">
                <div id="consolidatedChart"></div>
            </div>
        </div>
    </div>

    <script>
        // Sample Data (Replace with real backend/API data)
        var options = {
            series: [
                { name: "Course Completion (%)", data: [85, 60, 72, 90] },
                { name: "Quiz Success Rate (%)", data: [78, 65, 85, 92] },
                { name: "Coding Challenges Solved", data: [12, 15, 18, 22] },
                { name: "Study Hours (Weekly)", data: [10, 12, 15, 8] }
            ],
            chart: {
                type: "bar",
                height: 350
            },
            plotOptions: {
                bar: { horizontal: false }
            },
            xaxis: {
                categories: ["Data Science", "MEAN Stack", "Adv. Programming", "AI & ML"]
            },
            colors: ["#008FFB", "#00E396", "#FEB019", "#FF4560"],
            dataLabels: { enabled: false },
            legend: { position: "top" }
        };

        var chart = new ApexCharts(document.querySelector("#consolidatedChart"), options);
        chart.render();
    </script>



        </div> <!-- End of col-md-12 -->
    </div> <!-- End of row -->
</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   