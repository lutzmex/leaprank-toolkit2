<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Category_Manager {

    public function __construct() {
        add_filter('pre_get_posts', array($this, 'exclude_categories'));
    }

    public function exclude_categories($query) {
        // Never interfere with Admin
        if (is_admin()) {
            return $query;
        }

        // Get excluded categories
        $excluded_cats = get_option('lrst_excluded_categories', array());
        
        if (empty($excluded_cats) || !is_array($excluded_cats)) {
            return $query;
        }

        // Prepare negative IDs array (like the working plugin)
        $exclude_array = array();
        foreach ($excluded_cats as $cat_id) {
            $exclude_array[] = absint($cat_id);
        }

        // HOME PAGE - Apply exclusion
        if ($query->is_home) {
            $query->set('category__not_in', $exclude_array);
        }

        // FEEDS - Apply exclusion
        if ($query->is_feed) {
            $query->set('category__not_in', $exclude_array);
        }

        // SEARCH - Apply exclusion
        if ($query->is_search) {
            $query->set('category__not_in', $exclude_array);
        }

        // ARCHIVES - Apply exclusion
        if ($query->is_archive) {
            $query->set('category__not_in', $exclude_array);
        }

        return $query;
    }
}
