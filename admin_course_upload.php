<?php
include_once('config.php');
$page="";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // File upload handling
        $brochure_path = null;

        if (isset($_FILES['brochure_pdf']) && $_FILES['brochure_pdf']['error'] == 0) {
            $uploadDir = 'uploads/brochures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Clean the RefCode for safe file naming
            $refCode = preg_replace("/[^a-zA-Z0-9_-]/", "_", $_POST['ref_code']);
            if (empty($refCode)) {
                $refCode = "COURSE";
            }

            // Define clean filename without timestamp
            $targetFile = $uploadDir . $refCode . ".pdf";

            $fileType = strtolower(pathinfo($_FILES['brochure_pdf']['name'], PATHINFO_EXTENSION));
            if ($fileType != 'pdf') {
                throw new Exception("Only PDF files are allowed for brochure upload.");
            }

            // Replace if same file already exists
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }

            if (!move_uploaded_file($_FILES['brochure_pdf']['tmp_name'], $targetFile)) {
                throw new Exception("Failed to upload the brochure file.");
            }

            $brochure_path = $targetFile;
        }

        $sql = "INSERT INTO course_metadata 
            (course_id, ref_code, title, duration, focus_area, delivery_method, prelude, prerequisites, audience, description, objectives, outcomes, takeaways, skills, modules, closing, brochure_path, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $coni->prepare($sql);

        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $coni->error);
        }

        $stmt->bind_param(
            'issssssssssssssss',
            $_POST['course_id'],
            $_POST['ref_code'],
            $_POST['title'],
            $_POST['duration'],
            $_POST['focus_area'],
            $_POST['delivery_method'],
            $_POST['prelude'],
            $_POST['prerequisites'],
            $_POST['audience'],
            $_POST['description'],
            $_POST['objectives'],
            $_POST['outcomes'],
            $_POST['takeaways'],
            $_POST['skills'],
            $_POST['modules'],
            $_POST['closing'],
            $brochure_path
        );

        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }

        $stmt->close();
        $coni->close();

        header('Location: admin_course_upload.php?success=1');
        exit();
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<?php include_once('head-nav.php'); ?>

<!-- Page Header -->
<section id="main-banner-page" class="position-relative page-header service-detail-header section-nav-smooth parallax" 
         style="background-size: 20% auto; background-repeat: no-repeat; background-position: center;">
    <div class="overlay overlay-dark opacity-7 z-index-1"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2"><p><br><br></p></div>
        </div>
        <div class="gradient-bg title-wrap">
            <div class="row">
                <div class="col-lg-12 col-md-12 whitecolor">
                    <h3 class="float-left">Admin Functionality</h3>
                    <ul class="breadcrumb top10 bottom10 float-right">
                        <li class="breadcrumb-item hover-light"><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page Header ends -->

<section class="padding bglight">
    <div class="container">

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Metadata saved successfully!</div>
        <?php endif; ?>

        <div class="col-lg-10 col-md-12 mx-auto whitebox">
            <div class="widget logincontainer p-4">
                <h3 class="darkcolor bottom35 text-center text-md-left">
                    <i class="fa fa-database text-warning mr-2"></i> Add Course Metadata
                </h3>

                <form method="POST" enctype="multipart/form-data" class="getin_form border-form" style="position:relative;">
                    <div class="row">

                        <!-- Row 1 -->
                        <div class="col-md-4">
                            <div class="form-group bottom35" style="position:relative;">
                                <i class="fa fa-id-badge" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
                                <input type="number" name="course_id" class="form-control" placeholder="Course ID" required style="padding-left:40px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group bottom35" style="position:relative;">
                                <i class="fa fa-barcode" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
                                <input type="text" name="ref_code" class="form-control" placeholder="Reference Code (e.g., AI101)" required style="padding-left:40px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group bottom35" style="position:relative;">
                                <i class="fa fa-book" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
                                <input type="text" name="title" class="form-control" placeholder="Course Title" required style="padding-left:40px;">
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-6">
                            <div class="form-group bottom35" style="position:relative;">
                                <i class="fa fa-clock-o" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
                                <input type="text" name="duration" class="form-control" placeholder="Duration (e.g., 20 hrs)" style="padding-left:40px;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group bottom35" style="position:relative;">
                                <i class="fa fa-laptop" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
                                <input type="text" name="delivery_method" class="form-control" placeholder="Delivery Method (Online / Hybrid)" style="padding-left:40px;">
                            </div>
                        </div>

                        <!-- Multi-line fields -->
                        <?php
                        $fields = [
                            ['focus_area', 'fa-bullseye', 'Focus Area'],
                            ['prelude', 'fa-info-circle', 'Prelude / Introduction'],
                            ['prerequisites', 'fa-list-ul', 'Prerequisites'],
                            ['audience', 'fa-users', 'Target Audience'],
                            ['description', 'fa-align-left', 'Course Description'],
                            ['objectives', 'fa-lightbulb-o', 'Learning Objectives'],
                            ['outcomes', 'fa-rocket', 'Expected Outcomes'],
                            ['takeaways', 'fa-check-circle', 'Key Takeaways'],
                            ['skills', 'fa-cogs', 'Skills Covered'],
                            ['modules', 'fa-tasks', 'Modules Covered'],
                            ['closing', 'fa-commenting', 'Closing Remarks']
                        ];

                        foreach ($fields as $field) {
                            echo '<div class="col-md-6">
                                    <div class="form-group bottom35" style="position:relative;">
                                        <i class="fa ' . $field[1] . '" style="position:absolute;left:15px;top:15px;color:#999;"></i>
                                        <textarea name="' . $field[0] . '" class="form-control" rows="2" placeholder="' . $field[2] . '" style="padding-left:40px;"></textarea>
                                    </div>
                                  </div>';
                        }
                        ?>

                        <!-- PDF Upload -->
                        <div class="col-md-12">
                            <div class="form-group bottom35" style="position:relative;">
                                <i class="fa fa-file-pdf-o" style="position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
                                <input type="file" name="brochure_pdf" accept=".pdf" class="form-control" required style="padding-left:40px;">
                                <small style="color:#777;">File will be saved as [RefCode].pdf</small>
                            </div>
                        </div>

                        <div class="col-md-12 text-center top20">
                            <button type="submit" class="button gradient-btn w-100">
                                <i class="fa fa-save mr-2"></i> Save Metadata
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include_once('footer.php'); ?>
