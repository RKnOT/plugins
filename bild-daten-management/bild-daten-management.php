<?php
/*
Plugin Name: Bild Daten Management
Description: Analyse Tool für Bilddaten- und Event-Einträge in der DB
Version: 1.0
Author: RKnOT
*/

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'src/utils-bdm.php';

class BildDatenManagement {
    public function __construct() {
        add_action('admin_footer', array($this, 'disable_div_with_id'));
        // Menü im Admin-Backend hinzufügen
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    public function disable_div_with_id() {
        // Überprüfe, ob wir uns im WordPress-Admin-Bereich befinden
        if (is_admin()) {
            ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Suche nach dem <div>-Element mit der ID 'xxxxx' und verstecke es
                var divElement = document.getElementById('wpforms-notice-license-expired');
                if (divElement) {
                    divElement.style.display = 'none';
                }
            });
            </script>
            <?php
        }
    }
    public function add_admin_menu() {
   
        
        // Hauptmenüpunkt hinzufügen
        add_menu_page(
            'DB Daten Management', // Seiten-Titel
            'DB Daten Management', // Menü-Titel
            'manage_options', // Berechtigungen
            'db-daten-management', // Slug
            array($this, 'hauptseite_callback'), // Callback-Funktion
            'dashicons-admin-generic', // Icon
            80 // Position (nach Einstellungen)
        );
        add_submenu_page(
            'db-daten-management', // Eltern-Slug
            'DB Daten Management', // Seiten-Titel
            'DB Daten Management', // Menü-Titel
            'manage_options', // Berechtigungen
            'db-management', // Slug
            array($this, 'hauptseite_callback') // Callback-Funktion
        );

        add_submenu_page(
            'db-daten-management', // Eltern-Slug
            'db-bilder-links', // Seiten-Titel
            'DB Bilder Links', // Menü-Titel
            'manage_options', // Berechtigungen
            'db-bilder-links', // Slug
            array($this, 'db_bilder_links_callback') // Callback-Funktion
        );

        // Untermenüpunkte hinzufügen
        add_submenu_page(
            'db-daten-management', // Eltern-Slug
            'bild-per-id', // Seiten-Titel
            'Bild per Id', // Menü-Titel
            'manage_options', // Berechtigungen
            'bild-per-id', // Slug
            array($this, 'bild_per_id_callback') // Callback-Funktion
        );
           // Untermenüpunkte hinzufügen
           add_submenu_page(
            'db-daten-management', // Eltern-Slug
            'db-analyse', // Seiten-Titel
            'DB Analyse', // Menü-Titel
            'manage_options', // Berechtigungen
            'db-analyse', // Slug
            array($this, 'db_analyse_callback') // Callback-Funktion
        );
        remove_submenu_page('db-daten-management', 'db-daten-management');
        
    }
    public function hauptseite_callback() {
        echo '<div class="updated">';
        echo '<h1>Hauptseite WP Datenbank Management</h1>';
        echo '<h4>prepared by RKnOT';
        echo '</div>';     
    }
    
    public function db_bilder_links_callback() {
   
        get_all_db_images_path();
        
    }
    
    public function uebersicht_callback() {
        //echo '<div class="wrap"><h1>Übersicht</h1><p>Inhalt der Übersicht.</p></div>';
        array($this, 'bild_per_id_callback'); // Callback-Funktion
        
       
        
    }
    public function bild_per_id_callback() {
        get_bild_per_id();
       
        }
    public function db_analyse_callback() {
        be_plugin_page();


    }



    
    
   

    



    }
// Initialisiere das Plugin
if (is_admin()) {
    $bildDatenManagement = new BildDatenManagement();
}

?>
