<script>
  jQuery(document).ready(function($) {
    // Template import functionality
    $('.acfdt-import-btn').on('click', function (e) {
      e.preventDefault();

      var $button = $(this);
      var template = $button.data('template');
      var originalText = $button.text();

      $button.text('Importing...').prop('disabled', true);

      $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
          action: 'acfdt_import_template',
          template: template,
          nonce: acfdt_admin.nonce
        },
        success: function (response) {
          if (response.success) {
            $button.text('✓ Imported').addClass('button-success');

            // Show success message
            var $notice = $('<div class="notice notice-success is-dismissible"><p>' +
              response.message + '</p></div>');
            $('.wrap h1').after($notice);

            // Auto-dismiss after 3 seconds
            setTimeout(function () {
              $notice.fadeOut();
            }, 3000);
          } else {
            $button.text(originalText).prop('disabled', false);

            // Show error message
            var $notice = $('<div class="notice notice-error is-dismissible"><p>' +
              response.message + '</p></div>');
            $('.wrap h1').after($notice);
          }
        },
        error: function () {
          $button.text(originalText).prop('disabled', false);
          alert('An error occurred. Please try again.');
        }
      });
    });

  // Copy shortcode to clipboard
  $('.acfdt-shortcode-preview code').on('click', function() {
        var $code = $(this);
  var text = $code.text();

  // Create temporary input
  var $temp = $('<input>');
    $('body').append($temp);
    $temp.val(text).select();
    document.execCommand('copy');
    $temp.remove();

    // Visual feedback
    var originalText = $code.text();
    $code.text('✓ Copied!').css('background', '#4CAF50').css('color', 'white');

    setTimeout(function() {
      $code.text(originalText).css('background', '').css('color', '');
        }, 1500);
    });
});
</script>