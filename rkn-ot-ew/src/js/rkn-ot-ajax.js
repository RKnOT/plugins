jQuery(document).ready(function($) {
    $('#ajaxButton').on('click', function() {
        console.log('Button wurde geklickt!');
        $.ajax({
            url: rknOtAjax.ajaxUrl, // Ajax-URL von wp_localize_script
            type: 'POST',
            data: {
                action: 'rkn_ot_ajax_action', // Der Ajax-Action-Name
                nonce: rknOtAjax.nonce // Nonce zum Schutz vor CSRF
            },
            success: function(response) {
                if (response.success) {
                    $('#response').html('<p>' + response.data.message + '</p>');
                } else {
                    $('#response').html('<p>Ein Fehler ist aufgetreten.</p>');
                }
            },
            error: function() {
                $('#response').html('<p>Ajax-Fehler aufgetreten.</p>');
            }
        });
    });
});
