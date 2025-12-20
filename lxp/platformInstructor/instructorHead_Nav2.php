<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-../assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

   <title>Phx Innovates | EdTech, Campus to Corporate Learning & Skilling Solutions</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="http://localhost/raunakeducares.com/img/eAfac.png">
    <link rel="apple-touch-icon" sizes="180x180" href="http://localhost/raunakeducares.com/img/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
	
	
	<style>
  .menu-item a {
    font-size: 10px; /* Adjust font size to make it smaller */
    white-space: nowrap; /* Prevent text from wrapping */
    overflow: hidden; /* Hide overflow if necessary */
    text-overflow: ellipsis; /* Add ellipsis for long text */
  }

  .menu-icon {
	  
    font-size: 20px; /* Reduce icon size for a cleaner look */
	margin-right: 3px; /* Reduce spacing between icon and text */
    vertical-align: middle; /* Align icons properly with text */
  }

  .menu-link div {
    display: inline-block; /* Ensure text and icon stay in the same line */
    line-height: 1.2; /* Adjust line height for compact appearance */
  }

  .menu-sub li a {
    font-size: 9px; /* Smaller font size for submenu items */
    padding: 2px 2px; /* Adjust padding for compact design */
  }

  ul.menu-sub {
    margin-left: 2px; /* Slight indentation for submenu */
  }
</style>



<!-- Optional: Custom Styling for Icons and Layout -->
<style>
  /* General Style for Navigation Pills */
  .nav-pills .nav-link {
    border-radius: 10px;
    font-weight: 600;
    background-color: #f0f8ff; /* Light background color */
    color: #6c757d; /* Soft text color */
  }

  /* Active State for Nav Pills */
  .nav-pills .nav-link.active {
    background-color: #5bc0de; /* Light blue background */
    color: #fff; /* White text for active state */
    border-color: #5bc0de; /* Matching border */
  }

  /* Hover State for Nav Pills */
  .nav-pills .nav-link:hover {
    background-color: #6cb8d4; /* Slightly darker blue on hover */
    color: #fff; /* White text on hover */
  }

  /* Style for Tab Content */
  .tab-content {
    border-top: 2px solid #5bc0de; /* Light border color */
    padding-top: 20px;
    background-color: #fafafa; /* Very light gray background */
  }

  /* Card Title Customization */
  .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057; /* Darker text for the titles */
  }

  /* Padding for Each Tab Pane */
  .tab-pane {
    padding: 20px;
  }

  /* Icon Size and Spacing */
  .nav-link i {
    font-size: 20px; /* Slightly larger icons */
    margin-right: 10px; /* Space between icon and text */
    color: #6c757d; /* Icon color matching the text */
  }

  /* Change icon color on active state */
  .nav-pills .nav-link.active i {
    color: #fff; /* White icon when active */
  }
</style>





  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
       
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="#" class="app-brand-link">
       <img src="../assets/img/phxlogo1.png" height="50%" alt="Phx Logo" class="img-fluid">
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
<div><p><br></p></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- 1. Dashboard -->
    <li class="menu-item <?php echo ($page == 'dashboard') ? 'active open' : ''; ?>">
	  <a href="instructorDashboard.php" class="menu-link">
		<i class="menu-icon bx bx-grid-alt"></i>
		<div>Dashboard</div>
	  </a>
	</li>

<!-- 2. Manage Course Content Resources -->
    
		
        <li class="menu-item <?php echo ($page == 'createContent') ? 'active open' : ''; ?>" >
          <a href="create-content.php" class="menu-link">
            <i class="menu-icon bx bx-plus-circle"></i>
            <div>Course Content</div>
          </a>
        </li>
		
		<!-- 3. Assessments & Certifications -->
   
        <li class="menu-item <?php echo ($page == 'quizTest') ? 'active open' : ''; ?>">
          <a href="quizzes-tests.php" class="menu-link">
            <i class="menu-icon bx bx-task"></i>
            <div>Quizzes & Tests</div>
          </a>
        </li>
        <li class="menu-item <?php echo ($page == 'manageSkills') ? 'active open' : ''; ?>" >
          <a href="skills-tools.php" class="menu-link">
            <i class="menu-icon bx bx-poll"></i>
            <div>Skills Assessment</div>
          </a>
        </li>
        <li class="menu-item <?php echo ($page == 'badgesCertificate') ? 'active open' : ''; ?>" >
          <a href="certification-management.php" class="menu-link">
            <i class="menu-icon bx bxs-certification"></i>
            <div>Badges & Certificates</div>
          </a>
        </li>
		
        <li class="menu-item <?php echo ($page == 'courseLibrary') ? 'active open' : ''; ?>">
          <a href="course-library.php" class="menu-link">
            <i class="menu-icon bx bx-library"></i>
            <div>Course Library</div>
          </a>
        </li>
        
        <li class="menu-item <?php echo ($page == 'bulkUpload') ? 'active open' : ''; ?>">
          <a href="bulk-upload.php" class="menu-link">
            <i class="menu-icon bx bx-upload"></i>
            <div>Bulk Upload</div>
          </a>
        </li>
        
		<li class="menu-item <?php echo ($page == 'contentRepository') ? 'active open' : ''; ?>">
          <a href="content-repository.php" class="menu-link">
            <i class="menu-icon bx bx-folder"></i>
            <div>Content Repository</div>
          </a>
        </li>
        <li class="menu-item <?php echo ($page == 'aiContent') ? 'active open' : ''; ?>">
          <a href="ai-recommendations.php" class="menu-link">
            <i class="menu-icon bx bx-brain"></i>
            <div>AI-Powered Content Recommendations</div>
          </a>
        </li>
        <li class="menu-item <?php echo ($page == 'contentApprovals') ? 'active open' : ''; ?>">
          <a href="content-approvals.php" class="menu-link">
            <i class="menu-icon bx bx-check-circle"></i>
            <div>Content Approvals</div>
          </a>
        </li>
		<li class="menu-item <?php echo ($page == 'versionControl') ? 'active open' : ''; ?>">
          <a href="version-control.php" class="menu-link">
            <i class="menu-icon bx bx-git-branch"></i>
            <div>Version Control</div>
          </a>
        </li>
		
	
      
	
	
	
  </ul>
  
  
  
  <!-- Understand Your Navigation -->
<li class="menu-item text-center <?php echo ($page == 'navOverview') ? 'active open' : ''; ?>"
    style="<?php echo ($page == 'navOverview') ? 'font-size: 2rem; color: #ff0000;' : ''; ?>">
  <a href="navOverview.php" 
     class="menu-link d-flex flex-column align-items-center justify-content-center" 
     title="Understand Your Navigation">
     
    <img src="../assets/img/sidebar.png" width="32px" height="32px" alt="Understand Your Navigation" class="mb-1">
   
  </a>
</li>



   
</aside>
