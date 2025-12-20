<?php
/**
 *  Astraal LXP – Learner Dashboard
 * PHP 5.4 Safe / UwAmp & GoDaddy Compatible
 */

require_once('../../config.php');

// -----------------------------------------------------------------------------
// 1️⃣ Secure Session Validation
// -----------------------------------------------------------------------------
if (session_id() == '') session_start();

// Validate login session keys set by verifyLogin.php
if (
    !isset($_SESSION['phx_logged_in']) ||
    $_SESSION['phx_logged_in'] !== true ||
    !isset($_SESSION['phx_user_login']) ||
    !isset($_SESSION['phx_user_type'])
) {
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session expired. Please log in again.")));
    exit;
}

// -----------------------------------------------------------------------------
// 2️⃣ Idle Timeout (60 minutes)
// -----------------------------------------------------------------------------
$timeout_duration = 60 * 60; // 60 minutes
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../../phxlogin.php?error=" . urlencode(base64_encode("Session timed out. Please log in again.")));
    exit;
} else {
    $_SESSION['login_time'] = time(); // refresh activity timestamp
}

// -----------------------------------------------------------------------------
// 3️⃣ Retrieve User Info from Database
// -----------------------------------------------------------------------------
$user_login = mysqli_real_escape_string($coni, $_SESSION['phx_user_login']);
$user_type  = $_SESSION['phx_user_type'];
$page       = "dashboard";

require_once('learnerHead_Nav2.php');

$getfname = "SELECT name, surname FROM users WHERE login = '$user_login' LIMIT 1";
$result   = mysqli_query($coni, $getfname);
$get      = mysqli_fetch_array($result);
$_SESSION['fname'] = isset($get['name']) ? $get['name'] : 'Learner';
?>


        <!-- Layout container -->
        <div class="layout-page">
          
		  
		<?php require_once('learnersNav.php');   ?>

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
          <h5 class="card-title text-primary">Welcome <?php echo $get['name']; ?>! 🎉</h5>
          <p class="mb-4">
            You have <span class="fw-bold">3 new courses</span> and <span class="fw-bold">2 new personalized insights</span> ready. Explore them to accelerate your learning journey.
          </p>
          <div class="d-flex gap-2">
            <a href="#active-courses.php" class="btn btn-sm btn-outline-primary">View Courses</a>
            <a href="personalized-insights.php" class="btn btn-sm btn-outline-secondary">Check Insights</a>
          </div>
        </div>
      </div>
      <div class="col-sm-5 text-center text-sm-left">
        <div class="card-body pb-0 px-0 px-md-4">
          <img
            src="../assets/img/illustrations/man-with-laptop-light.png"
            height="140"
            alt="PlatformLearner Dashboard"
            data-app-dark-img="illustrations/man-with-laptop-dark.png"
            data-app-light-img="illustrations/man-with-laptop-light.png"
          />
        </div>
      </div>
    </div>
  </div>
</div>

	<div class="col-lg-4 col-md-4 order-1">
  <div class="row">
    
	<!-- Leaderboard Stats Card -->
<div class="col-lg-6 col-md-12 col-6 mb-4">
  <div class="card">
    <div class="card-body">
      <div class="card-title d-flex align-items-start justify-content-between">
        <div class="avatar flex-shrink-0">
          <i class="bx bx-trophy bx-lg text-warning"></i>
        </div>
        <div class="dropdown">
          <button
            class="btn p-0"
            type="button"
            id="leaderboardStatsOpt"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="leaderboardStatsOpt">
            <a class="dropdown-item" href="#leaderboard.php">View Leaderboard</a>
            <a class="dropdown-item" href="#profile.php">View Profile</a>
          </div>
        </div>
      </div>
      <span class="fw-semibold d-block mb-1">Leaderboard</span>
      <h3 class="card-title mb-2">#5 Rank</h3>
      <small class="text-success fw-semibold">
        <i class="bx bx-up-arrow-alt"></i> Up by +2 positions
      </small>
    </div>
  </div>
</div>

    <!-- Insights & Recommendations Card -->
    <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-bar-chart-alt bx-lg text-success"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="insightsOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="insightsOpt">
                <a class="dropdown-item" href="personalized-insights.php">View Insights</a>
                <a class="dropdown-item" href="#recommendation-settings.php">Manage Preferences</a>
              </div>
            </div>
          </div>
          <span>Personalization </span>
          <h3 class="card-title text-nowrap mb-1">3 Insights</h3>
          <small class="text-warning fw-semibold"><i class="bx bx-info-circle"></i> Updated today</small>
        </div>
      </div>
    </div>
  </div>
</div>
	
<!-- Individual PlatformLearner's Learning Analytics -->
<div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
  <div class="card">
    <div class="row row-bordered g-0">
      <!-- Learner Metrics Chart -->
      <div class="col-md-8">
        <h5 class="card-header m-0 me-2 pb-3">Your Learning Analytics</h5>
        <div id="learnerAnalyticsChart" class="px-2"></div>
      </div>
      <!-- Individual Learner Overview -->
      <div class="col-md-4">
        <div class="card-body text-center">
          <!-- Personalization Dropdown -->
          <div class="dropdown mb-3">
            <button
              class="btn btn-sm btn-outline-primary dropdown-toggle"
              type="button"
              id="analyticsFilterSelector"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              Filter by Month: January 2025
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="analyticsFilterSelector">
              <a class="dropdown-item" href="javascript:void(0);">December 2024</a>
              <a class="dropdown-item" href="javascript:void(0);">November 2024</a>
              <a class="dropdown-item" href="javascript:void(0);">October 2024</a>
            </div>
          </div>
          <div id="personalOverviewChart"></div>
         

          
        </div>
      </div>
      <!-- End of Individual Learner Overview -->
    </div>
  </div>
</div>
<!--/ Individual PlatformLearner's Learning Analytics -->

<!-- Vendors JS -->
<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

<script>
  // Example data for Individual Learner Analytics
  var options = {
    series: [
      {
        name: 'Completed Courses',
        data: [1, 2, 3]
      },
      {
        name: 'Active Courses',
        data: [2, 1, 2]
      },
      {
        name: 'Learning Hours',
        data: [10, 15, 28]
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
      categories: ['November 2024', 'December 2024', 'January 2025']
    }
  };

  var chart = new ApexCharts(document.querySelector("#learnerAnalyticsChart"), options);
  chart.render();

  // Update Metrics Summary
  document.getElementById('completedCoursesCount').innerText = chart.w.config.series[0].data.at(-1); // Last value
  document.getElementById('activeCoursesCount').innerText = chart.w.config.series[1].data.at(-1);
  document.getElementById('personalLearningHours').innerText = chart.w.config.series[2].data.at(-1);
</script>

<!-- Vendors JS -->
<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

<script>
  // Data for Individual Learner's Progress
  var personalOptions = {
    series: [
      {
        name: 'Completed Courses',
        data: [2, 4, 6, 8] // Example data for completed courses over time
      },
      {
        name: 'Completed Quizzes',
        data: [10, 20, 30, 40] // Example data for completed quizzes over time
      },
      {
        name: 'Submitted Assignments',
        data: [5, 10, 15, 20] // Example data for submitted assignments over time
      },
      {
        name: 'Learning Paths Progressed',
        data: [1, 2, 3, 4] // Example data for learning paths over time
      }
    ],
    chart: {
      type: 'bar',
      height: 350,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%',
        endingShape: 'rounded'
      }
    },
    xaxis: {
      categories: ['Q1', 'Q2', 'Q3', 'Q4'], // Example time periods
      title: {
        text: 'Time Period'
      }
    },
    yaxis: {
      title: {
        text: 'Metrics'
      }
    },
    colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560'], // Colors for each bar
    legend: {
      position: 'top',
      horizontalAlign: 'right'
    },
    dataLabels: {
      enabled: false
    },
    grid: {
      borderColor: '#f1f1f1'
    }
  };

  var personalChart = new ApexCharts(document.querySelector("#personalOverviewChart"), personalOptions);
  personalChart.render();
</script>

				
				
			<div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
  <div class="row">
    <!-- Behavioral Insights -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-bar-chart-alt-2 text-success"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="behavioralInsightsOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="behavioralInsightsOpt">
                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                <a class="dropdown-item" href="javascript:void(0);">Generate Report</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Engagements</span>
          <h3 class="card-title text-nowrap mb-2" id="behavioralInsightsCount">139 Points</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Up by +12% </small>
        </div>
      </div>
    </div>

    <!-- Adaptive Learning Paths -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
             <i class="bx bx-lg bx-dots-horizontal-rounded text-primary"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="adaptiveLearningPathsOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="adaptiveLearningPathsOpt">
                <a class="dropdown-item" href="javascript:void(0);">Customize Paths</a>
                <a class="dropdown-item" href="javascript:void(0);">View Progress</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Learning Paths</span>
          <h3 class="card-title mb-2" id="adaptivePathsCount">4 Paths</h3>
          <small class="text-info fw-semibold"><i class="bx bx-up-arrow-alt"></i>Up by +8%</small>
        </div>
      </div>
    </div>




<!-- Peer Review Stats -->
<div class="col-6 mb-4">
  <div class="card">
    <div class="card-body">
      <div class="card-title d-flex align-items-start justify-content-between">
        <div class="avatar flex-shrink-0">
          <i class="bx bx-lg bx-comment-detail text-primary"></i>
        </div>
        <div class="dropdown">
          <button
            class="btn p-0"
            type="button"
            id="peerReviewOpt"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu" aria-labelledby="peerReviewOpt">
            <a class="dropdown-item" href="#peer-reviews.php">View Reviews</a>
            <a class="dropdown-item" href="#review-summary.php">Summary Report</a>
          </div>
        </div>
      </div>
      <span class="fw-semibold d-block mb-1">Peer Reviews</span>
      <h3 class="card-title mb-2" id="peerReviewsCount">15</h3>
      <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +20% this week</small>
    </div>
  </div>
</div>



    

    <!-- Incentive Management -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-trophy text-warning"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="incentiveManagementOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="incentiveManagementOpt">
                <a class="dropdown-item" href="javascript:void(0);">Manage Incentives</a>
                <a class="dropdown-item" href="javascript:void(0);">Generate Reports</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Incentives</span>
          <h3 class="card-title mb-2" id="incentivesCount">0</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +10%</small>
        </div>
      </div>
    </div>
  </div>
</div>

				
				
				
              </div>
              <div class="row">
			  
			  
              <!-- learners Analytics Sync -->
<div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">Learners Platform Analytics </h5>
        <small class="text-muted">Comprehensive Overview</small>
      </div>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="platformAnalytics"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="platformAnalytics">
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
          <h2 class="mb-2">12,34</h2>
          <span>Total Interactions</span>
        </div>
        <div id="platformAnalyticsChart"></div>
        <script>
          var options = {
            chart: {
              type: 'line',
            },
            series: [{
              name: 'Interactions',
              data: [50, 75, 30, 60, 80, 35, 120]
            }],
            xaxis: {
              categories: ['Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec', 'Jan']
            }
          };
          var chart = new ApexCharts(document.querySelector("#platformAnalyticsChart"), options);
          chart.render();
        </script>
      </div>

      <!-- Detailed Metrics -->
      <ul class="p-0 m-0">
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-book"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Courses & Learning Paths</h6>
              <small class="text-muted">Completion</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">45% </small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-task"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Assignments & Quizzes</h6>
              <small class="text-muted">Pending: 2</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">3 Completed</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-award"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Certificates & Badges</h6>
              <small class="text-muted">Awarded: 9</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">93% Validated</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-chat"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Forums & Discussions</h6>
              <small class="text-muted">Posts: 457</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">+8% Growth</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-cube-alt"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Metaverse Spaces</h6>
              <small class="text-muted">Active Rooms: 6</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">+12% Engagement</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-brain"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">AI Recommendations</h6>
              <small class="text-muted">Suggestions: 18</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">89% Accuracy</small>
            </div>
          </div>
        </li>

        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-message-dots"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Behavioral Insights</h6>
              <small class="text-muted">Feedback Received: 3</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">+15% Engagement</small>
            </div>
          </div>
        </li>

        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-support"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Support Tickets</h6>
              <small class="text-muted">Open: 2</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">12 Resolved</small>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- learners Analytics Sync -->




<!-- lxp Activities -->
<div class="col-md-6 col-lg-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title m-0 me-2">Your Current Activities</h5>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="transactionID"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
          <a class="dropdown-item" href="javascript:void(0);">Last 8 Days</a>
          <a class="dropdown-item" href="javascript:void(0);">Last Week</a>
          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <ul class="p-0 m-0">
        <!-- Example Transactions -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bxs-graduation"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              
              <h6 class="mb-0">Course Completion</h6>
			  <small class="text-muted d-block mb-1">Skills Development</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">80%</h6>
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
             
              <h6 class="mb-0">Personalization Attempts</h6>
			   <small class="text-muted d-block mb-1">Skills Development</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">2</h6>
              <span class="text-muted">Paths</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bxs-file"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              
              <h6 class="mb-0">Assignment Submissions</h6>
			  <small class="text-muted d-block mb-1">3</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">63</h6>
              <span class="text-muted">% Avg</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bxs-badge-check"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              
              <h6 class="mb-0">Certificates Earned</h6>
			  <small class="text-muted d-block mb-1">on EduuAspire</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">0</h6>
              <span class="text-muted">Courses</span>
            </div>
          </div>
        </li>
		
		
<li class="d-flex mb-4 pb-1">
  <div class="avatar flex-shrink-0 me-3">
    <span class="avatar-initial rounded bg-label-primary"><i class="bx bxs-folder"></i></span>
  </div>
  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
    <div class="me-2">
      <h6 class="mb-0">Content Repository</h6>
      <small class="text-muted d-block mb-1">Interactive Resources</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">6</h6>
      <span class="text-muted">Files Access</span>
    </div>
  </div>
</li>

<li class="d-flex mb-4 pb-1">
  <div class="avatar flex-shrink-0 me-3">
    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-message-rounded"></i></span>
  </div>
  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
    <div class="me-2">
      <h6 class="mb-0">Discussion Forums</h6>
      <small class="text-muted d-block mb-1">Community Interaction</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">12</h6>
      <span class="text-muted">Threads</span>
    </div>
  </div>
</li>
<li class="d-flex mb-4 pb-1">
  <div class="avatar flex-shrink-0 me-3">
    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-world"></i></span>
  </div>
  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
    <div class="me-2">
      <h6 class="mb-0">AR/VR Stats</h6>
      <small class="text-muted d-block mb-1">Engagement Metrics</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">1</h6>
      <span class="text-muted">Views</span>
    </div>
  </div>
</li>

<li class="d-flex mb-4 pb-1">
  <div class="avatar flex-shrink-0 me-3">
    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-cube"></i></span>
  </div>
  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
    <div class="me-2">
      <h6 class="mb-0">Integrations</h6>
      <small class="text-muted d-block mb-1">Access to Third-party Systems</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">2</h6>
      <span class="text-muted">Attempts</span>
    </div>
  </div>
</li>
<li class="d-flex mb-4 pb-1">
  <div class="avatar flex-shrink-0 me-3">
    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-world"></i></span>
  </div>
  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
    <div class="me-2">
      <h6 class="mb-0">Localization</h6>
      <small class="text-muted d-block mb-1">Learning Resources</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">1</h6>
      <span class="text-muted">Hindi Languages</span>
    </div>
  </div>
</li>

      </ul>
    </div>
  </div>
</div>
<!--/ Activities -->


         <!-- Learners ToDo & Calendar Insights -->
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
          id="learnerTodoMenu"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="learnerTodoMenu">
          <a class="dropdown-item" href="javascript:void(0);">Mark All as Done</a>
          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          <a class="dropdown-item" href="javascript:void(0);">Export Calendar</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Learner's ToDo -->
      
      <ul class="p-0 m-0">
        <li class="d-flex mb-4 ">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-book"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Complete Active Courses</h6>
              <small class="text-muted">Finish pending modules</small>
            </div>
            <span class="badge bg-label-info">2 Pending</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-line-chart"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Explore Recommended Courses</h6>
              <small class="text-muted">Based on your interests</small>
            </div>
            <span class="badge bg-label-success">5 New</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-map"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Progress in Learning Paths</h6>
              <small class="text-muted">Achieve milestones</small>
            </div>
            <span class="badge bg-label-warning">1 Path</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-task"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Submit Assignments & Quizzes</h6>
              <small class="text-muted">Due this week</small>
            </div>
            <span class="badge bg-label-danger">3 Tasks</span>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-group"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Engage in Community</h6>
              <small class="text-muted">Participate in discussions</small>
            </div>
            <span class="badge bg-label-secondary">Ongoing</span>
          </div>
        </li>
        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-camera"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Attend Live Sessions</h6>
              <small class="text-muted">Don't miss out</small>
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
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-trophy"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Points Earned</h6>
              <small class="text-muted">For activities this week</small>
            </div>
            <h6 class="mb-0">150 Pts</h6>
          </div>
        </li>
        <li class="d-flex mb-4">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-folder"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Content Repository Updates</h6>
              <small class="text-muted">New resources added</small>
            </div>
            <h6 class="mb-0">4 Items</h6>
          </div>
        </li>
        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-cube"></i></span>
          </div>
          <div class="d-flex w-100 justify-content-between align-items-center">
            <div>
              <h6 class="mb-0">Blockchain Stats</h6>
              <small class="text-muted">Latest updates</small>
            </div>
            <h6 class="mb-0">Active Nodes: 12</h6>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Learners ToDo & Insights End -->


              </div>
            </div>
            <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   