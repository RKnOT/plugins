<?php
class RKN_OT_Menu_Page_3 {
    
    public function __construct() {
        // Die AJAX-Action registrieren, aber nur, wenn der Benutzer im Admin-Bereich ist
        //add_action('wp_ajax_rkn_ot_ajax_action', array($this, 'handle_ajax_request'));
    }

    public function render_page() {
        echo '<h1>Untermenü 3</h1>';
        echo '<p>Dies ist die dritte Unterseite.</p>';
        $this->render_button();
    }

    private function render_button() {
        echo '<button id="rkn_ot_button">Klicke mich</button>';
        echo '<div id="rkn_ot_response"></div>'; // Hier wird die Antwort angezeigt
        $this->enqueue_js();
    }

    private function enqueue_js() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
     
            document.getElementById('rkn_ot_button').addEventListener('click', function() {
           
           alert('jlkjl')
            // Erstelle eine AJAX-Anfrage
            var xhr = new XMLHttpRequest();
            xhr.open('POST', ajaxurl, true);  // `ajaxurl` ist eine globale JS-Variable in WP, die auf admin-ajax.php verweist
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('rkn_ot_response').innerHTML = xhr.responseText;  // Antwort anzeigen
                } else {
                    document.getElementById('rkn_ot_response').innerHTML = 'Ein Fehler ist aufgetreten.';
                }
            };
            
            xhr.send('action=rkn_ot_ajax_action');  // Aktion senden
        });
    });
        </script>
        <?php
    }

    // Die PHP-Funktion, die vom AJAX aufgerufen wird
    public function handle_ajax_request() {
        // Hier kannst du beliebige Logik einfügen
        echo 'Der Button wurde geklickt und dies ist die Antwort von der PHP-Funktion in Menüpunkt 3.';
        wp_die();  // Beendet das AJAX-Handling korrekt
    }
}

$rkn_ot_menu_page_3 = new RKN_OT_Menu_Page_3();
$rkn_ot_menu_page_3->render_page();
