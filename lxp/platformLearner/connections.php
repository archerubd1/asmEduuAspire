<?php
/**
 * Astraal LXP – Learner Connections (Multi-Moderation Safe)
 * PHP 5.4 / MySQL 5.x compatible
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

$phx_user_id = isset($_SESSION['phx_user_id']) ? (int)$_SESSION['phx_user_id'] : 0;

/**
 * Load learner connections indexed by platform
 */
$connections = array();

if ($phx_user_id > 0) {
    $q = "
        SELECT platform, profile_url, status, review_note
        FROM learner_connections
        WHERE learner_id = $phx_user_id
        ORDER BY updated_on DESC
    ";
    $r = mysqli_query($coni, $q);
    while ($row = mysqli_fetch_assoc($r)) {
        if (!isset($connections[$row['platform']]) || $row['status'] === 'pending') {
            $connections[$row['platform']] = $row;
        }
    }
}

function renderPlatform($name, $icon, $connections) {

    $linked = isset($connections[$name]);
    $status = $linked ? $connections[$name]['status'] : '';
    $url    = $linked ? $connections[$name]['profile_url'] : '';
    $note   = $linked ? $connections[$name]['review_note'] : '';
    ?>
    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div class="d-flex align-items-center">
            <img src="<?php echo $icon; ?>" height="28" class="me-3">
            <div>
                <h6 class="mb-0 fw-semibold"><?php echo htmlspecialchars($name); ?></h6>
                <small class="text-muted">
                    <?php
                    if (!$linked) {
                        echo 'Not linked';
                    } elseif ($status === 'pending') {
                        echo 'Under review';
                    } elseif ($status === 'rejected') {
                        echo 'Rejected'.($note ? ' – '.htmlspecialchars($note) : '');
                    } else {
                        echo 'Linked: '.htmlspecialchars($url);
                    }
                    ?>
                </small>
            </div>
        </div>

        <?php if ($linked && $status === 'approved'): ?>
            <button class="btn btn-sm btn-outline-danger"
                onclick="unlinkConnection('<?php echo $name; ?>')">
                Unlink
            </button>

        <?php elseif ($linked && $status === 'pending'): ?>
            <button class="btn btn-sm btn-outline-secondary" disabled>
                Pending
            </button>

        <?php elseif ($linked && $status === 'rejected'): ?>
            <button class="btn btn-sm btn-outline-warning"
                onclick="linkConnection('<?php echo $name; ?>')">
                Re-submit
            </button>

        <?php else: ?>
            <button class="btn btn-sm btn-outline-primary"
                onclick="linkConnection('<?php echo $name; ?>')">
                Connect
            </button>
        <?php endif; ?>
    </div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="row g-4">

<!-- LEFT -->
<div class="col-md-6 col-12">
<div class="card shadow-sm border-0 h-100">
<h5 class="card-header fw-semibold bg-light">Creator & Community Platforms</h5>
<div class="card-body pb-1">
<p class="text-muted small mb-4">Showcase your creative or collaborative profiles.</p>

<?php
$left = array(
    array('YouTube','../assets/img/icons/brands/youtube.png'),
    array('GitHub','../assets/img/icons/brands/github.png'),
    array('Twitch','../assets/img/icons/brands/twitch.png'),
    array('Spotify','../assets/img/icons/brands/spoitfy.jpg'),
    array('TikTok','../assets/img/icons/brands/tiktok.png')
);
foreach ($left as $p) renderPlatform($p[0], $p[1], $connections);
?>
</div>
</div>
</div>

<!-- RIGHT -->
<div class="col-md-6 col-12">
<div class="card shadow-sm border-0 h-100">
<h5 class="card-header fw-semibold bg-light">Social & Messaging Accounts</h5>
<div class="card-body pb-1">
<p class="text-muted small mb-4">Connect your communication and social platforms.</p>

<?php
$right = array(
    array('Facebook','../assets/img/icons/brands/facebook.png'),
    array('Instagram','../assets/img/icons/brands/instagram.png'),
    array('LinkedIn','../assets/img/icons/brands/linkedin.png'),
    array('Slack','../assets/img/icons/brands/slack.png'),
    array('Discord','../assets/img/icons/brands/discord.png'),
    array('Twitter','../assets/img/icons/brands/twitter.png')
);
foreach ($right as $p) renderPlatform($p[0], $p[1], $connections);
?>
</div>
</div>
</div>

</div>

<script>
function linkConnection(platform) {
    Swal.fire({
        title: 'Paste ' + platform + ' profile URL',
        input: 'url',
        showCancelButton: true,
        allowOutsideClick: false,
        preConfirm: function (url) {
            if (!url) {
                Swal.showValidationMessage('URL required');
                return false;
            }
            return url;
        }
    }).then(function (res) {
        if (!res.isConfirmed) return;

        var fd = new FormData();
        fd.append('platform', platform);
        fd.append('url', res.value);

        fetch('save_connections.php', {
            method: 'POST',
            body: fd
        })
        .then(function (r) { return r.json(); })
        .then(function (j) {
            if (j.status === 'success') {
                Swal.fire('Submitted', 'Link sent for review', 'success')
                .then(function () {
                    window.location.href = 'learner-360profile.php';
                });
            } else {
                Swal.fire('Error', j.message, 'error');
            }
        });
    });
}

function unlinkConnection(platform) {
    Swal.fire({
        icon: 'warning',
        title: 'Unlink ' + platform + '?',
        showCancelButton: true
    }).then(function (res) {
        if (!res.isConfirmed) return;

        var fd = new FormData();
        fd.append('platform', platform);
        fd.append('url', '');

        fetch('save_connections.php', {
            method: 'POST',
            body: fd
        })
        .then(function (r) { return r.json(); })
        .then(function (j) {
            if (j.status === 'success') {
                Swal.fire('Removed', 'Connection deleted', 'success')
                .then(function () {
                    window.location.href = 'learner-360profile.php';
                });
            } else {
                Swal.fire('Error', j.message, 'error');
            }
        });
    });
}
</script>
