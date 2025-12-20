<?php 
include_once '../config.php';
$sql = "SELECT users.name, users.surname, news.title, news.data
FROM users
JOIN news ON users.LOGIN = news.users_LOGIN";
$result = $coni->query($sql);
if ($result) {
$row = $result->fetch_assoc();

		$t = $row['title'];
		$m = $row['data'];
		$by = $row['name'].' '.$row['surname'];


	
}
?>

<!-- Custom CSS -->
    <link href="../assets/libs/flot/css/float-chart.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
	<title>GeeqLXP - Future Learning | Digital Education | Innovative Instruction | Personalized Learning | E-Learning Evolution | Tech-Enhanced Education |  AI-Powered Learning | Online Skill Development | 
Adaptive Teaching | 
Virtual Classrooms | 
Smart Learning Solutions | 
Continuous Education | 
Interactive Curriculum | 
Global Learning Network | 
Emerging Technologies | 
Remote Learning | 
Flexible Education | 
Next-Gen EdTech | 
Lifelong Learning | 
GeeqLXP Community</title>
<link rel="icon" href="../assets/img/GeeqLXP_favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/img/GeeqLXP_favicon.png" type="image/x-icon">
 <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== 
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin5">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="#">
              <!-- Logo icon -->
              <b class="logo-icon ps-2">
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                <img
                  src="../assets/images/logo-icon.png"
                  alt="homepage"
                  class="light-logo"
                  width="25"
                />
              </b>
              <!--End Logo icon -->
              <!-- Logo text -->
              <span class="logo-text ms-2">
                <!-- dark Logo text -->
                GeeqLXP
              </span>
              <!-- Logo icon -->
              <!-- <b class="logo-icon"> -->
              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
              <!-- Dark Logo icon -->
              <!-- <img src="../assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

              <!-- </b> -->
              <!--End Logo icon -->
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a
              class="nav-toggler waves-effect waves-light d-block d-md-none"
              href="javascript:void(0)"
              ><i class="ti-menu ti-close"></i
            ></a>
          </div>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <div
            class="navbar-collapse collapse"
            id="navbarSupportedContent"
            data-navbarbg="skin5"
          >
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-start me-auto">
              <li class="nav-item d-none d-lg-block">
                <a
                  class="nav-link sidebartoggler waves-effect waves-light"
                  href="javascript:void(0)"
                  data-sidebartype="mini-sidebar"
                  ><i class="mdi mdi-menu font-24"></i
                ></a>
              </li>
              <!-- ============================================================== -->
              <!-- create new -->
              <!-- ============================================================== 
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <span class="d-none d-md-block"
                    >Create New <i class="fa fa-angle-down"></i
                  ></span>
                  <span class="d-block d-md-none"
                    ><i class="fa fa-plus"></i
                  ></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </li>
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- Search -->
              <!-- ============================================================== -->
              <li class="nav-item search-box">
                <a
                  class="nav-link waves-effect waves-dark"
                  href="javascript:void(0)"
                  ><i class="mdi mdi-magnify fs-4"></i
                ></a>
                <form class="app-search position-absolute">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Search &amp; enter"
                  />
                  <a class="srh-btn"><i class="mdi mdi-window-close"></i></a>
                </form>
              </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-end">
              <!-- ============================================================== -->
              <!-- Comment -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="mdi mdi-bell font-24"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#"><?php echo $t.'  '.$m.' by:'.$by; ?></a></li>
                 
                 <!-- <li><hr class="dropdown-divider" /></li>
                  <li>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </li>  -->
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- End Comment -->
              <!-- ============================================================== -->
              <!-- ============================================================== -->
              <!-- Messages -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark"
                  href="#"
                  id="2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="font-24 mdi mdi-comment-processing"></i>
                </a>
                <ul
                  class="
                    dropdown-menu dropdown-menu-end
                    mailbox
                    animated
                    bounceInDown
                  "
                  aria-labelledby="2"
                >
                  <ul class="list-style-none">
                    <li>
                      <div class="">
                        <!-- Message -->
                        <a href="javascript:void(0)" class="link border-top">
                          <div class="d-flex no-block align-items-center p-10">
                            <span
                              class="
                                btn btn-success btn-circle
                                d-flex
                                align-items-center
                                justify-content-center
                              "
                              ><i class="mdi mdi-calendar text-white fs-4"></i
                            ></span>
                            <div class="ms-2">
                              <h5 class="mb-0">Event today</h5>
                              <span class="mail-desc"
                                >None </span
                              >
                            </div>
                          </div>
                        </a>
                        <!-- Message -->
                        <a href="javascript:void(0)" class="link border-top">
                          <div class="d-flex no-block align-items-center p-10">
                            <span
                              class="
                                btn btn-info btn-circle
                                d-flex
                                align-items-center
                                justify-content-center
                              "
                              ><i class="mdi mdi-settings fs-4"></i
                            ></span>
                            <div class="ms-2">
                              <h5 class="mb-0">Settings</h5>
                              <span class="mail-desc"
                                >You can customize if</span
                              >
                            </div>
                          </div>
                        </a>
                        <!-- Message -->
                        <a href="javascript:void(0)" class="link border-top">
                          <div class="d-flex no-block align-items-center p-10">
                            <span
                              class="
                                btn btn-primary btn-circle
                                d-flex
                                align-items-center
                                justify-content-center
                              "
                              ><i class="mdi mdi-account fs-4"></i
                            ></span>
                            <div class="ms-2">
                              <h5 class="mb-0">Users</h5>
                              <span class="mail-desc"
                                >message if!</span
                              >
                            </div>
                          </div>
                        </a>
                        <!-- Message -->
                        <a href="javascript:void(0)" class="link border-top">
                          <div class="d-flex no-block align-items-center p-10">
                            <span
                              class="
                                btn btn-danger btn-circle
                                d-flex
                                align-items-center
                                justify-content-center
                              "
                              ><i class="mdi mdi-link fs-4"></i
                            ></span>
                            <div class="ms-2">
                              <h5 class="mb-0">Wht ?</h5>
                              <span class="mail-desc"
                                >Just see the !</span
                              >
                            </div>
                          </div>
                        </a>
                      </div>
                    </li>
                  </ul>
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- End Messages -->
              <!-- ============================================================== -->
				
              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  "
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    src="../images/avatar-2.jpg"
                    alt="user"
                    class="rounded-circle"
                    width="31"
                  />
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-end user-dd animated"
                  aria-labelledby="navbarDropdown"
                >
                  <a class="dropdown-item" href="coordinator_profile"
                    ><img src="../images/user.png" alt="" srcset="">&nbsp;&nbsp; Update My Profile</a
                  >
                  
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><img src="../images/inbox.png" alt="" srcset=""> &nbsp;&nbsp;Inbox</a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><img src="../images/personalization.png" alt="" srcset="">&nbsp;&nbsp; Personalize </a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="../index"
                    ><img src="../images/logout.png" alt="" srcset="">&nbsp;&nbsp; Logout</a
                  >
                  <div class="dropdown-divider"></div>
            <!--      <div class="ps-4 p-10">
                    <a
                      href="learners_profile"
                      class="btn btn-sm btn-success btn-rounded text-white"
                      >View Profile</a
                    >
                  </div>  -->
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
                <li id="dashboardItem" class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href='facilitators_dashboard' aria-expanded="false">
                        <i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span>
                    </a>
                </li>




				 <li id="learningManagement" class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-account-settings-variant"></i><span class="hide-menu">User Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                       
				  
                  <li class="sidebar-item">
                    <a href="uploadUsers" class="sidebar-link"
                      ><i class="mdi mdi-account-multiple"></i
                      ><span class="hide-menu"> Upload Users </span></a
                    >
                  </li>
				  <li class="sidebar-item">
                    <a   href="enableAccess"  class="sidebar-link"
                      ><i class="mdi mdi-account-key"></i
                      ><span class="hide-menu">Enable Access Permissions </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="editUsers" class="sidebar-link"
                      ><i class="mdi mdi-account-edit"></i
                      ><span class="hide-menu">Edit Users </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="retireUsers" class="sidebar-link"
                      ><i class="mdi mdi-account-multiple-minus"></i
                      ><span class="hide-menu">Retire Users </span></a
                    >
                  </li>
                  
                </ul>
              </li>
                <li id="learningManagement" class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu">Learning Management</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li id="manageCoursesItem" class="sidebar-item">
                            <a href="Courses" class="sidebar-link">
                                <i class="mdi mdi-book-open-variant"></i><span class="hide-menu">Manage Courses </span>
                            </a>
                        </li>
				  
                  <li class="sidebar-item">
                    <a href="LearningPaths" class="sidebar-link"
                      ><i class="mdi mdi-book-multiple-variant"></i
                      ><span class="hide-menu"> Manage Learning Paths </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="Personalization" class="sidebar-link"
                      ><i class="mdi mdi-book-plus"></i
                      ><span class="hide-menu">Manage Personalization </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="Recommendations" class="sidebar-link"
                      ><i class="mdi mdi-bookmark-check"></i
                      ><span class="hide-menu">Manage Recommendations </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a   href="Resources"  class="sidebar-link"
                      ><i class="mdi mdi-border-color"></i
                      ><span class="hide-menu">Manage  Resources </span></a
                    >
                  </li>
                </ul>
              </li>
			  
			    <li class="sidebar-item">
                <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-gamepad-variant"></i
                  ><span class="hide-menu">Learning Augumentation</span></a
                >
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="#" class="sidebar-link"
                      ><i class="mdi mdi-account-settings-variant"></i
                      ><span class="hide-menu"> Gamification </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="#" class="sidebar-link"
                      ><i class="mdi mdi-account-multiple"></i
                      ><span class="hide-menu">Interactivities </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="#" class="sidebar-link"
                      ><i class="mdi mdi-account-multiple-plus"></i
                      ><span class="hide-menu"> Social Learning </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="#" class="sidebar-link"
                      ><i class="mdi mdi-account-network"></i
                      ><span class="hide-menu"> aiml Content Curataion </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="#" class="sidebar-link"
                      ><i class="mdi mdi-account-search"></i
                      ><span class="hide-menu"> Content Formats </span></a
                    >
                  </li>
                </ul>
              </li>
              
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
	
	 
	  
	  
	  
	  
	  
	  
	  
	  
	  