<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

$phx_user_id    = isset($_SESSION['phx_user_id']) ? (int)$_SESSION['phx_user_id'] : 0;
$phx_user_login = isset($_SESSION['phx_user_login']) ? $_SESSION['phx_user_login'] : '';
$phx_user_name  = isset($_SESSION['phx_user_name']) ? $_SESSION['phx_user_name'] : '';

if ($phx_user_id <= 0) {
    header('Location: ../../login.php');
    exit;
}

if (!isset($coni) || !$coni) {
    echo "DB connection not available. Check config.php";
    exit;
}

/* Load motivation items */
$construct_codes = array(
    'autonomy','competence','relatedness',
    'mastery','performance','avoidance','growth',
    'epistemic_curiosity','perceptual_curiosity','deprivation_curiosity','sensory_curiosity',
    'recog_public','recog_private','recog_peer','recog_badge','recog_mentor'
);
$esc_codes = array();
foreach($construct_codes as $c) $esc_codes[] = "'" . mysqli_real_escape_string($coni, $c) . "'";
$in_list = implode(',', $esc_codes);

$sql = "
    SELECT si.id AS item_id, si.code AS item_code, si.prompt, si.reverse_flag, mc.code AS construct_code, mc.display_name AS construct_name
    FROM 360_lm_survey_item si
    JOIN 360_lm_motivation_construct mc ON si.construct_id = mc.id
    WHERE mc.code IN ($in_list)
    ORDER BY mc.id, si.id
";

$res = mysqli_query($coni, $sql);
if (!$res) {
    echo "DB error loading items: " . mysqli_error($coni);
    exit;
}
$items = array();
while ($row = mysqli_fetch_assoc($res)) $items[] = $row;
$totalItems = count($items);

/* --- NEW: Group items by construct for tabbed layout --- */
$items_by_construct = [];
foreach ($items as $item) {
    $code = $item['construct_code'];
    $name = $item['construct_name'];
    if (!isset($items_by_construct[$code])) {
        $items_by_construct[$code] = [
            'name' => $name,
            'items' => []
        ];
    }
    $items_by_construct[$code]['items'][] = $item;
}
$construct_keys = array_keys($items_by_construct);
/* -------------------------------------------------------- */

/* Example: pick an H5P microtask (content_id). Create H5P content in 360_lm_h5p_content and use its id here.
    Or list multiple and pick one per archetype later. We'll use content_id=1 as example. */
$h5p_example_content_id = 1;

$page = "profile";
require_once('learnerHead_Nav2.php');
?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body{font-family: Arial, Helvetica, sans-serif; background:#f7f9fc; color:#222;}
        /* Increased max-width for better tab display */
        .container{max-width:1100px;margin:28px auto;padding:18px;background:#fff;border-radius:6px;box-shadow:0 6px 20px rgba(0,0,0,0.06);}
        .item{padding:12px;border-radius:6px;margin:8px 0;background:#fbfdff;border:1px solid #eef3fb;}
        .prompt{font-size:15px;margin-bottom:8px}
        .likert{display:inline-block}
        .btn{display:inline-block;padding:8px 12px;border-radius:4px;border:0;background:#1976d2;color:#fff;cursor:pointer}
        .btn.secondary{background:#6c757d}
        .progressbar{height:12px;background:#e9ecef;border-radius:8px;overflow:hidden;margin-bottom:12px}
        .progressbar > i{display:block;height:100%;background:#0d6efd;width:100%} /* Always full width in tabbed view */
        .footer{margin-top:16px;text-align:right}
        .hidden{display:none}
        /* constructHeader is no longer needed inside the item list, but keeping its style for context */
        .constructHeader{font-weight:bold;margin-top:12px;color:#0b5ed7} 
        .h5p-launch{display:inline-block;margin-right:10px;padding:6px 10px;background:#0b5ed7;color:#fff;border-radius:4px;cursor:pointer}
        .small{font-size:13px;color:#666}

        /* --- Bootstrap-like Tabs Styling (Minimal) --- */
        .nav-tabs { border-bottom: 1px solid #dee2e6; margin-bottom: 15px; }
        .nav-item { margin-bottom: -1px; }
        .nav-link { 
            display: block; 
            padding: 0.5rem 1rem; 
            color: #495057; 
            background-color: transparent; 
            border: 1px solid transparent; 
            border-top-left-radius: 0.25rem; 
            border-top-right-radius: 0.25rem; 
            cursor: pointer;
            text-wrap: nowrap;
        }
        .nav-link.active {
            color: #0d6efd;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .tab-content > .tab-pane { padding: 10px; border-radius: 0.25rem; }
    </style>


<div class="layout-page">
    <?php require_once('learnersNav.php'); ?>
    
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">


            <h3>Learning Motivation — Quick Assessment (Tabbed View)</h3>
            <p class="small">This assessment captures what motivates you — growth, mastery, recognition, curiosity, and more. Total items: <?php echo $totalItems;?>.</p>

            <div style="margin:10px 0">
                <span class="h5p-launch" id="launchH5PBtn">▶ Try a short micro-challenge</span>
                <span class="small">Interactive microtasks help us capture how you behave — this one is optional but recommended.</span>
            </div>

            <form id="motivationForm" method="post" action="survey_submit.php">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo htmlspecialchars($phx_user_id); ?>">
                <div class="tab-container">
                    <ul class="nav nav-tabs" id="motivationTabs" role="tablist">
                        <?php $is_first = true; ?>
                        <?php foreach ($items_by_construct as $code => $construct_data): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $is_first ? 'active' : ''; ?>" 
                                        id="<?php echo $code; ?>-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#tab-<?php echo $code; ?>" 
                                        type="button" role="tab" 
                                        aria-controls="tab-<?php echo $code; ?>" 
                                        aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>">
                                    <?php echo htmlspecialchars($construct_data['name']); ?>
                                </button>
                            </li>
                            <?php $is_first = false; ?>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content" id="motivationTabsContent">
                        <?php $is_first = true; ?>
                        <?php foreach ($items_by_construct as $code => $construct_data): ?>
                            <div class="tab-pane fade <?php echo $is_first ? 'show active' : ''; ?>" 
                                id="tab-<?php echo $code; ?>" 
                                role="tabpanel" 
                                aria-labelledby="<?php echo $code; ?>-tab">
                                
                                <div class="constructHeader"><?php echo htmlspecialchars($construct_data['name']); ?> Items</div>
                                
                                <?php foreach ($construct_data['items'] as $it): ?>
                                    <?php
                                        $itemId = (int)$it['item_id'];
                                        $prompt = htmlspecialchars($it['prompt']);
                                    ?>
                                    <div class="item">
                                        <div class="prompt"><?php echo $prompt; ?></div>
                                        <div class="likert" data-item="<?php echo $itemId; ?>">
                                            <?php for ($k=1;$k<=5;$k++): ?>
                                                <?php $rid = 'r_'.$itemId.'_'.$k; ?>
                                                <label style="margin-right:10px;">
                                                    <input type="radio" name="response[<?php echo $itemId; ?>]" id="<?php echo $rid; ?>" value="<?php echo $k; ?>"> <?php echo $k; ?>
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                            <?php $is_first = false; ?>
                        <?php endforeach; ?>
                    </div>
                </div> <div class="footer">
                    <button type="button" class="btn secondary" id="saveProgressBtn" style="margin-right:8px">Save & Continue Later</button>
                    <button type="button" class="btn" id="submitAllBtn">Submit Assessment</button>
                </div>
            </form>

            <div style="margin-top:12px;color:#666;font-size:13px">Tip: microuyjykuikuikulliolo-challenges improve the accuracy of your motivation profile; they are optional.</div>
        </div>

        <div id="hiddenH5PWrapper" class="hidden"></div>

        <script>
        /* ---- SweetAlert2 + Tabbed logic (Simplified) ---- */
        (function(){
            var totalItems = <?php echo (int)$totalItems; ?>;
            var submitAllBtn = document.getElementById('submitAllBtn');
            var saveBtn = document.getElementById('saveProgressBtn');

            // Function to check if all items are answered
            function areAllAnswered() {
                var form = document.getElementById('motivationForm');
                var radioGroups = {};
                var radios = form.querySelectorAll('input[type=radio]');
                
                for (var i=0; i<radios.length; i++) {
                    var name = radios[i].name;
                    if (!radioGroups[name]) radioGroups[name] = false;
                    if (radios[i].checked) radioGroups[name] = true;
                }

                var answeredCount = Object.keys(radioGroups).length;
                var totalExpected = totalItems;
                var incompleteCount = 0;

                for (var nm in radioGroups) { 
                    if (!radioGroups[nm]) { 
                        incompleteCount++;
                    } 
                }

                return { allAnswered: incompleteCount === 0, incompleteCount: incompleteCount };
            }

            submitAllBtn.addEventListener('click', function(){
                var check = areAllAnswered();

                if (!check.allAnswered) {
                    Swal.fire({
                        title: 'Unanswered items',
                        text: 'You have ' + check.incompleteCount + ' unanswered item(s). Do you want to submit anyway?',
                        icon: 'warning', 
                        showCancelButton: true, 
                        confirmButtonText: 'Submit Anyway', 
                        cancelButtonText: 'Review Items',
                    }).then(function(result){
                        if (result.value) handleSubmit(); // user confirmed
                    });
                    return;
                }

                handleSubmit();
            }, false);

            function handleSubmit() {
                // Collect responses
                var form = document.getElementById('motivationForm');
                var inputs = form.querySelectorAll('input[type=radio]');
                var payload = {};
                payload.user_id = form.querySelector('input[name=user_id]').value;
                payload.responses = {};
                for (var i=0;i<inputs.length;i++){
                    if (inputs[i].checked) {
                        var nm = inputs[i].name;
                        var m = nm.match(/^response\[(\d+)\]$/);
                        if (m) payload.responses[m[1]] = parseInt(inputs[i].value,10);
                    }
                }

                Swal.fire({
                    title: 'Submit your responses?',
                    text: 'You can re-take this assessment later if needed.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel'
                }).then(function(result){
                    if (!result.value) return;
                    // show loading
                    Swal.fire({ title: 'Submitting...', allowOutsideClick: false, didOpen: function(){ Swal.showLoading(); } });
                    // AJAX POST
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'survey_submit.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                    xhr.onreadystatechange = function(){
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                try {
                                    var res = JSON.parse(xhr.responseText);
                                    if (res.status && res.status === 'ok') {
                                        Swal.fire({ title: 'Thanks!', text: 'Your Learning Motivation profile has been recorded.', icon: 'success' }).then(function(){ window.location.href='../../profile.php'; });
                                    } else {
                                        Swal.fire('Error', 'Server error: ' + (res.message || 'unknown'), 'error');
                                    }
                                } catch(e) {
                                    Swal.fire('Error','Unexpected server response','error');
                                }
                            } else {
                                Swal.fire('Error','Submission failed: HTTP ' + xhr.status,'error');
                            }
                        }
                    };
                    xhr.send(JSON.stringify(payload));
                });
            }

            saveBtn.addEventListener('click', function(){
                var form = document.getElementById('motivationForm');
                var inputs = form.querySelectorAll('input[type=radio]');
                var payload = {};
                payload.user_id = form.querySelector('input[name=user_id]').value;
                payload.responses = {};
                for (var i=0;i<inputs.length;i++){
                    if (inputs[i].checked) {
                        var nm = inputs[i].name;
                        var m = nm.match(/^response\[(\d+)\]$/);
                        if (m) payload.responses[m[1]] = parseInt(inputs[i].value,10);
                    }
                }
                payload.draft = 1;

                Swal.fire({ title: 'Saving...', allowOutsideClick:false, didOpen: function(){ Swal.showLoading(); } });
                var xhr = new XMLHttpRequest();
                xhr.open('POST','survey_submit.php',true);
                xhr.setRequestHeader('Content-Type','application/json;charset=UTF-8');
                xhr.onreadystatechange = function(){
                    if (xhr.readyState===4) {
                        if (xhr.status===200) {
                            Swal.fire('Saved','Progress saved. You can return later.','success');
                        } else {
                            Swal.fire('Failed','Failed to save progress.','error');
                        }
                    }
                };
                xhr.send(JSON.stringify(payload));
            }, false);

            // H5P launcher
            document.getElementById('launchH5PBtn').addEventListener('click', function(){
                var contentId = <?php echo (int)$h5p_example_content_id;?>;
                // open SweetAlert2 modal with iframe
                var iframeUrl = 'h5p_view.php?content_id=' + contentId;
                Swal.fire({
                    title: 'Micro-challenge',
                    html: '<div style="width:100%;height:520px"><iframe id="h5p_iframe" src="'+iframeUrl+'" style="width:100%;height:100%;border:0;border-radius:6px;" allow="autoplay; fullscreen"></iframe></div>',
                    width: 900,
                    showCancelButton: true,
                    confirmButtonText: 'Close',
                    cancelButtonText: 'Close',
                    showCloseButton: true,
                    allowOutsideClick: false
                });
            }, false);

            // postMessage listener to capture H5P events from iframe (only same-origin)
            window.addEventListener('message', function(e){
                try {
                    // Basic origin check: accept only same origin
                    if (e.origin !== window.location.origin) return;
                    var data = (typeof e.data === 'string') ? JSON.parse(e.data) : e.data;
                    if (!data || !data.h5p_event) return;
                    // Build payload
                    var payload = {
                        h5p_content_id: data.content_id || null,
                        event_name: data.h5p_event,
                        event_props: data.h5p_props || {},
                        ts: (new Date()).toISOString()
                    };
                    // POST to server endpoint
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'h5p_event_ingest.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                    xhr.onreadystatechange = function(){
                        if (xhr.readyState === 4) {
                            // optional: log or show a small toast if needed
                            // console.log('H5P event stored', xhr.status);
                        }
                    };
                    xhr.send(JSON.stringify(payload));
                } catch(err) {
                    // ignore silently
                    // console.log('postMessage handler error', err);
                }
            }, false);

        })();
        </script>
        <?php require_once('../platformFooter.php'); ?>