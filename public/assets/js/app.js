/**
 * @author Elton Jain
 *
 */
(function ($) {
  const ACCESS_TOKEN = $('#_token').attr('content');
  // List of APIs
  const API = {
    backup: '/api/backup',
    testSSH: '/api/test/ssh',
  };

  // Short hands for preventing redunant DOM select
  const UI = {
    $b: $('body'),
    // $d: $(document),
  };

  // Iniial Setup
  setup();
  bindEvents();


  function setup() {
    $.ajaxSettings = $.extend($.ajaxSettings, {
      headers: {
        'X-CSRF-TOKEN': ACCESS_TOKEN,
      }
    });
  }

  function bindEvents() {
    // Test SSH connection with given info
    // Only on Detail page
    UI.$b.on('click', '#btnTestConnectionSite', function () {
      const $btn = $(this);
      const $form = $btn.parents('form');
      const form = $form[0];
      const apidata = {
        ssh_address: form.ssh_address.value,
        ssh_username: form.ssh_username.value,
        ssh_password: form.ssh_password.value,
        ssh_path: form.ssh_path.value,
      };

      if(!(form.ssh_address.value && form.ssh_username.value && form.ssh_password.value && form.ssh_path.value)) {
        alert('Please enter valid data. All fields are mandatory.');
        return false;
      }

      $.ajax({
        type: 'POST',
        url: API.testSSH,
        data: JSON.stringify(apidata),
        contentType: 'application/json',
        success: function(response){
          console.log(response);
          if(response.status === true) {
            // Update last backup date
            alert(response.message);
          } else {
            alert("There was some error: " + response.message);
          }
        },
        error: function(xhr, type){
          alert('Ajax error: ' + xhr.statusText);
        }
      })

      return false;
    });

    // Backup specific site based on it's configuration
    UI.$b.on('click', '.btn-backup-site', function () {
      const $btn = $(this);
      const siteId = $btn.data('site');
      $btn.addClass('loading');
      $.ajax({
        type: 'POST',
        url: API.backup,
        data: JSON.stringify({ id: siteId }),
        contentType: 'application/json',
        success: function(response){
          $btn.removeClass('loading');
          if(response.status === true) {
            // Update last backup date
            alert(response.message);
          } else {
            alert("There was some error " + response.message);
          }
        },
        error: function(xhr, type){
          $btn.removeClass('loading');
          alert('Ajax error: ' + xhr.statusText);
        }
      });

      return false;
    });
  }
})(Zepto);
