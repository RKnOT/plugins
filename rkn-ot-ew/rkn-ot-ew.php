<?php
/*
Plugin Name: RKN_OT_EW
Description: Ein Plugin mit einem Menüpunkt und fünf Untermenüpunkten im WordPress Backend.
Version: 1.0
Author: Dein Name
*/

if (!defined('ABSPATH')) {
    exit; // Sicherheit: Direktes Aufrufen der Datei verhindern
}

class RKN_OT_EW {

    public function __construct() {
        add_action('admin_menu', array($this, 'register_menu_pages'));
        add_action('wp_ajax_rkn_ot_ajax_action', array($this, 'handle_ajax_request'));
      //echo 'aufruf der main seite';
    }

//------------

    // Die PHP-Funktion, die vom AJAX aufgerufen wird
    public function handle_ajax_request() {
        // Hier kannst du beliebige Logik einfügen
        echo 'Der Button wurde geklickt und dies ist die Antwort von der PHP-Funktion in Menüpunkt 3.';
        wp_die();  // Beendet das AJAX-Handling korrekt
    }
   

//------------------

    public function register_menu_pages() {
        // Hauptmenüpunkt
        add_menu_page(
            'RKn OT',          // Seiten-Titel
            'RKn OT',          // Menü-Text
            'manage_options',   // Berechtigungen
            'rkn-ot',          // Menü-Slug
            array($this, 'menu_page_display'), // Callback
            '',                 // Symbol-Icon (optional)
            6                   // Position im Menü
        );

        // Untermenüpunkte
        add_submenu_page(
            'rkn-ot',          // Eltern-Slug (rkn-ot)
            'Copy Post to Event',     // Seiten-Titel
            'Copy Post to Event',     // Menü-Text
            'manage_options',   // Berechtigungen
            'copy-post-to-event',      // Menü-Slug
            array($this, 'submenu_page1') // Callback
        );

        add_submenu_page(
            'rkn-ot',          // Eltern-Slug (rkn-ot)
            'Buttons und RBs mit JS',     // Seiten-Titel
            'Buttons und RBs mit JS',     // Menü-Text
            'manage_options',   // Berechtigungen
            'rkn-buttons',      // Menü-Slug
            array($this, 'submenu_page2') // Callback
        );

        add_submenu_page(
            'rkn-ot',          // Eltern-Slug (rkn-ot)
            'Untermenü 3',     // Seiten-Titel
            'Untermenü 3',     // Menü-Text
            'manage_options',   // Berechtigungen
            'rkn-ot-sub3',      // Menü-Slug
            array($this, 'submenu_page3') // Callback
        );

        add_submenu_page(
            'rkn-ot',          // Eltern-Slug (rkn-ot)
            'Untermenü 4',     // Seiten-Titel
            'Untermenü 4',     // Menü-Text
            'manage_options',   // Berechtigungen
            'rkn-ot-sub4',      // Menü-Slug
            array($this, 'submenu_page4') // Callback
        );

        add_submenu_page(
            'rkn-ot',          // Eltern-Slug (rkn-ot)
            'Untermenü 5',     // Seiten-Titel
            'Untermenü 5',     // Menü-Text
            'manage_options',   // Berechtigungen
            'rkn-ot-sub5',      // Menü-Slug
            array($this, 'submenu_page5') // Callback
        );
    }

    // Hauptmenü-Callback
    public function menu_page_display() {
        echo '<h1>Hauptmenü: RKN OT Plugin</h1>';
        echo '<p>Willkommen auf der Hauptseite des RKN OT Plugins.</p>';
    }

    // Untermenü-Callbacks
    public function submenu_page1() {
        require_once plugin_dir_path(__FILE__) . 'src/php/menu/menu-page-1.php';
    }

    public function submenu_page2() {
        require_once plugin_dir_path(__FILE__) . 'src/php/menu/menu-page-2.php';
    }

    public function submenu_page3() {
        require_once plugin_dir_path(__FILE__) . 'src/php/menu/menu-page-3.php';
    }

    public function submenu_page4() {
        require_once plugin_dir_path(__FILE__) . 'src/php/menu/menu-page-4.php';
    }

    public function submenu_page5() {
        require_once plugin_dir_path(__FILE__) . 'src/php/menu/menu-page-5.php';
    }
}

// Initialisierung des Plugins
if (class_exists('RKN_OT_EW')) {
    $rkn_ot_ew = new RKN_OT_EW();
}
