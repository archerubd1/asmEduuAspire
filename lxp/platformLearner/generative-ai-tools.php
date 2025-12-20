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
    <h4 class="mb-4 ">🤖 Gen AI-Powered Tools</h4>
    <p class="text-muted ">Boost your learning with AI-driven tools for research, coding, writing, and career guidance.</p>

    <div class="row">
        <!-- 📚 AI Learning Assistant -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_learning_asst.jpg" height="200px" class="card-img-top" alt="AI Learning Assistant">
                <div class="card-body">
                    <h5 class="card-title">📚 AI Learning Assistant</h5>
                    <p class="card-text">Get real-time AI explanations, interactive Q&A, and study plans.</p>
                    <button class="btn btn-primary" onclick="showInfo('AI Learning Assistant', 'AI tutor that explains concepts, solves problems, and adapts lessons.')">Learn More</button>
                    <a href="https://www.khanacademy.org/khanmigo" target="_blank" class="btn btn-outline-primary">Try AI Tutor</a>
                </div>
            </div>
        </div>

        <!-- ✍️ AI Writing Assistant -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_writting_asst.png" height="200px" class="card-img-top" alt="AI Writing Assistant">
                <div class="card-body">
                    <h5 class="card-title">✍️ AI Writing Assistant</h5>
                    <p class="card-text">Generate essays, articles, and reports with AI-powered grammar correction.</p>
                    <button class="btn btn-success" onclick="showInfo('AI Writing Assistant', 'Enhance your writing with AI-generated essays, grammar correction, and smart style suggestions.')">Learn More</button>
                    <a href="https://www.grammarly.com" target="_blank" class="btn btn-outline-success">Use Grammarly AI</a>
                </div>
            </div>
        </div>

        <!-- 💻 AI Code Generator -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_code_generator.jpg" height="200px"class="card-img-top" alt="AI Code Generator">
                <div class="card-body">
                    <h5 class="card-title">💻 AI Code Generator</h5>
                    <p class="card-text">Generate, debug, and optimize code with AI-powered coding assistance.</p>
                    <button class="btn btn-info" onclick="showInfo('AI Code Generator', 'Get AI-generated code, instant debugging, and real-time coding suggestions.')">Learn More</button>
                    <a href="https://replit.com/" target="_blank" class="btn btn-outline-info">Try Replit AI</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- 🔍 AI Research Assistant -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_research_asst.jpg"height="200px" class="card-img-top" alt="AI Research Assistant">
                <div class="card-body">
                    <h5 class="card-title">🔍 AI Research Assistant</h5>
                    <p class="card-text">Summarize academic papers and generate citations instantly.</p>
                    <button class="btn btn-warning" onclick="showInfo('AI Research Assistant', 'Quickly summarize academic research, find references, and generate citations.')">Learn More</button>
                    <a href="https://www.elicit.org/" target="_blank" class="btn btn-outline-warning">Try Elicit AI</a>
                </div>
            </div>
        </div>

        <!-- 🎨 AI Creativity Booster -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_creativity_asst.jpg" height="200px"class="card-img-top" alt="AI Creativity Booster">
                <div class="card-body">
                    <h5 class="card-title">🎨 AI Creativity Booster</h5>
                    <p class="card-text">Generate images, brainstorm ideas, and enhance visual content.</p>
                    <button class="btn btn-danger" onclick="showInfo('AI Creativity Booster', 'AI-powered design tool for generating visuals, images, and presentation slides.')">Learn More</button>
                    <a href="https://www.canva.com/magic" target="_blank" class="btn btn-outline-danger">Try Canva AI</a>
                </div>
            </div>
        </div>

        <!-- 🏆 AI Career Advisor -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_career_asst.png" height="200px"class="card-img-top" alt="AI Career Advisor">
                <div class="card-body">
                    <h5 class="card-title">🏆 AI Career Advisor</h5>
                    <p class="card-text">Get job recommendations, resume feedback, and mock interviews.</p>
                    <button class="btn btn-dark" onclick="showInfo('AI Career Advisor', 'AI-generated resume feedback, job recommendations, and career growth insights.')">Learn More</button>
                    <a href="https://www.resumeworded.com" target="_blank" class="btn btn-outline-dark">Use Resume AI</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert Function for Informational Popups -->
<script>
    function showInfo(title, description) {
        Swal.fire({
            title: `🤖 ${title}`,
            text: description,
            icon: "info",
            confirmButtonText: "Got It!"
        });
    }
</script>




<div class="container mt-5">
    <h4 class="mb-4">🤖 AI-Powered Learning & Thinking Tools</h4>
    <p class="text-muted">Boost problem-solving, critical thinking, lifelong learning, and work-life skills with AI.</p>

    <div class="row">
        <!-- 🧠 AI for Problem-Solving -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_problem_solving.jpg" height="200px"class="card-img-top" alt="AI Problem-Solving">
                <div class="card-body">
                    <h5 class="card-title">🧠 AI for Problem-Solving</h5>
                    <p class="card-text">Get AI-driven strategies to tackle logic puzzles, math, and decision-making challenges.</p>
                    <button class="btn btn-primary" onclick="showInfo('AI for Problem-Solving', 'Improve problem-solving with AI-powered logic, math solutions, and critical reasoning exercises.')">Learn More</button>
                    <a href="https://www.wolframalpha.com" target="_blank" class="btn btn-outline-primary">Try Wolfram Alpha</a>
                </div>
            </div>
        </div>

        <!-- 🔍 AI for Critical Thinking -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_critical_thinking.png" height="200px"class="card-img-top" alt="AI Critical Thinking">
                <div class="card-body">
                    <h5 class="card-title">🔍 AI for Critical Thinking</h5>
                    <p class="card-text">Sharpen your reasoning and decision-making with AI-powered critical thinking tools.</p>
                    <button class="btn btn-success" onclick="showInfo('AI for Critical Thinking', 'Analyze arguments, improve logical thinking, and evaluate information with AI.')">Learn More</button>
                    <a href="https://yourlogicalfallacyis.com" target="_blank" class="btn btn-outline-success">Explore Logical Fallacies</a>
                </div>
            </div>
        </div>

        <!-- 🎓 AI for Lifelong Learning (Edu 5.0) -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_lifelong_learning.png" height="200px"class="card-img-top" alt="AI Lifelong Learning">
                <div class="card-body">
                    <h5 class="card-title">🎓 AI for Lifelong Learning (Edu 5.0)</h5>
                    <p class="card-text">AI-driven courses, micro-learning, and skill recommendations tailored for lifelong learners.</p>
                    <button class="btn btn-info" onclick="showInfo('AI for Lifelong Learning', 'Discover AI-driven micro-learning modules, upskilling recommendations, and future career pathways.')">Learn More</button>
                    <a href="https://www.coursera.org" target="_blank" class="btn btn-outline-info">Explore Coursera AI</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- 🤝 AI for Collaborative Learning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_collaborative_learning.jpg"height="200px" class="card-img-top" alt="AI Collaborative Learning">
                <div class="card-body">
                    <h5 class="card-title">🤝 AI for Collaborative Learning</h5>
                    <p class="card-text">Engage in AI-powered group discussions, study teams, and peer learning.</p>
                    <button class="btn btn-warning" onclick="showInfo('AI for Collaborative Learning', 'AI-powered study groups, discussion boards, and project-based learning environments.')">Learn More</button>
                    <a href="https://www.edmodo.com" target="_blank" class="btn btn-outline-warning">Join Edmodo</a>
                </div>
            </div>
        </div>

        <!-- 💼 AI for Work-Life Balance -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_with_worklife.jpeg" height="200px"class="card-img-top" alt="AI Work-Life Balance">
                <div class="card-body">
                    <h5 class="card-title">💼 AI for Work-Life Balance</h5>
                    <p class="card-text">Track productivity, set goals, and optimize work-life balance using AI-driven insights.</p>
                    <button class="btn btn-danger" onclick="showInfo('AI for Work-Life Balance', 'Monitor your focus, productivity, and stress levels with AI-driven insights and suggestions.')">Learn More</button>
                    <a href="https://www.rescuetime.com" target="_blank" class="btn btn-outline-danger">Use RescueTime AI</a>
                </div>
            </div>
        </div>

        <!-- 🚀 AI for Future Skills -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../assets/img/learner/ai_future_skills.jpg" height="200px"class="card-img-top" alt="AI Future Skills">
                <div class="card-body">
                    <h5 class="card-title">🚀 AI for Future Skills</h5>
                    <p class="card-text">Learn about AI-driven skill gaps, emerging career trends, and automation-proof jobs.</p>
                    <button class="btn btn-dark" onclick="showInfo('AI for Future Skills', 'AI-driven insights on skills for the future, automation-proof careers, and job market trends.')">Learn More</button>
                    <a href="https://www.futurelearn.com" target="_blank" class="btn btn-outline-dark">Explore FutureLearn</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ SweetAlert Function for Informational Popups -->
<script>
    function showInfo(title, description) {
        Swal.fire({
            title: `🤖 ${title}`,
            text: description,
            icon: "info",
            confirmButtonText: "Got It!"
        });
    }
</script>




<p><br><p><br>  
</div>





</div>








</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   