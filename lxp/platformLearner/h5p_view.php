<?php
/**
 * h5p_view.php
 * ---------------------------------------------------------
 * A lightweight wrapper to LOAD and DISPLAY H5P micro-tasks
 * inside an iframe AND post analytic events to parent page.
 *
 * Works even if full H5P runtime is NOT installed.
 * If H5P runtime exists — replace the placeholder with
 * real embed code.
 *
 * Requirements:
 *   - DB table: 360_lm_h5p_content (id, content_name, library, params)
 *   - $coni (mysqli) from ../../config.php
 *   - Same-origin as the parent for postMessage security.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php'); // provides $coni mysqli connection

if (!isset($coni)) {
    die("Database connection not available.");
}

// GET content_id from iframe src
$content_id = isset($_GET['content_id']) ? (int)$_GET['content_id'] : 0;
if ($content_id <= 0) {
    die("Invalid H5P content id.");
}

// Load H5P metadata from DB
$sql = "
    SELECT id, content_name, library, params
    FROM 360_lm_h5p_content
    WHERE id = {$content_id}
    LIMIT 1
";
$res = mysqli_query($coni, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    die("H5P content not found.");
}

$row = mysqli_fetch_assoc($res);
$contentName = htmlspecialchars($row['content_name']);
$paramsJson  = $row['params']; // optional JSON for real runtime
$libraryName = $row['library'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $contentName; ?></title>

<style>
    body, html {
        margin: 0; padding: 0;
        width: 100%; height: 100%;
        font-family: Arial, Helvetica, sans-serif;
        background: #f9f9f9;
    }
    #wrapper {
        padding: 18px;
    }
    #wrapper h3 {
        margin-top: 0;
    }
    .demo-button {
        padding: 10px 16px;
        margin: 6px;
        border: none;
        border-radius: 4px;
        background: #0d6efd;
        color: #fff;
        cursor: pointer;
    }
    .demo-button.red {
        background: #dc3545;
    }
    .demo-button.green {
        background: #198754;
    }
</style>
</head>

<body>

<div id="wrapper">
    <h3><?php echo $contentName; ?></h3>
    <p>
        <small>
            This interactive micro-task sends events directly to the parent window
            so your learning behavior can be analyzed in real time.
        </small>
    </p>

    <!--
      ----------------------------------------------------------------
      ❗ PLACEHOLDER INTERFACE
      ----------------------------------------------------------------
      If you DO NOT yet have H5P runtime installed on your server,
      this placeholder allows testing the analytics flow.

      If you DO HAVE H5P runtime installed, replace EVERYTHING below
      with the official H5P embed code + the event listeners.
      ----------------------------------------------------------------
    -->

    <button class="demo-button green" id="btnStart">▶ Start Activity</button>
    <button class="demo-button" id="btnComplete">✔ Complete</button>
    <button class="demo-button red" id="btnFail">✖ Fail</button>

    <p>
        <small style="color:#666;">
            When H5P runtime is installed, these buttons will be replaced
            by your actual H5P interactive content.
        </small>
    </p>
</div>

<script>
// ------------------------------
// Utility: send event to parent
// ------------------------------
function sendEvent(name, props) {
    var payload = {
        h5p_event: name,
        content_id: <?php echo $content_id; ?>,
        h5p_props: props || {}
    };

    try {
        // Send JSON string for reliability
        window.parent.postMessage(JSON.stringify(payload), window.location.origin);
    } catch (e) {
        // fail silently
    }
}

// ------------------------------
// Demo buttons (for testing now)
// ------------------------------
document.getElementById('btnStart').onclick = function(){
    sendEvent('microtask_start', { mode: 'demo' });
};

document.getElementById('btnComplete').onclick = function(){
    sendEvent('microtask_complete', { score: 95, mode: 'demo' });
};

document.getElementById('btnFail').onclick = function(){
    sendEvent('microtask_failed', { score: 25, mode: 'demo' });
};

// ------------------------------
// If REAL H5P runtime is present:
// Insert listeners here, e.g.:
//
// H5P.externalDispatcher.on('xAPI', function(event){
//     sendEvent('xapi', event.statement);
// });
//
// ------------------------------
</script>
</body>
</html>
