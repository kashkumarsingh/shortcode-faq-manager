<?php
/**
 * Plugin Name: Shortcode FAQ Manager
 * Description: A plugin to create a shortcode for displaying FAQs with accordion functionality.
 * Version: 1.0
 * Author: Kashkumar Singh
 * Text Domain: shortcode-faq-manager
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Autoload classes from the includes folder
require_once plugin_dir_path( __FILE__ ) . 'includes/class-shortcode-faq-manager.php';

// Initialize the plugin
function sfm_init() {
    $faq_manager = new Shortcode_FAQ_Manager();
    $faq_manager->init();
}
add_action( 'plugins_loaded', 'sfm_init' );
