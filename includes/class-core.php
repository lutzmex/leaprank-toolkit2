<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Core {
    
    private static $instance = null;
    public $header_engine;
    public $toc_engine;
    public $utility_engine;
    public $category_manager;
    public $admin_ui;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Initialize Modules
        $this->header_engine = new LRST_Header_Engine();
        $this->toc_engine = new LRST_Toc_Engine();
        $this->utility_engine = new LRST_Utility_Engine();
        $this->category_manager = new LRST_Category_Manager();
        
        if (is_admin()) {
            $this->admin_ui = new LRST_Admin_Ui();
            // Ensure defaults are set if options are missing (Auto-setup)
            add_action('admin_init', array($this, 'check_and_set_defaults'));
        }
        
        // Load Assets
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
    }

    public function check_and_set_defaults() {
        try {
            // Only run if not configured yet AND after WordPress is fully loaded
            if (did_action('init')) {
                if (false === get_option('lrst_header_enabled')) {
                    self::activate();
                }
            }
        } catch (Exception $e) {
            // Silently fail to prevent admin errors
        }
    }

    public function admin_assets($hook) {
        if (strpos($hook, 'leaprank-toolkit') === false) {
            return;
        }

        wp_enqueue_style('lrst-admin-css', LRST_PLUGIN_URL . 'assets/css/admin.css', array(), LRST_VERSION);
        wp_enqueue_script('lrst-admin-js', LRST_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), LRST_VERSION, true);
    }

    public static function activate() {
        try {
            // Header Defaults
            if (false === get_option('lrst_header_enabled') || '' === get_option('lrst_header_enabled')) {
                update_option('lrst_header_enabled', '1');
                update_option('lrst_selected_template', 'final-perfect'); 
                update_option('lrst_sticky_mode', 'smart');
            }
            
            // TOC Defaults
            if (false === get_option('lrst_toc_enabled') || '' === get_option('lrst_toc_enabled')) {
                update_option('lrst_toc_enabled', '1');
                update_option('lrst_toc_style', 'sidebar-clean'); 
                update_option('lrst_toc_min_headings', '3');
            }

            // Utility Defaults - Both Server Remove by default for clean HTML source
            if (false === get_option('lrst_hide_partners') || '' === get_option('lrst_hide_partners')) {
                update_option('lrst_hide_partners', '1'); 
                update_option('lrst_partners_mode', 'server'); // Server remove
            }
            
            if (false === get_option('lrst_hide_sidebar') || '' === get_option('lrst_hide_sidebar')) {
                update_option('lrst_hide_sidebar', '1'); 
                update_option('lrst_sidebar_mode', 'server'); // Server remove
            }

            if (false === get_option('lrst_highlight_links')) {
                update_option('lrst_highlight_links', '1'); 
            }

            // Auto-select Default Excluded Categories
            if (false === get_option('lrst_excluded_categories')) {
                if (function_exists('get_term_by') && function_exists('taxonomy_exists') && taxonomy_exists('category')) {
                    $default_cats = array('Gambling', 'Games', 'Sports', 'Uncategorized');
                    $excluded_ids = array();
                    
                    foreach ($default_cats as $name) {
                        $term = get_term_by('name', $name, 'category');
                        if ($term && !is_wp_error($term) && isset($term->term_id)) {
                            $excluded_ids[] = $term->term_id;
                        }
                    }
                    
                    update_option('lrst_excluded_categories', $excluded_ids);
                } else {
                    // Set empty array if categories not ready yet
                    update_option('lrst_excluded_categories', array());
                }
            }
        } catch (Exception $e) {
            // Prevent activation errors - log but don't crash
            if (function_exists('error_log')) {
                error_log('LeapRank Toolkit Activation: ' . $e->getMessage());
            }
        }
    }
}
