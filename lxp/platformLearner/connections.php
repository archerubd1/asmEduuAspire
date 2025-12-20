<?php
/**
 *  Astraal LXP – Learner Connections (Two-Column Legacy)
 * Compatible with PHP 5.4 / MySQL 5.x (UwAmp / GoDaddy)
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php');
require_once('../../session-guard.php');

$phx_user_id = isset($_SESSION['phx_user_id']) ? (int)$_SESSION['phx_user_id'] : 0;
$savedConnections = array();

if ($phx_user_id > 0) {
  $q = "SELECT platform, profile_url FROM learner_connections WHERE learner_id = $phx_user_id";
  $r = mysqli_query($coni, $q);
  if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
      $savedConnections[$row['platform']] = $row['profile_url'];
    }
  }
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="row g-4">

  <!-- ===== LEFT SIDE : Creator / Community ===== -->
  <div class="col-md-6 col-12">
    <div class="card shadow-sm border-0 h-100">
      <h5 class="card-header fw-semibold bg-light">Creator & Community Platforms</h5>
      <div class="card-body pb-1">
        <p class="text-muted small mb-4">Showcase your creative or collaborative profiles.</p>
<?php
$left = array(
  array('YouTube','../assets/img/icons/brands/youtube.png','#FF0000'),
  array('GitHub','../assets/img/icons/brands/github.png','#333333'),
  array('Twitch','../assets/img/icons/brands/twitch.png','#9146FF'),
  array('Spotify','../assets/img/icons/brands/spoitfy.jpg','#1DB954'),
  array('TikTok','../assets/img/icons/brands/tiktok.png','#000000')
);
foreach ($left as $p) {
  $name=$p[0]; $icon=$p[1]; $color=$p[2];
  $linked=isset($savedConnections[$name]) && $savedConnections[$name]!='';
  $url=$linked?$savedConnections[$name]:'';
  ?>
  <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
    <div class="d-flex align-items-center">
      <img src="<?php echo $icon; ?>" height="28" class="me-3" alt="<?php echo $name; ?>">
      <div>
        <h6 class="mb-0 fw-semibold"><?php echo $name; ?></h6>
        <small class="text-muted"><?php echo $linked?'Linked: '.htmlspecialchars($url):'Not linked'; ?></small>
      </div>
    </div>
    <?php if($linked){ ?>
      <button type="button" class="btn btn-sm btn-outline-danger" onclick="unlinkConnection('<?php echo $name; ?>')">Unlink</button>
    <?php } else { ?>
      <button type="button" class="btn btn-sm btn-outline-primary" onclick="linkConnection('<?php echo $name; ?>')">Connect</button>
    <?php } ?>
  </div>
  <?php
}
?>
      </div>
    </div>
  </div>

  <!-- ===== RIGHT SIDE : Social / Messaging ===== -->
  <div class="col-md-6 col-12">
    <div class="card shadow-sm border-0 h-100">
      <h5 class="card-header fw-semibold bg-light">Social & Messaging Accounts</h5>
      <div class="card-body pb-1">
        <p class="text-muted small mb-4">Connect your communication and social platforms.</p>
<?php
$right = array(
  array('Facebook','../assets/img/icons/brands/facebook.png','#1877F2'),
  array('Instagram','../assets/img/icons/brands/instagram.png','#E1306C'),
  array('LinkedIn','../assets/img/icons/brands/linkedin.png','#0A66C2'),
  array('Slack','../assets/img/icons/brands/slack.png','#4A154B'),
  array('Discord','../assets/img/icons/brands/discord.png','#5865F2'),
  array('Twitter','../assets/img/icons/brands/twitter.png','#1DA1F2')
);
foreach ($right as $p) {
  $name=$p[0]; $icon=$p[1]; $color=$p[2];
  $linked=isset($savedConnections[$name]) && $savedConnections[$name]!='';
  $url=$linked?$savedConnections[$name]:'';
  ?>
  <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
    <div class="d-flex align-items-center">
      <img src="<?php echo $icon; ?>" height="28" class="me-3" alt="<?php echo $name; ?>">
      <div>
        <h6 class="mb-0 fw-semibold"><?php echo $name; ?></h6>
        <small class="text-muted"><?php echo $linked?'Linked: '.htmlspecialchars($url):'Not linked'; ?></small>
      </div>
    </div>
    <?php if($linked){ ?>
      <button type="button" class="btn btn-sm btn-outline-danger" onclick="unlinkConnection('<?php echo $name; ?>')">Unlink</button>
    <?php } else { ?>
      <button type="button" class="btn btn-sm btn-outline-primary" onclick="linkConnection('<?php echo $name; ?>')">Connect</button>
    <?php } ?>
  </div>
  <?php
}
?>
      </div>
    </div>
  </div>

</div>

<script>
function linkConnection(platform){
  Swal.fire({
    title:'Link '+platform+' account',
    input:'url',
    inputPlaceholder:'https://'+platform.toLowerCase()+'.com/yourprofile',
    showCancelButton:true,
    confirmButtonText:'Save Link',
    allowOutsideClick:false,
    preConfirm:function(url){
      if(!url) return Swal.showValidationMessage('Enter URL');
      if(!/^https?:\/\//.test(url)) return Swal.showValidationMessage('URL must start with http:// or https://');
      return url;
    }
  }).then(function(result){
    if(result.isConfirmed){
      var fd=new FormData();
      fd.append('section','connections');
      fd.append('platform',platform);
      fd.append('url',result.value);
      fetch('save_learner_profile.php',{method:'POST',body:fd})
      .then(function(r){return r.json();})
      .then(function(j){
        if(j.status==='success'){
          Swal.fire('Success',platform+' linked successfully.','success')
          .then(function(){location.reload();});
        }else{
          Swal.fire('Error',j.message||'Failed to save link.','error');
        }
      })
      .catch(function(){Swal.fire('Error','Server connection failed.','error');});
    }
  });
}

function unlinkConnection(platform){
  Swal.fire({
    icon:'warning',
    title:'Unlink '+platform+'?',
    text:'This will remove your saved link.',
    showCancelButton:true,
    confirmButtonText:'Yes, Unlink',
    confirmButtonColor:'#d33',
    cancelButtonText:'Cancel'
  }).then(function(res){
    if(res.isConfirmed){
      var fd=new FormData();
      fd.append('section','connections');
      fd.append('platform',platform);
      fd.append('url','');
      fetch('save_learner_profile.php',{method:'POST',body:fd})
      .then(function(r){return r.json();})
      .then(function(j){
        if(j.status==='success'){
          Swal.fire('Unlinked',platform+' removed.','success')
          .then(function(){location.reload();});
        }else{
          Swal.fire('Error',j.message||'Unable to unlink.','error');
        }
      })
      .catch(function(){Swal.fire('Error','Server connection failed.','error');});
    }
  });
}
</script>
