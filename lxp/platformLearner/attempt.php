<?php
/**
 * File: learner/attempt.php
 * Purpose: Learner Attempt Page — attachments stored in problem_attachments
 * PHP 5.4+ / MySQLi
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

if (!isset($_SESSION['phx_user_login'])) {
    header("Location: ../../phxlogin.php");
    exit;
}

$user_login = $_SESSION['phx_user_login'];

/* Upload directory (always defined) */
$upload_dir_base = realpath(__DIR__ . '/../../uploads') ?: (__DIR__ . '/../../uploads');
$upload_dir_base = rtrim($upload_dir_base, '/\\') . '/';
$upload_dir = $upload_dir_base . 'attempts/';

/* Ensure upload dir exists early */
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true) && !is_dir($upload_dir)) {
        error_log("Failed to create upload directory: " . $upload_dir);
    }
}

/* POST handling */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $attempt_id_post = isset($_POST['attempt_id']) ? (int)$_POST['attempt_id'] : 0;
    $variant_id      = isset($_GET['variant_id']) ? (int)$_GET['variant_id'] : 0;

    if ($attempt_id_post <= 0 || $variant_id <= 0) {
        http_response_code(400);
        die("Invalid attempt reference.");
    }

    $answer_new = isset($_POST['answer_text']) ? trim($_POST['answer_text']) : '';
    $answerEsc  = mysqli_real_escape_string($coni, $answer_new);
    $now        = time();

    /* Handle file upload (if any) and persist to problem_attachments */
    $uploaded_attachment_id = 0;
    if (isset($_FILES['answer_file']) && is_uploaded_file($_FILES['answer_file']['tmp_name']) && $_FILES['answer_file']['error'] == UPLOAD_ERR_OK) {
        $original = basename($_FILES['answer_file']['name']);
        $safeName = preg_replace("/[^A-Za-z0-9_.-]/", "_", $original);
        $newFile  = "attempt_" . $attempt_id_post . "_" . time() . "_" . $safeName;
        $dest     = $upload_dir . $newFile;

        if (move_uploaded_file($_FILES['answer_file']['tmp_name'], $dest)) {
            @chmod($dest, 0644);
            $file_path_esc = mysqli_real_escape_string($coni, $newFile);
            $mime_raw = isset($_FILES['answer_file']['type']) ? $_FILES['answer_file']['type'] : '';
            $mime_esc = mysqli_real_escape_string($coni, $mime_raw);

            // compute sha256 hash (best-effort)
            $file_hash = @hash_file('sha256', $dest);
            $file_hash_esc = $file_hash ? mysqli_real_escape_string($coni, $file_hash) : '';

            // Insert attachment record
            $sqlAttach = "
                INSERT INTO problem_attachments
                    (attempt_id, file_path, mime_type, `hash`, created_at)
                VALUES (
                    {$attempt_id_post},
                    '{$file_path_esc}',
                    " . ($mime_esc === '' ? "NULL" : "'{$mime_esc}'") . ",
                    " . ($file_hash_esc === '' ? "NULL" : "'{$file_hash_esc}'") . ",
                    {$now}
                )
            ";
            if ($coni->query($sqlAttach)) {
                $uploaded_attachment_id = (int)$coni->insert_id;
            } else {
                error_log("Attachment insert failed: " . $coni->error . " -- SQL: " . $sqlAttach);
            }
        } else {
            error_log("move_uploaded_file failed for attempt {$attempt_id_post}. tmp: " . $_FILES['answer_file']['tmp_name']);
        }
    }

    /* SAVE DRAFT */
    if (isset($_POST['save_draft'])) {

        // Note: we no longer update a file_path column on problem_attempts
        $sqlUpdateDraft = "
            UPDATE problem_attempts
            SET answer_text   = '{$answerEsc}',
                last_saved_at = {$now},
                updated_at    = {$now}
            WHERE id = {$attempt_id_post}
              AND status = 'draft'
            LIMIT 1
        ";

        if (!$coni->query($sqlUpdateDraft)) {
            error_log("Draft update error: " . $coni->error . " -- SQL: " . $sqlUpdateDraft);
            die("<div class='alert alert-danger'>Error saving draft. Contact support.</div>");
        }

        header("Location: attempt.php?variant_id={$variant_id}&msg=" . urlencode(base64_encode("Draft saved successfully")));
        exit;
    }

    /* SUBMIT FINAL */
    if (!empty($_POST['submit_answer'])) {

        $sqlSubmit = "
            UPDATE problem_attempts
            SET answer_text   = '{$answerEsc}',
                submitted_at  = {$now},
                status        = 'submitted',
                updated_at    = {$now}
            WHERE id = {$attempt_id_post}
              AND status = 'draft'
            LIMIT 1
        ";

        if (!$coni->query($sqlSubmit)) {
            error_log("Submit update error: " . $coni->error . " -- SQL: " . $sqlSubmit);
            die("<div class='alert alert-danger'>Error submitting attempt. Contact support.</div>");
        }

        if ($coni->affected_rows === 0) {
            error_log("Submit attempt did not affect rows. attempt_id={$attempt_id_post}, SQL: {$sqlSubmit}");
            die("<div class='alert alert-warning'>No changes were made. Ensure attempt is in draft status.</div>");
        }

        header("Location: attempt.php?variant_id={$variant_id}&msg=" . urlencode(base64_encode("Your answer has been submitted successfully")));
        exit;
    }

} // end POST


/* GET: variant_id */
$variant_id = isset($_GET['variant_id']) ? (int)$_GET['variant_id'] : 0;
if ($variant_id <= 0) {
    die("Invalid variant ID");
}

$page = "problemSolving";
require_once('learnerHead_Nav2.php');
?>

<div class="layout-page">
<?php require_once('learnersNav.php'); ?>

<div class="content-wrapper">
<div class="container-xxl flex-grow-1 container-p-y">

<?php
/* FETCH VARIANT */
$sqlVar = "
    SELECT 
        v.id,
        v.problem_type_id,
        v.problem_slug,
        v.level,
        v.statement,
        v.expected_outcome,
        v.difficulty_score
    FROM problem_variants v
    WHERE v.id = {$variant_id}
    LIMIT 1
";
$resVar = $coni->query($sqlVar);
if (!$resVar || $resVar->num_rows == 0) {
    die("<div class='alert alert-danger'>Problem variant not found.</div>");
}
$variant = $resVar->fetch_assoc();
$ptype_id = (int)$variant['problem_type_id'];

$sqlType = "SELECT title FROM problem_types WHERE id = {$ptype_id} AND active = 1 LIMIT 1";
$resType = $coni->query($sqlType);
$type_title = ($resType && $resType->num_rows > 0) ? $resType->fetch_assoc()['title'] : "Unknown Problem Type";

$problem_slug     = htmlspecialchars($variant['problem_slug']);
$statement        = htmlspecialchars($variant['statement']);
$expected_outcome = htmlspecialchars($variant['expected_outcome']);
$level            = htmlspecialchars($variant['level']);

/* Normalize difficulty (support textual + legacy numeric) */
$difficulty_raw = isset($variant['difficulty_score']) ? $variant['difficulty_score'] : '';
$numeric_to_text = array(
    '1' => 'novice',
    '2' => 'beginner',
    '3' => 'intermediate',
    '4' => 'advance',
    '5' => 'expert'
);
if (is_numeric($difficulty_raw) && isset($numeric_to_text[(string)$difficulty_raw])) {
    $difficulty = $numeric_to_text[(string)$difficulty_raw];
} else {
    $difficulty = strtolower(trim((string)$difficulty_raw));
    if ($difficulty === '') {
        $difficulty = 'unknown';
    }
}

/* FIND LATEST ATTEMPT (any status). Reuse it to avoid creating duplicates. */
$sqlFindLatest = "
    SELECT id, answer_text, started_at, last_saved_at, status
    FROM problem_attempts
    WHERE user_login = '" . mysqli_real_escape_string($coni, $user_login) . "'
      AND problem_variant_id = {$variant_id}
    ORDER BY id DESC
    LIMIT 1
";
$resLatest = $coni->query($sqlFindLatest);

if ($resLatest && $resLatest->num_rows > 0) {
    $attempt_row = $resLatest->fetch_assoc();
    $attempt_id  = (int)$attempt_row['id'];
    $attempt_status = $attempt_row['status'];
    $existing_answer = htmlspecialchars($attempt_row['answer_text']);
} else {
    // No attempt exists — create draft
    $started_at = time();
    $sqlInsertDraft = "
        INSERT INTO problem_attempts
            (user_login, problem_variant_id, started_at, status, created_at, updated_at)
        VALUES (
            '" . mysqli_real_escape_string($coni, $user_login) . "',
            {$variant_id},
            {$started_at},
            'draft',
            {$started_at},
            {$started_at}
        )
    ";
    if (!$coni->query($sqlInsertDraft)) {
        error_log("Error creating attempt draft: " . $coni->error . " -- SQL: " . $sqlInsertDraft);
        die("<div class='alert alert-danger'>Error creating attempt draft. Contact support.</div>");
    }
    $attempt_id = (int)$coni->insert_id;
    $attempt_status = 'draft';
    $existing_answer = '';
}

/* Load attachments for this attempt */
$attachments = array();
$sqlAttachList = "
    SELECT id, file_path, mime_type, `hash`, created_at
    FROM problem_attachments
    WHERE attempt_id = {$attempt_id}
    ORDER BY id ASC
";
$resAttach = $coni->query($sqlAttachList);
if ($resAttach) {
    while ($r = $resAttach->fetch_assoc()) {
        $attachments[] = $r;
    }
}

/* If attempt is submitted, we'll render read-only or disable submit controls in UI */
$is_submitted = ($attempt_status === 'submitted');
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h4 class="card-title mb-3">
                    <i class="fa fa-lightbulb text-warning"></i>
                    <?php echo htmlspecialchars($type_title); ?>  
                    <span class="text-muted">/ <?php echo ucfirst($level); ?> Level</span>
                </h4>

                <p class="text-muted small">
                    <i class="fa fa-tag"></i> 
                    <strong>Problem:</strong> <?php echo $problem_slug; ?>
                </p>

                <p>
                    <?php
                        $diffNames = array(
                            'novice'      => "Novice",
                            'beginner'    => "Beginner",
                            'intermediate'=> "Intermediate",
                            'advance'     => "Advanced",
                            'expert'      => "Expert",
                            'unknown'     => "Unknown"
                        );
                        $diffBadge = array(
                            'novice'       => "bg-primary",
                            'beginner'     => "bg-info",
                            'intermediate' => "bg-warning text-dark",
                            'advance'      => "bg-danger",
                            'expert'       => "bg-dark",
                            'unknown'      => "bg-secondary"
                        );
                        $dv = isset($difficulty) ? $difficulty : 'unknown';
                        $label = isset($diffNames[$dv]) ? $diffNames[$dv] : ucfirst($dv);
                        $badge = isset($diffBadge[$dv]) ? $diffBadge[$dv] : 'bg-secondary';
                    ?>
                    <span class="badge <?php echo htmlspecialchars($badge); ?>"><?php echo htmlspecialchars($label); ?></span>
                </p>

                <div class="alert alert-primary">
                    <h6 class="mb-1"><i class="fa fa-align-left"></i> Problem Statement</h6>
                    <div><?php echo nl2br($statement); ?></div>
                </div>

                <div class="alert alert-info">
                    <h6 class="mb-1"><i class="fa fa-check-circle"></i> Expected Outcome</h6>
                    <div><?php echo nl2br($expected_outcome); ?></div>
                </div>

                <!-- Answer Entry Form -->
                <form method="POST" enctype="multipart/form-data" 
                      action="attempt.php?variant_id=<?php echo $variant_id; ?>" 
                      id="attemptForm">

                    <input type="hidden" name="attempt_id" value="<?php echo $attempt_id; ?>">
                    <!-- Hidden field used by JS to signal submission -->
                    <input type="hidden" name="submit_answer" id="submit_answer_hidden" value="">

                    <div class="mb-3">
                        <label class="form-label"><i class="fa fa-pen"></i> Your Answer</label>
                        <textarea name="answer_text" class="form-control" rows="8" style="min-height:200px;" <?php echo $is_submitted ? 'readonly' : ''; ?>><?php echo $existing_answer; ?></textarea>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label"><i class="fa fa-paperclip"></i> Attach File (optional)</label>

                        <?php if ($is_submitted): ?>
                            <div class="form-control-plaintext">
                                <?php if (empty($attachments)): ?>
                                    No files attached.
                                <?php else: ?>
                                    <?php foreach ($attachments as $att): ?>
                                        <?php $fp = htmlspecialchars($att['file_path']); ?>
                                        <div><a href="<?php echo htmlspecialchars('/uploads/attempts/' . $att['file_path']); ?>" target="_blank" rel="noopener noreferrer"><?php echo $fp; ?></a></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <input type="file" name="answer_file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        <?php endif; ?>

                        <?php if (!empty($attachments)): ?>
                            <div class="mt-2 small text-muted">
                                Existing attachments:
                                <ul class="mb-0">
                                    <?php foreach ($attachments as $att): 
                                        $fp = htmlspecialchars($att['file_path']);
                                        $mime = htmlspecialchars($att['mime_type']);
                                        $hash = htmlspecialchars($att['hash']);
                                        $created = date('Y-m-d H:i:s', (int)$att['created_at']);
                                    ?>
                                        <li>
                                            <a href="<?php echo htmlspecialchars('/uploads/attempts/' . $att['file_path']); ?>" target="_blank" rel="noopener noreferrer"><?php echo $fp; ?></a>
                                            <span class="text-muted small"> (<?php echo $mime ?: 'unknown'; ?>, <?php echo $created; ?>)</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="mt-1 small text-muted">Files saved to: <?php echo htmlspecialchars($upload_dir); ?></div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <?php if ($is_submitted): ?>
                            <div class="text-success small">This attempt has been submitted and is read-only.</div>
                        <?php else: ?>
                            <button type="submit" name="save_draft" class="btn btn-secondary">
                                <i class="fa fa-save"></i> Save Draft
                            </button>

                            <button type="submit" name="submit_answer_btn" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Submit Answer
                            </button>
                        <?php endif; ?>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<br>

<?php
/* TELEMETRY */
$tele_event = "attempt_open";
if (isset($_GET['msg'])) {
    $decoded = base64_decode($_GET['msg']);
    if (stripos($decoded, 'Draft saved') !== false) {
        $tele_event = "attempt_saved";
    } elseif (stripos($decoded, 'submitted') !== false) {
        $tele_event = "attempt_submitted";
    }
}

/* Ensure telemetry difficulty uses canonical textual difficulty */
$tele_payload = array(
    "variant_id" => $variant_id,
    "attempt_id" => isset($attempt_id) ? $attempt_id : 0,
    "difficulty" => isset($difficulty) ? $difficulty : 'unknown',
    "level"      => $level,
    "attachments_count" => count($attachments)
);

$payload = json_encode($tele_payload);
$payloadEsc = mysqli_real_escape_string($coni, $payload);
$teleSql = "
    INSERT INTO problem_telemetry (user_login, event_type, payload, occurred_at)
    VALUES (
        '" . mysqli_real_escape_string($coni, $user_login) . "',
        '{$tele_event}',
        '{$payloadEsc}',
        UNIX_TIMESTAMP()
    )
";
if (!$coni->query($teleSql)) {
    error_log("Telemetry insert failed: " . $coni->error . " -- SQL: " . $teleSql);
}
?>

</div><!-- container -->
</div><!-- content-wrapper -->

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var submitBtn = document.querySelector("button[name='submit_answer_btn']");
    var form = document.getElementById("attemptForm");
    var submitHidden = document.getElementById("submit_answer_hidden");

    if (submitBtn && form && submitHidden) {
        submitBtn.addEventListener("click", function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Submit Answer?",
                text: "You cannot edit after submission.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, submit",
                cancelButtonText: "Cancel"
            }).then(function(result) {
                if (result.isConfirmed) {
                    submitHidden.value = "1";
                    form.submit();
                }
            });
        }, false);
    }
});
</script>

<?php require_once('../platformFooter.php'); ?>

</div><!-- layout-page -->

<?php ob_end_flush(); ?>
