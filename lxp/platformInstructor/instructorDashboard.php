<?php
/**
 * Astraal LXP - Instructor Dashboard
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

$page = "dashboard";
require_once('instructorHead_Nav2.php');
?>




        <!-- Layout container -->
        <div class="layout-page">
		
		<?php require_once('instructorNav.php');   ?>
		
		
         

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
			  
                <div class="col-lg-8 mb-4 order-0">
  <div class="card">
    <div class="d-flex align-items-end row">
      <div class="col-sm-7">
        <div class="card-body">
          <h5 class="card-title text-primary">Welcome <?php echo $fname; ?>🎉</h5>
          <p class="mb-4">
            You have <span class="fw-bold">2 upcoming classes</span> and <span class="fw-bold">4 assignments to review</span>. 
            Ensure timely feedback for students.
          </p>
          <div class="d-flex gap-2">
            <a href="#class-schedule.php" class="btn btn-sm btn-outline-primary">View Schedule</a>
            <a href="#assignment-reviews.php" class="btn btn-sm btn-outline-secondary">Review Assignments</a>
          </div>
        </div>
      </div>
      <div class="col-sm-5 text-center text-sm-left">
        <div class="card-body pb-0 px-0 px-md-4">
          <img
            src="../assets/img/illustrations/man-with-laptop-light.png"
            height="140"
            alt="Faculty Dashboard"
            data-app-dark-img="illustrations/man-with-laptop-light.png"
            data-app-light-img="illustrations/man-with-laptop-light.png"
          />
        </div>
      </div>
    </div>
  </div>
</div>


				
                <div class="col-lg-4 col-md-4 order-1">
  <div class="row">
   
   <div class="col-lg-6 col-md-12 col-6 mb-4">
  <div class="card">
    <div class="card-body">
      <div class="card-title d-flex align-items-start justify-content-between">
        <div class="avatar flex-shrink-0">
          <i class="bx bx-chalkboard bx-lg text-primary"></i>
        </div>
        <div class="dropdown">
          <button
            class="btn p-0"
            type="button"
            id="classMgmtOpt"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="classMgmtOpt">
            <a class="dropdown-item" href="#class-schedule.php">View Classes</a>
            <a class="dropdown-item" href="#create-class.php">Create Class</a>
          </div>
        </div>
      </div>
      <span class="fw-semibold d-block mb-1">Classes</span>
      <h3 class="card-title mb-2">8 / week</h3>
      <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +10% this week</small>
    </div>
  </div>
</div>


    <!-- System Alerts Card -->
    <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-bell bx-lg text-danger"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="alertsOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsOpt">
                <a class="dropdown-item" href="#system-alerts.php">View Alerts</a>
                <a class="dropdown-item" href="#alert-settings.php">Manage Alerts</a>
              </div>
            </div>
          </div>
          <span>System Alerts</span>
          <h3 class="card-title text-nowrap mb-1">3 Critical</h3>
          <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> 4% this week</small>
        </div>
      </div>
    </div>
	
	
	
	
  </div>
</div>			
				
<!-- Faculty Workflow Metrics -->
<div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
  <div class="card">
    <div class="row row-bordered g-0">
      <div class="col-md-8">
        <h5 class="card-header m-0 me-2 pb-3">Instructor Metrics</h5>
        <div id="instructorMetricsChart" class="px-2"></div>
      </div>
      <div class="col-md-4">
        <div class="card-body">
          <div class="text-center">
            <div class="dropdown">
              <button
                class="btn btn-sm btn-outline-primary dropdown-toggle"
                type="button"
                id="instructorMetricsReportId"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                2024
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="instructorMetricsReportId">
                <a class="dropdown-item" href="javascript:void(0);">2023</a>
                <a class="dropdown-item" href="javascript:void(0);">2022</a>
                <a class="dropdown-item" href="javascript:void(0);">2021</a>
              </div>
            </div>
          </div>
        </div>
        <div id="facultyGrowthChart"></div>
        <div class="text-center fw-semibold pt-3 mb-2">75% Faculty Engagement</div>

       <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
          <div class="d-flex">
            <div class="me-2">
              <span class="badge bg-label-primary p-2"><i class="bx bx-user-check text-primary"></i></span>
            </div>
            <div class="d-flex flex-column">
              <small>Student Engagement</small>
              <h6 class="mb-0" id="studentEngagementCount"></h6>
            </div>
          </div>
          <div class="d-flex">
            <div class="me-2">
              <span class="badge bg-label-success p-2"><i class="bx bx-calendar-check text-success"></i></span>
            </div>
            <div class="d-flex flex-column">
              <small>Sessions Conducted</small>
              <h6 class="mb-0" id="totalSessionsCount"></h6>
            </div>
          </div>
          <div class="d-flex">
            <div class="me-2">
              <span class="badge bg-label-info p-2"><i class="bx bx-notepad text-info"></i></span>
            </div>
            <div class="d-flex flex-column">
              <small>Assignments Graded</small>
              <h6 class="mb-0" id="assignmentsGradedCount"></h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Faculty Workflow Metrics -->

<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script>
  // Example data for Student Engagement, Sessions Conducted, and Assignments Graded
  var optionsFaculty = {
    series: [
      {
        name: 'Student Engagement',
        data: [80, 85, 90, 95]
      },
      {
        name: 'Sessions Conducted',
        data: [20, 30, 45, 60]
      },
      {
        name: 'Assignments Graded',
        data: [150, 200, 250, 300]
      }
    ],
    chart: {
      type: 'line',
      height: 350
    },
    stroke: {
      curve: 'smooth'
    },
    xaxis: {
      categories: ['2021', '2022', '2023', '2024']
    }
  };

  var facultyChart = new ApexCharts(document.querySelector("#instructorMetricsChart"), optionsFaculty);
  facultyChart.render();
  
  document.getElementById('studentEngagementCount').innerText = facultyChart.w.config.series[0].data[facultyChart.w.config.series[0].data.length - 1];
  document.getElementById('totalSessionsCount').innerText = facultyChart.w.config.series[1].data[facultyChart.w.config.series[1].data.length - 1];
  document.getElementById('assignmentsGradedCount').innerText = facultyChart.w.config.series[2].data[facultyChart.w.config.series[2].data.length - 1];


var facultyGrowthOptions = {
  series: [{
    name: 'Faculty Performance',
    data: [80, 93] // Example data points
  }],
  chart: {
    type: 'bar', // You can choose the type of chart (line, bar, etc.)
    height: 150
  },
  xaxis: {
    categories: ['2023', '2024'] // Years
  },
  title: {
    text: 'Faculty Growth Metrics',
    align: 'center'
  },
  colors: ['#008FFB'] // Example color
};

var facultyGrowthChart = new ApexCharts(document.querySelector("#facultyGrowthChart"), facultyGrowthOptions);
facultyGrowthChart.render();
</script>

				
				
			<div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
  <div class="row">
    <!-- Class Performance Overview -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-line-chart text-primary"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="classPerformanceOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="classPerformanceOpt">
                <a class="dropdown-item" href="javascript:void(0);">View Detailed Analysis</a>
                <a class="dropdown-item" href="javascript:void(0);">Generate Report</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Class Performance</span>
          <h3 class="card-title text-nowrap mb-2" id="classPerformanceCount">76%</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +5%</small>
        </div>
      </div>
    </div>

    <!-- Assignment Submissions -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-folder text-warning"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="assignmentSubmissionsOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="assignmentSubmissionsOpt">
                <a class="dropdown-item" href="javascript:void(0);">Review Submissions</a>
                <a class="dropdown-item" href="javascript:void(0);">Send Reminders</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Assignments Submitted</span>
          <h3 class="card-title mb-2" id="assignmentsSubmittedCount">92</h3>
          <small class="text-warning fw-semibold"><i class="bx bx-down-arrow-alt"></i> -3%</small>
        </div>
      </div>
    </div>

    <!-- Attendance Tracking -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-calendar-check text-info"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="attendanceTrackingOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="attendanceTrackingOpt">
                <a class="dropdown-item" href="javascript:void(0);">Mark Attendance</a>
                <a class="dropdown-item" href="javascript:void(0);">View Reports</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Attendance Rate</span>
          <h3 class="card-title mb-2" id="attendanceRateCount">88%</h3>
          <small class="text-info fw-semibold"><i class="bx bx-up-arrow-alt"></i> +7%</small>
        </div>
      </div>
    </div>

    <!-- Student Feedback -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-chat text-success"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="studentFeedbackOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="studentFeedbackOpt">
                <a class="dropdown-item" href="javascript:void(0);">View Feedback</a>
                <a class="dropdown-item" href="javascript:void(0);">Export Insights</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Student Feedback</span>
          <h3 class="card-title mb-2" id="studentFeedbackCount">4.5/5</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +10%</small>
        </div>
      </div>
    </div>
  </div>
</div>




</div>
			  
			  
			  
			  
              <div class="row">
			  
<!-- Faculty Instructor Analytics -->
<div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">Faculty Analytics Dashboard</h5>
        <small class="text-muted">Comprehensive Insights</small>
      </div>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="facultyAnalytics"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="facultyAnalytics">
          <a class="dropdown-item" href="javascript:void(0);">View All</a>
          <a class="dropdown-item" href="javascript:void(0);">Refresh Data</a>
          <a class="dropdown-item" href="javascript:void(0);">Export Analytics</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Analytics Data -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column align-items-center gap-1">
          <h2 class="mb-2">8,29</h2>
          <span>Total Sessions Conducted</span>
        </div>
        <div id="facultyAnalyticsChart"></div>
        <script>
          var options = {
            chart: {
              type: 'line',
            },
            series: [{
              name: 'Sessions',
              data: [30, 45, 20, 50, 60, 40, 90]
            }],
            xaxis: {
              categories: ['Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec', 'Jan']
            }
          };
          var chart = new ApexCharts(document.querySelector("#facultyAnalyticsChart"), options);
          chart.render();
        </script>
      </div>

      <!-- Detailed Metrics -->
      <ul class="p-0 m-0">
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Student Engagement</h6>
              <small class="text-muted">Participation Rate</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">78%</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-task"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Grading & Feedback</h6>
              <small class="text-muted">Pending: 5</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">10 Graded</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-award"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Student Achievements</h6>
              <small class="text-muted">Recognized: 15</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">92% Verified</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-chat"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Discussions & Forums</h6>
              <small class="text-muted">Active Threads: 120</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">+10% Growth</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-user-plus"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">New Enrollments</h6>
              <small class="text-muted">Students Added: 25</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">+18% This Month</small>
            </div>
          </div>
        </li>

        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-support"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Support Requests</h6>
              <small class="text-muted">Open: 3</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">8 Resolved</small>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Faculty Instructor Analytics -->
	
	
	
	
	
	
	
	

<!-- Instructor Activities -->
<div class="col-md-6 col-lg-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title m-0 me-2">Your Current Activities</h5>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="activityMenu"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="activityMenu">
          <a class="dropdown-item" href="javascript:void(0);">Last 8 Days</a>
          <a class="dropdown-item" href="javascript:void(0);">Last Week</a>
          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <ul class="p-0 m-0">
        <!-- Example Instructor Activities -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bxs-graduation"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Course Facilitation</h6>
              <small class="text-muted d-block mb-1">Active Sessions</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">5</h6>
              <span class="text-muted">Ongoing</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-edit"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Content Personalization</h6>
              <small class="text-muted d-block mb-1">Customized Learning Paths</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">8</h6>
              <span class="text-muted">Edits</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-task"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Assignment Evaluations</h6>
              <small class="text-muted d-block mb-1">Student Submissions</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">27</h6>
              <span class="text-muted">Completed</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-task"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Certificates Issued</h6>
              <small class="text-muted d-block mb-1">Student Achievements</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">12</h6>
              <span class="text-muted">Courses</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-folder"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Resource Management</h6>
              <small class="text-muted d-block mb-1">Learning Materials Updated</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">15</h6>
              <span class="text-muted">Files </span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-message-rounded"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Forum Moderation</h6>
              <small class="text-muted d-block mb-1">Community Discussions</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">20</h6>
              <span class="text-muted">Threads</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-pie-chart"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Analytics Review</h6>
              <small class="text-muted d-block mb-1">Student Engagement</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">4</h6>
              <span class="text-muted">Reports</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-cube"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Tool Integration</h6>
              <small class="text-muted d-block mb-1">Third-Party Systems</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">3</h6>
              <span class="text-muted">Active Tools</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>



               
  
<!-- Instructor ToDo & Calendar Insights -->
<div class="col-md-6 col-lg-4 col-xl-4 order-2 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">ToDo & General Stats</h5>
        <p><br></p>
      </div>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="instructorTodoMenu"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="instructorTodoMenu">
          <a class="dropdown-item" href="javascript:void(0);">Mark All as Done</a>
          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          <a class="dropdown-item" href="javascript:void(0);">Export Calendar</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Instructor's ToDo -->
      <ul class="p-0 m-0">
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-check"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Review Submitted Assignments</h6>
              <small class="text-muted">Pending evaluations</small>
            </div>
            <span class="badge bg-label-info">5 Pending</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-line-chart"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Track Learner Progress</h6>
              <small class="text-muted">Monitor course engagement</small>
            </div>
            <span class="badge bg-label-success">8 Learners</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-calendar"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Plan Upcoming Sessions</h6>
              <small class="text-muted">Schedule and prepare materials</small>
            </div>
            <span class="badge bg-label-warning">3 Sessions</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-task"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Publish New Content</h6>
              <small class="text-muted">Update course modules</small>
            </div>
            <span class="badge bg-label-danger">2 Pending</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-group"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Respond to Learner Queries</h6>
              <small class="text-muted">Pending messages and tickets</small>
            </div>
            <span class="badge bg-label-secondary">4 Unread</span>
          </div>
        </li>
        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-camera"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Host Live Webinars</h6>
              <small class="text-muted">Ensure readiness for live sessions</small>
            </div>
            <span class="badge bg-label-info">1 Scheduled</span>
          </div>
        </li>
      </ul>

      <!-- Activity & Stats -->
      <h6 class="text-uppercase mt-4 mb-3">Platform Insights</h6>
      <ul class="p-0 m-0">
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-bar-chart"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Engagement Stats</h6>
              <small class="text-muted">Learners active this week</small>
            </div>
            <h6 class="mb-0">23 Learners</h6>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-upload"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Content Contributions</h6>
              <small class="text-muted">Resources added this month</small>
            </div>
            <h6 class="mb-0">7 Items</h6>
          </div>
        </li>
        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-time"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Average Response Time</h6>
              <small class="text-muted">For learner queries</small>
            </div>
            <h6 class="mb-0">2 Hours</h6>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Instructor ToDo & Insights End -->










              </div>
            </div>
            <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   