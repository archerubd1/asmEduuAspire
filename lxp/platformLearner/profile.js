document.addEventListener('DOMContentLoaded', function () {

  function showAlert(type, title, text) {
    if (typeof Swal !== 'undefined' && Swal.fire) {
      Swal.fire({
        icon: type,
        title: title,
        text: text,
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false
      });
      return;
    }

    if (typeof swal === 'function') {
      swal(title, text, type);
      return;
    }

    alert(title + "\n" + text);
  }

  function submitForm(id, section, button) {

    var form = document.getElementById(id);
    if (!form) return;

    var data = new FormData(form);

    var endpoint = '';
    if (section === 'account') endpoint = 'save_account.php';
    if (section === 'notifications') endpoint = 'save_notifications.php';
    if (section === 'connections') endpoint = 'save_connections.php';

    fetch(endpoint, {
      method: 'POST',
      body: data
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {

      if (!res || res.status !== 'success') {
        showAlert('error', 'Error', res.message || 'Something went wrong');
        return;
      }

      showAlert('success', 'Saved', res.message || 'Saved successfully');

      // 🔥 HARD, UNSTOPPABLE REDIRECT (NOT DEPENDENT ON ALERT)
      setTimeout(function () {
        if (res.next_step === 'notifications') {
          window.location.href = 'learner-profile-notifications.php';
        }
        else if (res.next_step === 'connections') {
          window.location.href = 'learner-profile-connections.php';
        }
        else if (res.next_step === 'profiling') {
          window.location.href = 'learner-360profile.php';
        }
      }, 800); // short delay so user sees message
    })
    .catch(function () {
      showAlert('error', 'Server Error', 'Unable to reach server');
    });
  }

  // ✅ IMPORTANT: bind on FORM submit, not button click
  var accForm = document.getElementById('formAccountSettings');
  if (accForm) {
    accForm.addEventListener('submit', function (e) {
      e.preventDefault();
      submitForm('formAccountSettings', 'account');
    });
  }

  var notiForm = document.getElementById('notificationsForm');
  if (notiForm) {
    notiForm.addEventListener('submit', function (e) {
      e.preventDefault();
      submitForm('notificationsForm', 'notifications');
    });
  }

  var connForm = document.getElementById('connectionsForm');
  if (connForm) {
    connForm.addEventListener('submit', function (e) {
      e.preventDefault();
      submitForm('connectionsForm', 'connections');
    });
  }

});
