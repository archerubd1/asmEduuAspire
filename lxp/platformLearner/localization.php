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
<div class="card-body">
<h4 class="mb-4">Localization Settings</h4>
        
   <form>
    <!-- Language & Content Translation -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                <i class='bx bx-globe' style="color: #007bff;"></i> Preferred Language
            </label>
            <select class="form-select">
                <option value="en">English</option>
                <option value="hi">Hindi</option>
                <option value="mr">Marathi</option>
                <option value="ta">Tamil</option>
                <option value="bn">Bengali</option>
                <option value="kn">Kannada</option>
                <option value="te">Telugu</option>
            </select>
            <button type="button" class="btn btn-sm btn-light" onclick="showInfo('Multi-Language Support', 'You can select your preferred language for the platform interface and content.')">
                <i class='bx bx-info-circle' style="color: #17a2b8;"></i>
            </button>
        </div>

        <!-- Adaptive UI & UX -->
        <div class="col-md-6">
            <label class="form-label">
                <i class='bx bx-calendar' style="color: #28a745;"></i> Date & Time Format
            </label>
            <select class="form-select">
                <option value="dd-mm-yyyy">DD-MM-YYYY</option>
                <option value="mm-dd-yyyy">MM-DD-YYYY</option>
            </select>
            <button type="button" class="btn btn-sm btn-light" onclick="showInfo('Localized Date, Time, & Currency', 'Select your preferred date and currency format based on your region.')">
                <i class='bx bx-info-circle' style="color: #17a2b8;"></i>
            </button>
        </div>
    </div>
 <div class="dropdown-divider"></div>
    <!-- Theming & Branding -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                <i class='bx bx-brush' style="color: #ffc107;"></i> Select Theme
            </label>
            <select class="form-select">
                <option value="light">Light Theme</option>
                <option value="dark">Dark Theme</option>
                <option value="regional">Regional Theme</option>
            </select>
            <button type="button" class="btn btn-sm btn-light" onclick="showInfo('Regional Theming', 'Adjust UI colors and layout based on your region.')">
                <i class='bx bx-info-circle' style="color: #17a2b8;"></i>
            </button>
        </div>

        <!-- Video & Media Localization -->
        <div class="col-md-6">
            <label class="form-label">
                <i class='bx bx-subtitles' style="color: #dc3545;"></i> Enable Subtitles
            </label>
            <select class="form-select">
                <option value="auto">Auto-Generated</option>
                <option value="manual">Manually Uploaded</option>
            </select>
            <button type="button" class="btn btn-sm btn-light" onclick="showInfo('Subtitles & Captions', 'Choose between auto-generated or manually uploaded subtitles.')">
                <i class='bx bx-info-circle' style="color: #17a2b8;"></i>
            </button>
        </div>
    </div>
 <div class="dropdown-divider"></div>
    <!-- AI-Powered Features & Accessibility -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label">
                <i class='bx bx-bot' style="color: #6610f2;"></i> Enable AI Chatbot
            </label>
            <input type="checkbox" class="form-check-input">
        </div>

        <div class="col-md-3">
            <label class="form-label">
                <i class='bx bx-accessibility' style="color: #20c997;"></i> Enable Screen Reader Mode
            </label>
            <input type="checkbox" class="form-check-input">
        </div>

        <div class="col-md-3">
            <label class="form-label">
                <i class='bx bx-world' style="color: #fd7e14;"></i> Allow Community Translations
            </label>
            <input type="checkbox" class="form-check-input">
        </div>

        <div class="col-md-3">
            <label class="form-label">
                <i class='bx bx-bookmark' style="color: #007bff;"></i> Enable Region-Specific Courses
            </label>
            <input type="checkbox" class="form-check-input">
        </div>

       
    </div>
 <div class="dropdown-divider"></div>
    <!-- Community & Theming -->
    <div class="row mb-3">
        <h4>Community & Theming</h4>
        <div class="col-md-6">
            <input type="checkbox" id="community_translations" name="community_translations">
            <label for="community_translations">
                <i class='bx bx-group' style="color: #ff5733;"></i> Allow Community Translations
            </label>
        </div>
        <div class="col-md-6">
            <input type="checkbox" id="theming" name="theming">
            <label for="theming">
                <i class='bx bx-palette' style="color: #ff9800;"></i> Enable Region-Specific Themes
            </label>
        </div>
    </div>
 <div class="dropdown-divider"></div>
    <!-- Personalized Learning -->
    <div class="row mb-3">
        <h4>Personalized Learning Paths</h4>
        <div class="col-md-6">
            <input type="checkbox" id="regional_courses" name="regional_courses">
            <label for="regional_courses">
                <i class='bx bx-map' style="color: #28a745;"></i> Show Region-Specific Courses
            </label>
        </div>
        <div class="col-md-6">
            <input type="checkbox" id="certifications" name="certifications">
            <label for="certifications">
                <i class='bx bx-certification' style="color: #17a2b8;"></i> Enable Regional Certifications
            </label>
        </div>
    </div>
 <div class="dropdown-divider"></div>
    <!-- Mobile & Offline Learning -->
    <div class="row mb-3">
        <h4>Mobile & Offline Learning</h4>
		 <div class="col-md-4">
		 <input type="checkbox" id="offline_learning" name="offline_learning">
            <label for="offline_learning">
                <i class='bx bx-download' style="color: #28a745;"></i> Enable Offline Learning
            </label>
            
        </div>
        <div class="col-md-4">
            <input type="checkbox" id="offline_language" name="offline_language">
            <label for="offline_language">
                <i class='bx bx-wifi-off' style="color: #dc3545;"></i> Enable Offline Language Support
            </label>
        </div>
        <div class="col-md-4">
            <input type="checkbox" id="push_notifications" name="push_notifications">
            <label for="push_notifications">
                <i class='bx bx-bell' style="color: #ffc107;"></i> Receive Notifications in Preferred Language
            </label>
        </div>
    </div>
 <div class="dropdown-divider"></div>
    <button type="submit" class="btn btn-primary mt-4">Save Localization Preferences</button>
</form>



<p><br><br>
</div>
</div>
</div>
 <!-- / Content -->

<script>
        function showInfo(title, message) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'info',
                confirmButtonText: 'Got it!'
            });
        }
    </script>



<?php 
require_once('../platformFooter.php');
?>
