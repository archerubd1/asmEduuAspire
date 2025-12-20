<?php
/**
 * Astraal LXP - Instructor Problem Solving Dashboard
 * Fully aligned with new canonical 9 problem types
 * PHP 5.4 + MySQL 5.x Compatible (GoDaddy/UWAMP)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_id']) || !isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$phx_user_id    = (int) $_SESSION['phx_user_id'];
$phx_user_login = $_SESSION['phx_user_login'];

$page = "problemSolvingInstructor";
require_once('instructorHead_Nav2.php');

/* -------------------------------------------------------------------------
   OLD CARD LABELS → NEW PROBLEM TYPE SLUGS (canonical mapping)
   Each tile keeps its label but internally maps to the 9 main categories
---------------------------------------------------------------------------*/
$tileMap = array(

    "real_world_scenarios" => array(
        "label"  => "Use Real-World Scenarios",
        "cta"    => "Create Case Study",
        "slug"   => "authentic",
        "icon"   => "bx bx-world",
        "color"  => "danger"
    ),

    "inquiry_learning" => array(
        "label"  => "Promote Inquiry-Based Learning",
        "cta"    => "Initiate Discussion",
        "slug"   => "exploratory",
        "icon"   => "bx bx-question-mark",
        "color"  => "info"
    ),

    "group_problem_solving" => array(
        "label"  => "Implement Group Challenges",
        "cta"    => "Assign Group Work",
        "slug"   => "strategic",
        "icon"   => "bx bx-group",
        "color"  => "warning"
    ),

    "puzzles_challenges" => array(
        "label"  => "Encourage Puzzles & Challenges",
        "cta"    => "Start Challenge",
        "slug"   => "procedural",
        "icon"   => "bx bx-brain",
        "color"  => "success"
    ),

    "project_learning" => array(
        "label"  => "Incorporate Project-Based Learning",
        "cta"    => "Assign Project",
        "slug"   => "design",
        "icon"   => "bx bx-briefcase-alt",
        "color"  => "primary"
    ),

    "design_thinking" => array(
        "label"  => "Apply Design Thinking",
        "cta"    => "Define, Ideate, Test",
        "slug"   => "design",
        "icon"   => "bx bx-edit",
        "color"  => "dark"
    ),

    "root_cause_analysis" => array(
        "label"  => "Use Root Cause Analysis",
        "cta"    => "Apply 5 Whys",
        "slug"   => "diagnosis",
        "icon"   => "bx bx-search-alt",
        "color"  => "danger"
    ),

    "critical_frameworks" => array(
        "label"  => "Critical Thinking Frameworks",
        "cta"    => "Apply SWOT",
        "slug"   => "strategic",
        "icon"   => "bx bx-pie-chart-alt",
        "color"  => "success"
    ),

    "open_ended_problems" => array(
        "label"  => "Assign Open-Ended Problems",
        "cta"    => "Create Assignment",
        "slug"   => "transfer",
        "icon"   => "bx bx-book-reader",
        "color"  => "secondary"
    ),

    "ai_simulation" => array(
        "label"  => "Use AI & Simulations",
        "cta"    => "Launch AI Module",
        "slug"   => "quantum",
        "icon"   => "bx bx-chip",
        "color"  => "warning"
    ),

    "reflective_learning" => array(
        "label"  => "Reflect on Past Experiences",
        "cta"    => "Start Reflection",
        "slug"   => "indic",
        "icon"   => "bx bx-history",
        "color"  => "primary"
    )
);

/* -------------------------------------------------------------------------
   LOAD dynamic stats per problem type
---------------------------------------------------------------------------*/
$stats = array();

foreach ($tileMap as $key => $tile) {

    $slug = mysqli_real_escape_string($coni, $tile['slug']);

    // Fetch problem type ID
    $resType = $coni->query("
        SELECT id FROM problem_types
        WHERE slug='$slug' LIMIT 1
    ");

    $type_id = 0;
    if ($resType && $resType->num_rows > 0) {
        $type_id = (int)$resType->fetch_assoc()['id'];
    }

    // Count problem variants
    $variantCount = 0;
    if ($type_id > 0) {
        $res = $coni->query("
            SELECT COUNT(*) AS c FROM problem_variants
            WHERE problem_type_id=$type_id
        ");
        if ($res) {
            $variantCount = (int)$res->fetch_assoc()['c'];
        }
    }

    // Count pending grading attempts
    $pendingCount = 0;
    if ($type_id > 0) {
        $res = $coni->query("
            SELECT COUNT(*) AS c
            FROM problem_attempts
            WHERE status='submitted'
              AND problem_variant_id IN (
                    SELECT id FROM problem_variants WHERE problem_type_id=$type_id
              )
        ");
        if ($res) {
            $pendingCount = (int)$res->fetch_assoc()['c'];
        }
    }

    $stats[$key] = array(
        "variants" => $variantCount,
        "pending"  => $pendingCount
    );
}

?>

<!-- ================== PAGE LAYOUT ================== -->
<div class="layout-page">

<?php require_once('instructorNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<div class="col-lg-12 mb-4 order-0">
  <div class="accordion mt-3" id="problemSolvingAccordion">
    <div class="accordion-item">

      <h4 class="accordion-header">
        <button type="button" class="accordion-button bg-label-primary" data-bs-toggle="collapse"
            data-bs-target="#problemSolvingPanel" aria-expanded="true">
            <i class="bx bx-brain" style="font-size:22px;color:#007bff;"></i>
            &nbsp; Manage Learning Journey &nbsp;|&nbsp; Problem Solving Skills
        </button>
      </h4>

      <div id="problemSolvingPanel" class="accordion-collapse collapse show">
        <div class="accordion-body">

          <div class="row">

<?php
/* -------------------------------------------------------------------------
   RENDER CARDS
---------------------------------------------------------------------------*/
foreach ($tileMap as $key => $tile):

    $slug         = $tile['slug'];
    $label        = $tile['label'];
    $ctaLabel     = $tile['cta'];
    $icon         = $tile['icon'];
    $btnColor     = $tile['color'];

    $variantCount = $stats[$key]['variants'];
    $pendingCount = $stats[$key]['pending'];

    $adminPage    = "problem_type_admin.php?type=" . urlencode($slug);
    $listPage     = "problem_type_admin.php?action=list&type=" . urlencode($slug);
    $attemptsPage = "problem_type_admin.php?action=attempts&type=" . urlencode($slug);
	
?>
    <div class="col-md-4 mb-3">
      <div class="card text-center shadow-sm p-3">

        <i class="<?php echo $icon; ?>" style="font-size:40px;"></i>

        <h6 class="mt-2"><?php echo htmlspecialchars($label); ?></h6>

        <div class="mt-1">
          <a href="<?php echo $listPage; ?>" class="badge bg-primary" style="cursor:pointer;">
            <?php echo $variantCount; ?> Problems
          </a>
		  
          <!-- TO GRADE: now clickable and navigates to attempts list for the problem type -->
          <a href="<?php echo $attemptsPage; ?>" class="badge bg-warning" style="cursor:pointer;">
            <?php echo $pendingCount; ?> To Grade
          </a>
        </div>

        <a href="<?php echo $adminPage; ?>"
           class="btn btn-<?php echo $btnColor; ?> btn-sm mt-2">
           <?php echo htmlspecialchars($ctaLabel); ?>
        </a>

      </div>
    </div>

<?php endforeach; ?>

          </div> <!-- row -->

        </div>
      </div>
    </div>
  </div>
</div>

</div>
</div>

<?php require_once('../platformFooter.php'); ?>
</div>
