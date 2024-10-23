jQuery(document).ready(function($) {
    // Buttons mit IDs 'b1', 'b2' und 'b3' verarbeiten
    $('#b1').on('click', function() {
        $.ajax({
            url: myButtonAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'button1_action', // WP Ajax Action
                nonce: myButtonAjax.nonce   // Nonce zur Sicherheit
            },
            success: function(response) {
                if (response.success) {
                    $('#status').html(response.data);
                    //alert(response.data); // Zeige die Antwort der PHP-Funktion
                } else {
                    $('#status').html(response.data);
                }
            }
        });
    });

    $('#b2').on('click', function() {
        $.ajax({
            url: myButtonAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'button2_action', // WP Ajax Action
                nonce: myButtonAjax.nonce   // Nonce zur Sicherheit
            },
            success: function(response) {
                if (response.success) {
                    $('#status').html(response.data);
                    //alert(response.data); // Zeige die Antwort der PHP-Funktion
                } else {
                    $('#status').html(response.data);('Es gab ein Problem.');
                }
            }
        });
    });
    $('#b3').on('click', function() {
        $.ajax({
            url: myButtonAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'button3_action', // WP Ajax Action
                nonce: myButtonAjax.nonce   // Nonce zur Sicherheit
            },
            success: function(response) {
                if (response.success) {
                    document.getElementById("checkbox").checked = false;
                    $('#status').html(response.data);
                } else {
                    $('#status').html(response.data);('Es gab ein Problem.');
                }
            }
        });
    });
});

