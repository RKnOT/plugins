<?php

if (!defined('ABSPATH')) {
    exit;
}


// utils submenu one
function get_all_db_images_path() {
    echo '<div class="updated">';
    echo '<h1>Alle Bilder aus der Datenbank</h1>';
    echo '</div>';

  
    global $wpdb;

    // SQL-Abfrage, um alle Bild-URLs aus der Datenbank zu lesen
    $sql = "SELECT p.ID, p.post_title, pm.meta_value 
            FROM {$wpdb->posts} p 
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
            WHERE p.post_type = 'attachment' 
            AND pm.meta_key = '_wp_attached_file'";
    $results = $wpdb->get_results($sql);
    $count = 0;
    $images_path_array = array();
    $image_path_array = array();
    // Filtere die Bild-URLs und extrahiere die Metadaten
    foreach ($results as $result) {
        $image_path = wp_get_attachment_image_src($result->ID, 'full');
        $image_url = isset($image_path[0]) ? $image_path[0] : '';
        
        // Überprüfe, ob es sich um eine Bild-URL handelt
        if ($image_url && preg_match('/\.(jpg|jpeg|png|gif)$/', $image_url)) {
            $count = $count + 1;
            // Extrahiere die Metadaten des Bildes
            $image_metadata = wp_get_attachment_metadata($result->ID);
            
            // Überprüfe, ob das Bild Metadaten hat
            if ($image_metadata) {
                array_push($image_path_array, $count);
                array_push($image_path_array, $result->ID);
                array_push($image_path_array, $result->post_title);
                $substring = strstr($image_url, 'uploads');
                array_push($image_path_array, $image_url);
                if (isset($image_metadata['original_image'])) {
                    array_push($image_path_array, $image_metadata['original_image']);
                }
                else {
                    array_push($image_path_array, '');
                }
                $image_meta = wp_get_attachment_metadata($result->ID);
                if (array_key_exists('image_meta', $image_meta)) {
                    foreach ($image_meta['image_meta'] as $key => $value) {
                        if (is_string($value)) {
                            //echo  $key . ' => ' . $value . '<br>';
                            if ($key == 'created_timestamp') {
                                $value_int = intval($value);
                                //$datum = "2019";
                                //$datum_int = strtotime($datum);
                                if ($value_int  != 0) {
                                    //$value = date("Y-m-d H:i:s",  $value_int);
                                    $value = date("Y-m-d",  $value_int);    
                                }
                                else {$value = '';}    
                            }
                            array_push($image_path_array,  $value);      
                        }
                        else {
                            //echo ' ';
                            array_push($image_path_array,  ' ');
                        }
                    }
                } 
            }
            array_push($images_path_array, $image_path_array);
            $image_path_array = array();
        }
    }
    gen_table($images_path_array);
}

function gen_table($daten){
    
    $string = array('Zähler', 'ID', 'Titel', 'url', 'Bild', 'original_image', 'aperture' , 'credit', 'camera', 'caption', 'created_timestamp', 'copyright', 'focal_length', 'iso', 'shutter_speed', 'title', 'orientation');
  

    ?>
    <style>
    .wide-cell {
     width: 300px; /* Breite der Zelle */
    }

    .hover-image {
    width: 150px; /* Ausgangsbreite des Bildes */
    transition: transform 0.3s ease; /* Sanfte Transformation */
    }

    .hover-image:hover {
    transform: scale(2); /* Vergrößerung auf 150% bei Hover */
}
    </style>
    <div class="wrap">
    <table border="1">
        <thead>
            <tr>
                <?php foreach ($string as $spalte) : ?>
                    <th><?php echo $spalte; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
                for ($i = 0; $i < count($daten); $i++) : ?>
                    <tr>
                        <?php 
                            $j =1;
                            $url = '---';
                            foreach ($daten[$i] as $wert) : 

                                if($j == 4) $image_url = $wert;
                                if ($j == 5){
                                     
                                    ?>
                                        <td class="wide-cell">
                                            <?php
                                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr('Bild') . '" class="hover-image" />'; 
                                            ?>
                                        </td>
                                    <?php 
                                } else{
                                    ?>
                                    <td><?php echo $wert; ?></td>
                                    <?php 
                                }
                                $j = $j +1;
                                endforeach; 
                        ?>
                    </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    </div>
    <?php



}


// utils submenu two
function get_bild_per_id() {
    $saved_value = get_option('bild_daten_management_option');
    if ($saved_value == false) {
        update_option('bild_daten_management_option', '240');
    }

        ?>
            <div class="updated">
            <h2>Bild Meta-Daten per ID </h2>
            <form method="post" action="">
                <label for="bild_daten_management_input">Eingabe der ID:</label>
                <input type="text" id="bild_id" name="bild_id" value="<?php echo esc_attr($saved_value); ?>" />
                <input type="submit" class="button-primary"  name="erster_button" value="Auswahl speichern">
                <input type="submit" class="button-primary" name="zweiter_button" value="Bild Daten lesen">
            </form>
            <hr />
        </div>
        <?php
        // Überprüfe, ob der zweite Button geklickt wurde
        if (isset($_POST['erster_button'])) {

            $bild_id = sanitize_text_field($_POST['bild_id']);
            $bild_id_store = get_option('bild_daten_management_option');
            if($bild_id != $bild_id_store) {
                write_input_field($bild_id );
                update_option('bild_daten_management_option', $bild_id);
                echo 'Auswahl gespeichert';
            } else echo 'keine Änderung';

        }

        if (isset($_POST['zweiter_button']) && !empty($_POST['bild_id'])) {
            
            $bild_id = sanitize_text_field($_POST['bild_id']);
            $attachment = get_post($bild_id);
            $metadata = wp_get_attachment_metadata($bild_id);
            $image_url = wp_get_attachment_url($bild_id);
            $file_path = get_attached_file($bild_id);
        
         
            if ($attachment && $metadata && $image_url && $file_path) {
                echo '<h2>Bild-Metadaten</h2>';
                echo '<h2>Bild-ID: ' . $bild_id . '</h2>';
                echo '<p><strong>Title:</strong> ' . esc_html($attachment->post_title) . '</p>';
                echo '<p><strong>Beschreibung:</strong> ' . esc_html($attachment->post_content) . '</p>';
                echo '<p><strong>Caption:</strong> ' . esc_html($attachment->post_excerpt) . '</p>';
                echo '<p><strong>URL:</strong> <a href="' . esc_url($image_url) . '" target="_blank">' . esc_html($image_url) . '</a></p>';
                echo '<p><strong>Dateiformat:</strong> ' . esc_html($attachment->post_mime_type) . '</p>';
                echo '<h3>Bild</h3>';
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($attachment->post_title) . '" style="max-width:500px;width:100%;height:auto;" />';
                echo '<h3>Metadaten</h3>';
                echo '<ul>';
                foreach ($metadata as $key => $value) {
                    if (is_array($value)) {
                        echo '<li><strong>' . esc_html($key) . ':</strong><pre>' . esc_html(print_r($value, true)) . '</pre></li>';
                    } else {
                        echo '<li><strong>' . esc_html($key) . ':</strong> ' . esc_html($value) . '</li>';
                    }
                }
                echo '</ul>';

                // EXIF-Daten lesen und anzeigen
                $exif = @exif_read_data($file_path);
                if ($exif) {
                    echo '<h3>EXIF-Daten</h3>';
                    echo '<ul>';
                    foreach ($exif as $key => $value) {
                        if (is_array($value)) {
                            echo '<li><strong>' . esc_html($key) . ':</strong><pre>' . esc_html(print_r($value, true)) . '</pre></li>';
                        } else {
                            echo '<li><strong>' . esc_html($key) . ':</strong> ' . esc_html($value) . '</li>';
                        }
                    }
                    echo '</ul>';
                } else {
                    echo '<p>Keine EXIF-Daten gefunden.</p>';
                }
            } else {
                echo '<p>Keine Bilddaten gefunden für die angegebene ID.</p>';
            }
            
        }

   


}

function write_input_field($value) {


    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Suche nach dem Element mit der ID 'xxxxx' 
        var inputElement = document.getElementById('bild_id');
        inputElement.value = <?php echo json_encode($value); ?>;
       
    });
    </script>
    <?php
  
}  

// utils submenu three
function be_plugin_page() {
    echo '<div class="updated">';
    echo '<h1>Willkommen auf dem Datenbank  analyse tool</h1><p>made by RKnOT</p>';
    $table_name = 'my_dropdown_option';
    $dropdown_values = array( 'event-recurring', 'event', 'option');
    $optionen = array(
        'event-recurring' => 'Event recurring',
        'event' => 'Event',
        'post' => 'Post',
        'page' => 'Page',
        'location' => 'Ort',
        'wpforms' => 'Vorlagen',
        'nav_menu_item' => 'navigation',

    
    
    );
    
    $tb_name = drop_down($optionen, $table_name);
    //get_posttypes();
    echo '</div';
}

function drop_down($optionen,  $table_name) {
    // Prüfe, ob das Formular gesendet wurde
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Überprüfe die nonce-Sicherheit
        if (isset($_POST['mein_dropdown_nonce']) && wp_verify_nonce($_POST['mein_dropdown_nonce'], 'mein_dropdown_action')) {
            // Rufe den ausgewählten Wert aus dem Formular ab
            $selected_value = sanitize_text_field($_POST['mein_dropdown']);
            // Speichere den ausgewählten Wert in der Datenbank (hier als Beispiel)
            update_option($table_name, $selected_value);

            // Gib eine Erfolgsmeldung aus
            echo '<div class="updated"><p>Auswahl erfolgreich gespeichert.</p></div>';
        }

        // Überprüfe, ob der zweite Button geklickt wurde
        if (isset($_POST['zweiter_button'])) {
            // Rufe hier deine PHP-Funktion auf
            $saved_option = get_option($table_name);
            show_database_table($saved_option);
        }
    }
    // Rufe den gespeicherten Wert aus der Datenbank ab
    $gespeicherter_wert = get_option($table_name, '');
    // Ausgabe des Formulars mit der Liste und dem zweiten Button
    ?>
        <form method="post" action="">
            <?php wp_nonce_field('mein_dropdown_action', 'mein_dropdown_nonce'); ?>
            <label for="mein_dropdown">Wähle eine Option:</label>
            <select name="mein_dropdown" id="mein_dropdown">
                <?php
                // Dynamisches Generieren der Optionen aus der Liste
                foreach ($optionen as $value => $label) {
                    echo '<option value="' . esc_attr($value) . '" ' . selected($gespeicherter_wert, $value, false) . '>' . esc_html($label) . '</option>';
                }
                ?>
            </select>
            <input type="submit" class="button-primary" value="Auswahl speichern">
            <!-- Hinzugefügter zweiter Button -->
            <input type="submit" class="button-primary" name="zweiter_button" value="Datenbank lesen">
        </form>
    <?php
}

function show_database_table($saved_option) {
    $count = 0;
    $now = new DateTime();
    $date =  $now->format('Y_m_d');
    $post_records = array();
    $post_records[] = 'Record nr.: ;ID:; Titel:; Post Datum:; Event Start:; Event Stop:;'. $date . "\n";
  
    $post_type = $saved_option;
    $args = array(
      'post_type'   => $post_type,
      'post_status' => 'publish',
      'posts_per_page' => - 1,
      );
    $posts = new WP_Query($args);
    $posts = $posts->posts;
    foreach($posts as $post){
        $title = $post->post_title;
        $guid = $post->guid;
        $id   =  $post->ID;
        $post_date = $post->post_date;
        $post_name = $post->post_name;
        $post_name_int = (float) $post_name;
        $title = $post->post_title;
        $event_start = "";
        $event_stop = "";
        if($saved_option == 'event-recurring' or $saved_option == 'event'){
            $custom_date_fields = get_post_meta( $id);$event_start = $custom_date_fields['_event_start'][0];
            $event_stop = $custom_date_fields['_event_end'][0];
        }
        $count +=1;
        $record_str =   $count . ';' . $id . ';' . $title . ';' . $post_date  . ';' . $event_start .';' .$event_stop .  "\n"; 
        $post_records[] = $record_str;
       
    }
    $table_html = '<div class="wrap"><table border="1">';

   
    foreach ($post_records as $str) {
        $row =  explode(';', $str);
        $table_html .= '<tr>';
        foreach ($row as $value) {
            $table_html .= '<td>' . esc_html($value) . '</td>';
        }
        $table_html .= '</tr>';
    }
    $table_html .= '</table>';
    echo $table_html;

 
}

function get_posttypes(){

    // Get post types
    $args       = array(
        'public' => true,
    );
    $post_types = get_post_types( $args, 'objects' );
    ?>
    
    <select class="widefat" name="post_type">
        <?php foreach ( $post_types as $post_type_obj ):
            $labels = get_post_type_labels( $post_type_obj );
            ?>
            <option value="<?php echo esc_attr( $post_type_obj->name ); ?>"><?php echo esc_html( $labels->name ); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
    
}




