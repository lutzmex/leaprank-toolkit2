<?php
/**
 * Plugin Name: LeapRank SaaS Toolkit
 * Plugin URI: https://Leaprank.ai
 * Description: The ultimate all-in-one toolkit for SaaS sites. Includes 30+ Premium Headers, Smart TOC, and Advanced UI Utilities.
 * Version: 2.0.0
 * Author: LeapRank
 * Author URI: https://Leaprank.ai
 * License: GPL v2 or later
 * Text Domain: leaprank-toolkit
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Constants
define('LRST_VERSION', '2.0.0');
define('LRST_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LRST_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LRST_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader for classes
spl_autoload_register(function ($class) {
    if (strpos($class, 'LRST_') !== 0) {
        return;
    }
    
    $file_name = 'class-' . str_replace('_', '-', strtolower(substr($class, 5))) . '.php';
    $file_path = LRST_PLUGIN_DIR . 'includes/' . $file_name;
    
    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

// Initialize Main Plugin Class
function leaprank_toolkit_init() {
    return LRST_Core::instance();
}

// Start the plugin
add_action('plugins_loaded', 'leaprank_toolkit_init');

// Activation Hook
register_activation_hook(__FILE__, array('LRST_Core', 'activate'));
