<?php 


require_once('adminHead_Nav.php');

?>


        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search 
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. 
                <li class="nav-item lh-1 me-3">
                  <a
                    class="github-button"
                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                    >Star</a
                  >
                </li>

               <!-- Platform Admin -->
<li class="nav-item navbar-dropdown dropdown-user dropdown">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
    <div class="avatar avatar-online">
      <img src="../assets/img/avatars/admin-avatar.png" alt="Admin Avatar" class="w-px-40 h-auto rounded-circle" />
    </div>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="#">
        <div class="d-flex">
          <div class="flex-shrink-0 me-3">
            <div class="avatar avatar-online">
              <img src="../assets/img/avatars/admin-avatar.png" alt="Admin Avatar" class="w-px-40 h-auto rounded-circle" />
            </div>
          </div>
          <div class="flex-grow-1">
            <span class="fw-semibold d-block">Admin Name</span>
            <small class="text-muted">Platform Admin</small>
          </div>
        </div>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <a class="dropdown-item" href="admin-profile.html">
        <i class="bx bx-user me-2"></i>
        <span class="align-middle">My Profile</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="admin-settings.html">
        <i class="bx bx-cog me-2"></i>
        <span class="align-middle">Settings</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="admin-user-management.html">
        <i class="bx bx-group me-2"></i>
        <span class="align-middle">User Management</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="admin-system-logs.html">
        <i class="bx bx-file me-2"></i>
        <span class="align-middle">System Logs</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="admin-billing.html">
        <span class="d-flex align-items-center align-middle">
          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
          <span class="flex-grow-1 align-middle">Billing</span>
          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
        </span>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <a class="dropdown-item" href="../../index.php">
        <i class="bx bx-power-off me-2"></i>
        <span class="align-middle">Log Out</span>
      </a>
    </li>
  </ul>
</li>
<!--/ Platform Admin -->

              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

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
							  <h5 class="card-title text-primary">Welcome Admin! 🎉</h5>
							  <p class="mb-4">
								You have <span class="fw-bold">5 pending user requests</span> and <span class="fw-bold">3 system alerts</span>. 
								Review them to ensure smooth platform operations.
							  </p>
							  <div class="d-flex gap-2">
								<a href="user-management.html" class="btn btn-sm btn-outline-primary">Manage Users</a>
								<a href="system-alerts.html" class="btn btn-sm btn-outline-secondary">View Alerts</a>
							  </div>
							</div>
						  </div>
						  <div class="col-sm-5 text-center text-sm-left">
							<div class="card-body pb-0 px-0 px-md-4">
							  <img
								src="../assets/img/illustrations/man-with-laptop-light.png"
								height="140"
								alt="Admin Dashboard"
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
    <!-- User Management Card -->
    <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-user-circle bx-lg text-primary"></i>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="userMgmtOpt"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userMgmtOpt">
                <a class="dropdown-item" href="user-management.html">View Users</a>
                <a class="dropdown-item" href="add-user.html">Add User</a>
              </div>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Active Users</span>
          <h3 class="card-title mb-2">1,245</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +15% this month</small>
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
                <a class="dropdown-item" href="system-alerts.html">View Alerts</a>
                <a class="dropdown-item" href="alert-settings.html">Manage Alerts</a>
              </div>
            </div>
          </div>
          <span>System Alerts</span>
          <h3 class="card-title text-nowrap mb-1">3 Critical</h3>
          <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> </small>
        </div>
      </div>
    </div>
  </div>
</div>			
				
<!-- Total Revenue -->
<div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
  <div class="card">
    <div class="row row-bordered g-0">
      <div class="col-md-8">
        <h5 class="card-header m-0 me-2 pb-3">Platform Metrics</h5>
        <div id="platformMetricsChart" class="px-2"></div>
      </div>
      <div class="col-md-4">
        <div class="card-body">
          <div class="text-center">
            <div class="dropdown">
              <button
                class="btn btn-sm btn-outline-primary dropdown-toggle"
                type="button"
                id="metricsReportId"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                2024
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="metricsReportId">
                <a class="dropdown-item" href="javascript:void(0);">2023</a>
                <a class="dropdown-item" href="javascript:void(0);">2022</a>
                <a class="dropdown-item" href="javascript:void(0);">2021</a>
              </div>
            </div>
          </div>
        </div>
        <div id="growthChart"></div>
        <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

       <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
  <div class="d-flex">
    <div class="me-2">
      <span class="badge bg-label-primary p-2"><i class="bx bx-user text-primary"></i></span>
    </div>
    <div class="d-flex flex-column">
      <small>Users</small>
      <h6 class="mb-0" id="activeUsersCount"></h6>
    </div>
  </div>
  <div class="d-flex">
    <div class="me-2">
      <span class="badge bg-label-success p-2"><i class="bx bx-book text-success"></i></span>
    </div>
    <div class="d-flex flex-column">
      <small>Courses</small>
      <h6 class="mb-0" id="totalCoursesCount"></h6>
    </div>
  </div>
  <div class="d-flex">
    <div class="me-2">
      <span class="badge bg-label-info p-2"><i class="bx bx-dollar-circle text-info"></i></span>
    </div>
    <div class="d-flex flex-column">
      <small>Total Revenue</small>
      <h6 class="mb-0" id="totalRevenueAmount"></h6>
    </div>
  </div>
</div>

		
		
      </div>
    </div>
  </div>
</div>
<!--/ Total Revenue -->

<!-- Vendors JS -->
<script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

<script>
  // Example data for Users, Courses, and Revenue
  var options = {
    series: [{
        name: 'Active Users',
        data: [200, 400, 600, 800]
      },
      {
        name: 'Total Courses',
        data: [50, 80, 100, 120]
      },
      {
        name: 'Revenue',
        data: [30000, 40000, 50000, 60000]
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

  var chart = new ApexCharts(document.querySelector("#platformMetricsChart"), options);
  chart.render();
  
  document.getElementById('activeUsersCount').innerText = chart.w.config.series[0].data[chart.w.config.series[0].data.length - 1]; // Last data point
document.getElementById('totalCoursesCount').innerText = chart.w.config.series[1].data[chart.w.config.series[1].data.length - 1];
document.getElementById('totalRevenueAmount').innerText = chart.w.config.series[2].data[chart.w.config.series[2].data.length - 1];
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
          <span class="fw-semibold d-block mb-1">Profile Insights</span>
          <h3 class="card-title text-nowrap mb-2" id="behavioralInsightsCount">139</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +12%</small>
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
          <h3 class="card-title mb-2" id="adaptivePathsCount">83</h3>
          <small class="text-info fw-semibold"><i class="bx bx-up-arrow-alt"></i> +8%</small>
        </div>
      </div>
    </div>

    <!-- Gamification -->
    <div class="col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <i class="bx bx-lg bx-game text-info"></i>
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
          <span class="fw-semibold d-block mb-1">Gamification</span>
          <h3 class="card-title mb-2" id="incentivesCount">0</h3>
          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +37%</small>
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
			  
			  
               <!-- Platform Admin Insights -->
<div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">Platform Admin Insights</h5>
        <small class="text-muted">Comprehensive Overview</small>
      </div>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="adminInsights"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="adminInsights">
          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          <a class="dropdown-item" href="javascript:void(0);">Share</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Course Library -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column align-items-center gap-1">
          <h2 class="mb-2">1,542</h2>
          <span>Total Courses</span>
        </div>
        <div id="courseLibraryChart"></div>
		
		<script>
        var options = {
          chart: {
            type: 'bar',
          },
          series: [{
            name: 'Courses',
            data: [10, 15, 20, 25, 30, 35, 40]
          }],
          xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
          }
        };
        
        var chart = new ApexCharts(document.querySelector("#courseLibraryChart"), options);
        chart.render();
      </script>
      </div>
      
      <!-- Groups and Teams -->
      <ul class="p-0 m-0">
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Groups & Teams</h6>
              <small class="text-muted">245 Active Teams</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">12,340</small>
            </div>
          </div>
        </li>

        <!-- Content Approvals -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-file"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Content Approvals</h6>
              <small class="text-muted">Pending: 85</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">1,200</small>
            </div>
          </div>
        </li>

        <!-- Leaderboards -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-clipboard"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Leaderboards</h6>
              <small class="text-muted">Top Performers: 25</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">5 Daily Updates</small>
            </div>
          </div>
        </li>

        <!-- Live Sessions -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-video"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Live Sessions</h6>
              <small class="text-muted">Ongoing: 18</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">78 Completed</small>
            </div>
          </div>
        </li>

        <!-- Mentorship Programs -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-user-check"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Mentorship Programs</h6>
              <small class="text-muted">Active Mentors: 35</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">450 Trainees</small>
            </div>
          </div>
        </li>

        <!-- Blockchain Credentials -->
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-blockchain"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Blockchain Credentials</h6>
              <small class="text-muted">Total: 1,005</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">650 Verified</small>
            </div>
          </div>
        </li>

        <!-- Support Tickets -->
        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-support"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Support Tickets</h6>
              <small class="text-muted">Open: 12</small>
            </div>
            <div class="user-progress">
              <small class="fw-semibold">2,345 Resolved</small>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Platform Admin Insights -->




<!-- lxp Activities -->
<div class="col-md-6 col-lg-4 order-0 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="card-title m-0 me-2">EduuAspire lxp Activities</h5>
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
          <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
          <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
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
			  <small class="text-muted d-block mb-1">EduuAspire Train2Hire</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">68</h6>
              <span class="text-muted">Placements</span>
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
              <h6 class="mb-0">20</h6>
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
			  <small class="text-muted d-block mb-1">by 39 users</small>
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
              <h6 class="mb-0">78</h6>
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
      <h6 class="mb-0">Content Management</h6>
      <small class="text-muted d-block mb-1">Repository & Approvals</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">350</h6>
      <span class="text-muted">Files</span>
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
      <h6 class="mb-0">120</h6>
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
      <h6 class="mb-0">1200</h6>
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
      <small class="text-muted d-block mb-1">Third-party Systems</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">120</h6>
      <span class="text-muted">APIs</span>
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
      <small class="text-muted d-block mb-1">Global Content Adaptation</small>
    </div>
    <div class="user-progress d-flex align-items-center gap-1">
      <h6 class="mb-0">85</h6>
      <span class="text-muted">Languages</span>
    </div>
  </div>
</li>

      </ul>
    </div>
  </div>
</div>
<!--/ Activities -->


         <!-- Platform SystemLevel Insights -->
<div class="col-md-6 col-lg-4 col-xl-4 order-2 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex align-items-center justify-content-between pb-0">
      <div class="card-title mb-0">
        <h5 class="m-0 me-2">Platform SystemLevel Insights</h5>
        <small class="text-muted">Comprehensive Overview</small>
      </div>
      <div class="dropdown">
        <button
          class="btn p-0"
          type="button"
          id="adminInsights"
          data-bs-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="adminInsights">
          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          <a class="dropdown-item" href="javascript:void(0);">Share</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      
     <!-- Platform Server Usage Insights -->

<div id="serverUsageChart"></div>

<script>
  var options = {
    chart: {
      type: 'bar',
    },
    series: [{
      name: 'Server Stats',
      data: [800, 4500, 70, 65]  // Example data: [Bandwidth (GB), Traffic Hits, Memory Usage (%), Disk Usage (%)]
    }],
    xaxis: {
      categories: ['Bandwidth', 'Hits', 'Memory', 'Disk']
    }
  };

  var chart = new ApexCharts(document.querySelector("#serverUsageChart"), options);
  chart.render();
</script>



      <!-- Server Stats -->
      <ul class="p-0 m-0">
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-server"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Bandwidth Usage</h6>
              <small class="text-muted d-block mb-1">till date</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">1.3 TB</h6>
              <span class="text-muted">Used of 5TB</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-network"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Traffic Hits</h6>
              <small class="text-muted d-block mb-1">on Website & Self Sign Ups</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">0.8M</h6>
              <span class="text-muted">Hits</span>
            </div>
          </div>
        </li>
        <li class="d-flex mb-4 pb-1">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-memory-card"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Memory Usage</h6>
              <small class="text-muted d-block mb-1">for Courses & Live Sessions</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">1.3 GB</h6>
              <span class="text-muted">Used</span>
            </div>
          </div>
        </li>
        <li class="d-flex">
          <div class="avatar flex-shrink-0 me-3">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-hdd"></i></span>
          </div>
          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
              <h6 class="mb-0">Disk Usage</h6>
              <small class="text-muted d-block mb-1">Content & Resources</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
              <h6 class="mb-0">3.7 GB </h6>
              <span class="text-muted">Used</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- Platform SystemLevel Insights -->  


              </div>
            </div>
            <!-- / Content -->

<?php 
require_once('../platformFooter.php');
?>
   