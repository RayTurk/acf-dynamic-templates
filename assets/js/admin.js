(function($) {
    'use strict';

    $(function() {

        // Handle template import
        $('#acfdt-templates-grid').on('click', '.acfdt-import-btn', function(e) {
            e.preventDefault();

            var $button = $(this);
            var templateId = $button.data('template-id');
            var $status = $button.parent().find('.acfdt-import-status');

            if ($button.hasClass('disabled') || $button.prop('disabled')) {
                return;
            }

            $.ajax({
                url: acfdt_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'acfdt_import_template',
                    nonce: acfdt_ajax.nonce,
                    template_id: templateId
                },
                beforeSend: function() {
                    $button.prop('disabled', true);
                    $status.text(acfdt_ajax.importing_text).removeClass('error success').addClass('importing').show();
                },
                success: function(response) {
                    if (response.success) {
                        $button.text(acfdt_ajax.imported_text).addClass('button-secondary').removeClass('button-primary');
                        $status.text(response.data.message).removeClass('importing error').addClass('success');
                    } else {
                        $button.prop('disabled', false);
                        $status.text(response.data.message).removeClass('importing success').addClass('error');
                    }
                },
                error: function() {
                    $button.prop('disabled', false);
                    $status.text('An unexpected error occurred.').removeClass('importing success').addClass('error');
                }
            });
        });

        // Handle shortcode copy to clipboard
        $('#acfdt-templates-grid').on('click', '.acfdt-copy-shortcode', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $code = $button.siblings('code');
            var textToCopy = $code.text();

            if (navigator.clipboard) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    var originalText = $button.text();
                    $button.text('Copied!');
                    setTimeout(function() {
                        $button.text(originalText);
                    }, 2000);
                });
            } else {
                // Fallback for older browsers
                var $temp = $('<input>');
                $('body').append($temp);
                $temp.val(textToCopy).select();
                document.execCommand('copy');
                $temp.remove();
                var originalText = $button.text();
                $button.text('Copied!');
                setTimeout(function() {
                    $button.text(originalText);
                }, 2000);
            }
        });

    });

})(jQuery);