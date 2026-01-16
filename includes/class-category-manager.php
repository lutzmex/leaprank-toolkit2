<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Category_Manager {

    public function __construct() {
        add_action('init', array($this, 'init_hooks'));
    }

    public function init_hooks() {
        // Main query manipulation
        add_action('pre_get_posts', array($this, 'exclude_categories'));
    }

    public function exclude_categories($query) {
        try {
            // Never interfere with Admin
            if (is_admin()) {
                return;
            }

            // Safety check for query object
            if (!isset($query) || !is_object($query)) {
                return;
            }

            // Get excluded categories
            $excluded_cats = get_option('lrst_excluded_categories', array());
            
            if (empty($excluded_cats) || !is_array($excluded_cats)) {
                return;
            }

            // 1. Single Post Pages:
            // We want the post itself to be visible if we visit it directly.
            if ($query->is_main_query() && $query->is_single()) {
                return;
            }

            // Safety check for menus
            if ($query->get('post_type') === 'nav_menu_item') {
                return;
            }

            // Apply Exclusion
            $current_excluded = $query->get('category__not_in');
            if (!is_array($current_excluded)) {
                $current_excluded = array();
            }
            
            // Merge with existing exclusions
            $new_excludes = array_map('absint', $excluded_cats);
            $query->set('category__not_in', array_merge($current_excluded, $new_excludes));
        } catch (Exception $e) {
            // Silently fail to prevent site crashes
            return;
        }
    }
}
