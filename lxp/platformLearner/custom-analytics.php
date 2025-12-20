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
    <?php require_once('learnersNav.php'); ?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
          <div class="card">     
    
	
	
	<div class="container mt-5">
    <h3 class="text-primary mb-4"><i class='bx bx-cog bx-spin'></i> Custom Analytics Settings</h3>

    <div class="row">
        <!-- Data Tracking -->
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <i class='bx bx-bar-chart-alt-2'></i> Data Tracking
                </div>
                <div class="card-body">
                    <p>Enable or disable tracking of learning progress.</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="trackProgress" checked>
                        <label class="form-check-label" for="trackProgress">Enable Progress Tracking</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Insights -->
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <i class='bx bx-line-chart'></i> Performance Insights
                </div>
                <div class="card-body">
                    <p>Choose the level of detail for performance reports.</p>
                    <select class="form-select" id="reportDetail">
                        <option value="basic">Basic Summary</option>
                        <option value="detailed" selected>Detailed Report</option>
                        <option value="custom">Custom View</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <i class='bx bx-bell'></i> Notifications
                </div>
                <div class="card-body">
                    <p>Receive updates on analytics reports via email.</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                        <label class="form-check-label" for="emailNotifications">Enable Email Alerts</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Privacy -->
        <div class="col-md-4 mt-3">
            <div class="card shadow-lg">
                <div class="card-header bg-danger text-white">
                    <i class='bx bx-shield'></i> Data Privacy
                </div>
                <div class="card-body">
                    <p>Control how your learning data is stored and shared.</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="dataPrivacy" checked>
                        <label class="form-check-label" for="dataPrivacy">Enable Data Protection</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Frequency -->
        <div class="col-md-4 mt-3">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white">
                    <i class='bx bx-time'></i> Report Frequency
                </div>
                <div class="card-body">
                    <p>Select how often you receive analytics reports.</p>
                    <select class="form-select" id="reportFrequency">
                        <option value="daily">Daily</option>
                        <option value="weekly" selected>Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Learning Recommendations -->
        <div class="col-md-4 mt-3">
            <div class="card shadow-lg">
                <div class="card-header bg-secondary text-white">
                    <i class='bx bx-book-open'></i> Learning Recommendations
                </div>
                <div class="card-body">
                    <p>Get AI-powered recommendations based on your learning history.</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="aiRecommendations" checked>
                        <label class="form-check-label" for="aiRecommendations">Enable AI Suggestions</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="text-center mt-4">
        <button class="btn btn-primary" onclick="saveSettings()"><i class='bx bx-save'></i> Save Settings</button>
    </div>
</div>

<script>
    function saveSettings() {
        const tracking = document.getElementById('trackProgress').checked;
        const reportType = document.getElementById('reportDetail').value;
        const notifications = document.getElementById('emailNotifications').checked;
        const privacy = document.getElementById('dataPrivacy').checked;
        const frequency = document.getElementById('reportFrequency').value;
        const recommendations = document.getElementById('aiRecommendations').checked;

        alert(`Settings Saved:\n- Tracking: ${tracking ? 'Enabled' : 'Disabled'}\n- Report Type: ${reportType}\n- Email Notifications: ${notifications ? 'Enabled' : 'Disabled'}\n- Data Privacy: ${privacy ? 'Enabled' : 'Disabled'}\n- Report Frequency: ${frequency}\n- AI Recommendations: ${recommendations ? 'Enabled' : 'Disabled'}`);
    }
</script>
	
	
	
	
	
	
	
	
	
	
	
	
<p><br><br>
</div>










                <?php require_once('../platformFooter.php'); ?>
           
   