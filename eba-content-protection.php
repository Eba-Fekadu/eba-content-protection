<?php

/**
 * Plugin Name: Eba Content Protection for WP
 * Plugin URI:  https://eba.com/eba-content-protection
 * Author:      Eba
 * Author URI:  https://github.com/Eba-Fekadu
 * Description: A simple content protection plugin for WordPress.
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: eba-content-protection
*/

//check if file is accessed directly
if(!defined('WPINC')){
    exit("You can't access this file directly");
}

//define plugin constants
define("EBA_CONTENT_PROTECTION_VERSION",time());
//define plugin file
define("EBA_CONTENT_PROTECTION_FILE",__FILE__);
//define plugin directory
define("EBA_CONTENT_PROTECTION_DIR",dirname(EBA_CONTENT_PROTECTION_FILE));

// Check if the main plugin class exists; if not, include it
if(!class_exists('Eba_Content_Protection')){
include_once EBA_CONTENT_PROTECTION_DIR . '/includes/class-eba-content-protection.php';
}

/**
 * Enqueues custom styles for the admin area of the plugin.
 */
function custom_admin_plugin_styles() {
    wp_enqueue_style('my-custom-styles', plugin_dir_url(__FILE__) . 'styles/content-protection-style.css');
    
}

/**
 * Enqueues custom styles for the front-end of the plugin.
 */
function custom_wp_plugin_styles() {
    wp_enqueue_style('my-custom-styles', plugin_dir_url(__FILE__) . 'styles/password-form.css');
    
}


add_action('admin_enqueue_scripts', 'custom_admin_plugin_styles');
add_action('wp_enqueue_scripts', 'custom_wp_plugin_styles');