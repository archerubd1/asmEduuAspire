document.addEventListener('DOMContentLoaded', function () {

  // ============================================================
  // 🧩 SWEETALERT FIX: Disable Auto-Close and DOM Propagation
  // ============================================================
  if (typeof swal === 'function' && typeof Swal === 'undefined') {
    (function () {
      var originalSwal = swal;
      swal = function (options, callback) {
        if (typeof options === 'string') options = { title: options };
        if (typeof options !== 'object') options = {};
        // Prevent timer-based auto close
        options.timer = null;
        options.showConfirmButton = true;
        options.allowOutsideClick = false;
        options.allowEscapeKey = false;
        options.confirmButtonText = options.confirmButtonText || 'OK';

        var alertInstance = originalSwal(options, function (isConfirm) {
          if (typeof callback === 'function') callback(isConfirm);
        });

        // Destroy any internal SweetAlert close timer
        if (window.sweetAlertCloseTimer) {
          clearTimeout(window.sweetAlertCloseTimer);
          window.sweetAlertCloseTimer = null;
        }
        return alertInstance;
      };
    })();
  }

  // ============================================================
  // 🧩 ALERT WRAPPER (Hybrid)
  // ============================================================
  function showAlert(type, title, text) {
    // SweetAlert2
    if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
      Swal.fire({
        icon: type,
        title: title,
        text: text,
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: true
      });
      return;
    }

    // SweetAlert1
    if (typeof swal === 'function') {
      swal({
        title: title,
        text: text,
        type: type,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false
      });
      return;
    }

    alert(title + "\n" + text);
  }

  // ============================================================
  // 🧩 Helper: Add Status Label beside Button
  // ============================================================
  function showStatusLabel(button, message, type) {
    if (!button) return;
    var oldLabel = button.parentNode.querySelector('.save-status-label');
    if (oldLabel) oldLabel.remove();

    var label = document.createElement('span');
    label.className = 'save-status-label ms-3 fw-bold';
    label.style.fontSize = '0.9em';
    label.style.color = (type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#6c757d');
    label.textContent = message;
    button.parentNode.appendChild(label);
  }

  // ============================================================
  // 🧩 File Upload Preview
  // ============================================================
  var uploadInput = document.getElementById('upload');
  var uploadedAvatar = document.getElementById('uploadedAvatar');

  if (uploadInput) {
    uploadInput.addEventListener('change', function (e) {
      e.stopPropagation();
      e.preventDefault();
      var file = e.target.files[0];
      if (!file) return;

      if (!/image\/(jpeg|png|gif)/.test(file.type)) {
        showAlert('error', 'Invalid File', 'Only JPG, PNG, or GIF allowed.');
        e.target.value = '';
        return;
      }

      if (file.size > 800 * 1024) {
        showAlert('error', 'Too Large', 'Maximum file size is 800 KB.');
        e.target.value = '';
        return;
      }

      var reader = new FileReader();
      reader.onload = function (ev) { uploadedAvatar.src = ev.target.result; };
      reader.readAsDataURL(file);
    });
  }

  // ============================================================
  // 🧩 Submit Function (with Event Protection)
  // ============================================================
  function submitForm(id, section, button) {
    var form = document.getElementById(id);
    if (!form) return;
    if (event) event.preventDefault();
    if (event) event.stopPropagation();

    var data = new FormData(form);
    data.append('section', section);

    fetch('save_learner_profile.php', { method: 'POST', body: data })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        var sectionName = '';
        if (section === 'account') sectionName = 'Account Information';
        else if (section === 'notifications') sectionName = 'Notification Preferences';
        else if (section === 'connections') sectionName = 'Connections';
        else if (section === 'deactivate') sectionName = 'Account Deactivation';

        if (res.status === 'success') {
          showAlert('success', sectionName + ' Updated', res.message || (sectionName + ' saved successfully.'));
          showStatusLabel(button, '✔ ' + sectionName + ' updated successfully.', 'success');
        } else if (res.status === 'deactivated') {
          showAlert('warning', 'Account Deactivated', 'Your account has been deactivated successfully.');
          showStatusLabel(button, '⚠ Account Deactivated', 'warning');
        } else {
          showAlert('error', 'Error Updating ' + sectionName, res.message || 'Something went wrong.');
          showStatusLabel(button, '✖ Failed to update', 'error');
        }
      })
      .catch(function () {
        showAlert('error', 'Server Error', 'Unable to reach server.');
        showStatusLabel(button, '✖ Server Error', 'error');
      });
  }

  // ============================================================
  // 🧩 Event Binding for Buttons
  // ============================================================
  var acc = document.querySelector('#formAccountSettings button[type="submit"]');
  if (acc) acc.addEventListener('click', function (e) {
    e.preventDefault(); e.stopPropagation();
    submitForm('formAccountSettings', 'account', acc);
  });

  var noti = document.querySelector('#notificationsForm button[type="submit"]');
  if (noti) noti.addEventListener('click', function (e) {
    e.preventDefault(); e.stopPropagation();
    submitForm('notificationsForm', 'notifications', noti);
  });

  var conn = document.querySelector('#connectionsForm button[type="submit"]');
  if (conn) conn.addEventListener('click', function (e) {
    e.preventDefault(); e.stopPropagation();
    submitForm('connectionsForm', 'connections', conn);
  });

  var deac = document.querySelector('#formAccountDeactivation button[type="submit"]');
  if (deac) deac.addEventListener('click', function (e) {
    e.preventDefault(); e.stopPropagation();
    submitForm('formAccountDeactivation', 'deactivate', deac);
  });
});
