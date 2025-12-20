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
        <span class="text-muted fw-light">Privacy & Custom Settings for  </span> <?php echo $get['name']; ?>
    </h4>

    <div class="row">
        <div class="col-md-12">
		
		<!-- Bootstrap Tabs -->
        <ul class="nav nav-pills flex-column flex-md-row mb-3" style="gap: 15px;">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#profile"><i class="bx bx-user"></i> Profile Privacy</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#learning"><i class="bx bx-book"></i> Learning Preferences</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#notifications"><i class="bx bx-bell"></i> Notifications</a></li>
			<li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#social-learning"><i class="bx bx-group"></i> Social Learning</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#data"><i class="bx bx-chart"></i> Data & Analytics</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#security"><i class="bx bx-lock"></i> Security</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#content-accessibility"><i class="bx bx-adjust"></i> Accessibility</a></li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- 1️⃣ Profile Privacy -->
<div class="tab-pane fade show active" id="profile">
    <div class="card">
        <h5 class="card-header">👤 Profile Privacy Settings</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Profile Visibility -->
                    <tr>
                        <td>👥 Profile Visibility</td>
                        <td>Control who can see your profile.</td>
                        <td>
                            <select class="form-select">
                                <option>Public</option>
                                <option>Only Peers</option>
                                <option>Private</option>
                            </select>
                        </td>
                    </tr>

                    <!-- Profile Picture Visibility -->
                    <tr>
                        <td>🖼 Profile Picture Visibility</td>
                        <td>Show/hide profile picture from others.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="profilePictureVisibility">
                                <label class="form-check-label" for="profilePictureVisibility">Disabled</label>
                            </div>
                        </td>
                    </tr>

                    <!-- Achievements & Badges -->
                    <tr>
                        <td>🏆 Achievements & Badges</td>
                        <td>Show earned badges & certifications on profile.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="achievementsVisibility">
                                <label class="form-check-label" for="achievementsVisibility">Disabled</label>
                            </div>
                        </td>
                    </tr>

                    <!-- Location Sharing -->
                    <tr>
                        <td>📍 Location Sharing</td>
                        <td>Allow location-based course recommendations.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="locationSharing">
                                <label class="form-check-label" for="locationSharing">Disabled</label>
                            </div>
                        </td>
                    </tr>

                    <!-- Public Searchability -->
                    <tr>
                        <td>🔍 Public Searchability</td>
                        <td>Allow profile to appear in search results.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="publicSearch">
                                <label class="form-check-label" for="publicSearch">Disabled</label>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleSwitch(inputId) {
            let input = document.getElementById(inputId);
            let label = input.nextElementSibling;

            // Set initial state
            label.innerText = input.checked ? "Enabled" : "Disabled";

            // Update label on toggle
            input.addEventListener("change", function () {
                label.innerText = this.checked ? "Enabled" : "Disabled";
            });
        }

        toggleSwitch("profilePictureVisibility");
        toggleSwitch("achievementsVisibility");
        toggleSwitch("locationSharing");
        toggleSwitch("publicSearch");
    });
</script>


            <!-- 2️⃣ Learning Preferences -->
<div class="tab-pane fade" id="learning">
    <div class="card">
        <h5 class="card-header">📚 Learning Preferences</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Course Progress Sharing -->
                    <tr>
                        <td>📚 Course Progress Sharing</td>
                        <td>Share progress with mentors, peers, or private.</td>
                        <td>
                            <select class="form-select">
                                <option>Public</option>
                                <option>Peers Only</option>
                                <option>Private</option>
                            </select>
                        </td>
                    </tr>

                    <!-- Learning Streak Visibility -->
                    <tr>
                        <td>📝 Learning Streak Visibility</td>
                        <td>Show learning streak (daily activity tracking).</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="learningStreak">
                                <label class="form-check-label" for="learningStreak">Disabled</label>
                            </div>
                        </td>
                    </tr>

                    <!-- AI Course Recommendations -->
                    <tr>
                        <td>🔄 Course Recommendations</td>
                        <td>Enable personalized AI-based course suggestions.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="aiRecommendations">
                                <label class="form-check-label" for="aiRecommendations">Disabled</label>
                            </div>
                        </td>
                    </tr>

                    <!-- Goal & Milestone Tracking -->
                    <tr>
                        <td>🎯 Goal & Milestone Tracking</td>
                        <td>Share milestones publicly or keep them private.</td>
                        <td>
                            <select class="form-select">
                                <option>Public</option>
                                <option>Private</option>
                            </select>
                        </td>
                    </tr>

                    <!-- Leaderboard Participation -->
                    <tr>
                        <td>🏅 Leaderboard Participation</td>
                        <td>Appear on competitive leaderboards.</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="leaderboardParticipation">
                                <label class="form-check-label" for="leaderboardParticipation">Disabled</label>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleSwitch(inputId) {
            let input = document.getElementById(inputId);
            let label = input.nextElementSibling;

            // Set initial state
            label.innerText = input.checked ? "Enabled" : "Disabled";

            // Update label on toggle
            input.addEventListener("change", function () {
                label.innerText = this.checked ? "Enabled" : "Disabled";
            });
        }

        toggleSwitch("learningStreak");
        toggleSwitch("aiRecommendations");
        toggleSwitch("leaderboardParticipation");
    });
</script>


            <!-- 3️⃣ Notifications -->
<div class="tab-pane fade" id="notifications">
    <div class="card">
        <h5 class="card-header">🔔 Notification Preferences</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Email Notifications -->
                    <tr>
                        <td>📩 Email Notifications</td>
                        <td>Receive email alerts for new courses, messages.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="emailNotifications" value="0">
                            <label id="emailLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Mobile Push Notifications -->
                    <tr>
                        <td>📲 Mobile Push Notifications</td>
                        <td>Enable app-based notifications.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="pushNotifications" value="0">
                            <label id="pushLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Course Updates Alerts -->
                    <tr>
                        <td>🔔 Course Updates Alerts</td>
                        <td>Notify when a course is updated or revised.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="courseUpdates" value="0">
                            <label id="courseUpdatesLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Mentor Communication -->
                    <tr>
                        <td>👨‍🏫 Mentor Communication</td>
                        <td>Allow mentors to contact via messages.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="mentorCommunication" value="0">
                            <label id="mentorCommunicationLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Forum & Peer Discussions -->
                    <tr>
                        <td>💬 Forum & Peer Discussions</td>
                        <td>Enable participation in discussion forums.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="forumDiscussions" value="0">
                            <label id="forumDiscussionsLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Event & Webinar Reminders -->
                    <tr>
                        <td>📅 Event & Webinar Reminders</td>
                        <td>Receive reminders for upcoming events.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="eventReminders" value="0">
                            <label id="eventRemindersLabel">OFF</label>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateLabel(inputId, labelId) {
            let input = document.getElementById(inputId);
            let label = document.getElementById(labelId);

            // Set initial state
            label.innerText = input.value == 0 ? "OFF" : "ON";

            // Update label on toggle
            input.addEventListener("input", function () {
                label.innerText = this.value == 0 ? "OFF" : "ON";
            });
        }

        updateLabel("emailNotifications", "emailLabel");
        updateLabel("pushNotifications", "pushLabel");
        updateLabel("courseUpdates", "courseUpdatesLabel");
        updateLabel("mentorCommunication", "mentorCommunicationLabel");
        updateLabel("forumDiscussions", "forumDiscussionsLabel");
        updateLabel("eventReminders", "eventRemindersLabel");
    });
</script>

			
	<!-- 5️⃣ Social & Collaborative Learning Preferences -->
<div class="tab-pane fade" id="social-learning">
    <div class="card">
        <h5 class="card-header">🤝 Social & Collaborative Learning Preferences</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Peer Learning Visibility -->
                    <tr>
                        <td>👥 Peer Learning Visibility</td>
                        <td>Allow peers to view & collaborate on projects.</td>
                        <td>
                            <select class="form-select" id="peerLearningVisibility">
                                <option>Public</option>
                                <option>Peers Only</option>
                                <option>Private</option>
                            </select>
                        </td>
                    </tr>

                    <!-- Group Study Sessions -->
                    <tr>
                        <td>🤝 Group Study Sessions</td>
                        <td>Allow invites for group learning sessions.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="groupStudy" value="0">
                            <label id="groupStudyLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Social Learning Integration -->
                    <tr>
                        <td>🔄 Social Learning Integration</td>
                        <td>Enable sharing progress on LinkedIn, Twitter.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="socialIntegration" value="0">
                            <label id="socialIntegrationLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Direct Messaging from Peers -->
                    <tr>
                        <td>💬 Direct Messaging from Peers</td>
                        <td>Allow direct messages from learners.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="directMessaging" value="0">
                            <label id="directMessagingLabel">OFF</label>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateLabel(inputId, labelId) {
            let input = document.getElementById(inputId);
            let label = document.getElementById(labelId);

            // Set initial state
            label.innerText = input.value == 0 ? "OFF" : "ON";

            // Update label when slider moves
            input.addEventListener("input", function () {
                label.innerText = this.value == 0 ? "OFF" : "ON";
            });
        }

        updateLabel("groupStudy", "groupStudyLabel");
        updateLabel("socialIntegration", "socialIntegrationLabel");
        updateLabel("directMessaging", "directMessagingLabel");
    });
</script>



           <!-- 4️⃣ Data & Analytics -->
<div class="tab-pane fade" id="data">
    <div class="card">
        <h5 class="card-header">📊 Data & Analytics Preferences</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Learning Analytics Tracking -->
                    <tr>
                        <td>📊 Learning Analytics Tracking</td>
                        <td>Allow platform to track learning patterns.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="learningAnalytics" value="0">
                            <label id="learningAnalyticsLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Time Spent on Platform -->
                    <tr>
                        <td>⏳ Time Spent on Platform</td>
                        <td>Track session durations for analytics.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="timeSpentTracking" value="0">
                            <label id="timeSpentTrackingLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- AI-Powered Skill Analysis -->
                    <tr>
                        <td>🎯 AI-Powered Skill Analysis</td>
                        <td>Allow AI to analyze skills & gaps.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="aiSkillAnalysis" value="0">
                            <label id="aiSkillAnalysisLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Personalized AI Insights -->
                    <tr>
                        <td>🔄 Personalized AI Insights</td>
                        <td>Enable AI-driven insights & suggestions.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="personalizedAI" value="0">
                            <label id="personalizedAILabel">OFF</label>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateLabel(inputId, labelId) {
            let input = document.getElementById(inputId);
            let label = document.getElementById(labelId);

            // Set initial state
            label.innerText = input.value == 0 ? "OFF" : "ON";

            // Update label when slider moves
            input.addEventListener("input", function () {
                label.innerText = this.value == 0 ? "OFF" : "ON";
            });
        }

        updateLabel("learningAnalytics", "learningAnalyticsLabel");
        updateLabel("timeSpentTracking", "timeSpentTrackingLabel");
        updateLabel("aiSkillAnalysis", "aiSkillAnalysisLabel");
        updateLabel("personalizedAI", "personalizedAILabel");
    });
</script>


           <!-- 5️⃣ Security & Account Protection -->
<div class="tab-pane fade" id="security">
    <div class="card">
        <h5 class="card-header">🔑 Security & Account Protection</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Two-Factor Authentication (2FA) -->
                    <tr>
                        <td>🔑 Two-Factor Authentication (2FA)</td>
                        <td>Add an extra layer of security.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="twoFactorAuth" value="0">
                            <label id="twoFactorAuthLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Account Activity Tracking -->
                    <tr>
                        <td>🛡 Account Activity Tracking</td>
                        <td>Receive alerts for suspicious logins.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="accountTracking" value="0">
                            <label id="accountTrackingLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Login from New Devices -->
                    <tr>
                        <td>📍 Login from New Devices</td>
                        <td>Require verification for new devices.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="newDeviceLogin" value="0">
                            <label id="newDeviceLoginLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Block Users -->
                    <tr>
                        <td>🚫 Block Users</td>
                        <td>Block specific users from messaging.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="blockUsers" value="0">
                            <label id="blockUsersLabel">OFF</label>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateLabel(inputId, labelId) {
            let input = document.getElementById(inputId);
            let label = document.getElementById(labelId);

            // Set initial state
            label.innerText = input.value == 0 ? "OFF" : "ON";

            // Update label on toggle
            input.addEventListener("input", function () {
                label.innerText = this.value == 0 ? "OFF" : "ON";
            });
        }

        updateLabel("twoFactorAuth", "twoFactorAuthLabel");
        updateLabel("accountTracking", "accountTrackingLabel");
        updateLabel("newDeviceLogin", "newDeviceLoginLabel");
        updateLabel("blockUsers", "blockUsersLabel");
    });
</script>


<!-- 7️⃣ Content & Accessibility Preferences -->
<div class="tab-pane fade" id="content-accessibility">
    <div class="card">
        <h5 class="card-header">📚 Content & Accessibility Preferences</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Description</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Audio & Video Preferences -->
                    <tr>
                        <td>🎧 Audio & Video Preferences</td>
                        <td>Enable subtitles, playback speed controls.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="audioVideoPref" value="0">
                            <label id="audioVideoPrefLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Preferred Course Language -->
                    <tr>
                        <td>🏷 Preferred Course Language</td>
                        <td>Select default course language.</td>
                        <td>
                            <select class="form-select" id="preferredLanguage">
                                <option>English</option>
                                <option>French</option>
                                <option>Spanish</option>
                                <option>German</option>
                                <option>Chinese</option>
                                <option>Hindi</option>
                            </select>
                        </td>
                    </tr>

                    <!-- Dark Mode -->
                    <tr>
                        <td>🌙 Dark Mode</td>
                        <td>Enable dark mode for better readability.</td>
                        <td>
                            <input type="range" class="form-range" min="0" max="1" step="1" id="darkMode" value="0">
                            <label id="darkModeLabel">OFF</label>
                        </td>
                    </tr>

                    <!-- Content Format Preference -->
                    <tr>
                        <td>📚 Content Format Preference</td>
                        <td>Prefer video, text, interactive learning.</td>
                        <td>
                            <select class="form-select" id="contentFormat">
                                <option>Video</option>
                                <option>Text</option>
                                <option>Interactive</option>
                            </select>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateLabel(inputId, labelId) {
            let input = document.getElementById(inputId);
            let label = document.getElementById(labelId);

            // Set initial state
            label.innerText = input.value == 0 ? "OFF" : "ON";

            // Update label on toggle
            input.addEventListener("input", function () {
                label.innerText = this.value == 0 ? "OFF" : "ON";
            });
        }

        updateLabel("audioVideoPref", "audioVideoPrefLabel");
        updateLabel("darkMode", "darkModeLabel");
    });
</script>




        </div> <!-- End of col-md-12 -->
    </div> <!-- End of row -->
</div> <!-- End of container -->

	






<?php 
require_once('../platformFooter.php');
?>
   