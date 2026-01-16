<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Utility_Engine {

    public function __construct() {
        // CSS based hiding and highlighting
        add_action('wp_head', array($this, 'render_utility_css'), 999);

        // Server-side source cleaning (Removes from HTML source)
        add_filter('sidebars_widgets', array($this, 'remove_sidebar_source'), 10);
    }

    /**
     * Get all active widgets on the site
     */
    public static function get_all_widgets() {
        try {
            global $wp_registered_widgets;
            
            if (!isset($wp_registered_widgets) || !is_array($wp_registered_widgets)) {
                return array();
            }

            $sidebars = wp_get_sidebars_widgets();
            
            if (!is_array($sidebars)) {
                return array();
            }

            $all_widgets = array();

            foreach ($sidebars as $sidebar_id => $widgets) {
                if ($sidebar_id === 'wp_inactive_widgets' || empty($widgets) || !is_array($widgets)) {
                    continue;
                }

                foreach ($widgets as $widget_id) {
                    if (isset($wp_registered_widgets[$widget_id]) && is_array($wp_registered_widgets[$widget_id])) {
                        $widget_obj = $wp_registered_widgets[$widget_id];
                        $all_widgets[$widget_id] = array(
                            'name' => isset($widget_obj['name']) ? $widget_obj['name'] : $widget_id,
                            'id' => $widget_id,
                            'sidebar' => $sidebar_id,
                            'callback' => isset($widget_obj['callback']) ? $widget_obj['callback'] : ''
                        );
                    }
                }
            }

            return $all_widgets;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Completely removes sidebars/widgets from the HTML source on single posts.
     */
    public function remove_sidebar_source($sidebars_widgets) {
        if (!is_single() || !is_array($sidebars_widgets)) {
            return $sidebars_widgets;
        }

        try {
            $hide_sidebar = get_option('lrst_hide_sidebar');
            $sidebar_mode = get_option('lrst_sidebar_mode', 'server');
            $hide_partners = get_option('lrst_hide_partners');
            $partners_mode = get_option('lrst_partners_mode', 'css');
            $hidden_widgets = get_option('lrst_hidden_widgets', array());
            
            if (!is_array($hidden_widgets)) {
                $hidden_widgets = array();
            }

            // Remove entire sidebar if enabled and mode is 'server'
            if ($hide_sidebar && $sidebar_mode === 'server') {
                foreach ($sidebars_widgets as $sidebar_id => $widgets) {
                    if ($sidebar_id !== 'wp_inactive_widgets' && is_array($widgets)) {
                        $sidebars_widgets[$sidebar_id] = array();
                    }
                }
                return $sidebars_widgets;
            }

            // Remove specific widgets
            foreach ($sidebars_widgets as $sidebar_id => $widgets) {
                if ($sidebar_id === 'wp_inactive_widgets' || empty($widgets) || !is_array($widgets)) {
                    continue;
                }

                foreach ($widgets as $key => $widget_id) {
                    // Remove partners widget if server mode
                    if ($hide_partners && $partners_mode === 'server') {
                        if (strpos($widget_id, 'custom_html') !== false || strpos($widget_id, 'partner') !== false) {
                            unset($sidebars_widgets[$sidebar_id][$key]);
                        }
                    }

                    // Remove individually selected widgets
                    if (in_array($widget_id, $hidden_widgets)) {
                        unset($sidebars_widgets[$sidebar_id][$key]);
                    }
                }
            }

            return $sidebars_widgets;
        } catch (Exception $e) {
            // Return unmodified on error to prevent crashes
            return $sidebars_widgets;
        }
    }

    public function render_utility_css() {
        if (!is_single()) {
            return;
        }

        try {
            $hide_partners = get_option('lrst_hide_partners');
            $partners_mode = get_option('lrst_partners_mode', 'css');
            $hide_sidebar = get_option('lrst_hide_sidebar');
            $sidebar_mode = get_option('lrst_sidebar_mode', 'server');
            $hide_related = get_option('lrst_hide_related');
            $hide_more_stories = get_option('lrst_hide_more_stories');
            $highlight_links = get_option('lrst_highlight_links', '1');
            $hidden_widgets = get_option('lrst_hidden_widgets', array());
            $custom_css = get_option('lrst_custom_css');
            
            if (!is_array($hidden_widgets)) {
                $hidden_widgets = array();
            }

            $selectors = array();

            // Only apply CSS hiding if mode is CSS (not server) for Partners
            if ($hide_partners && $partners_mode === 'css') {
                $selectors = array_merge($selectors, [
                    'body.single #secondary', 
                    'body.single .sidebar-area',
                    'body.single-post #secondary',
                    'body.single-post .sidebar-area',
                    'body.single #custom_html-2', 
                    'body.single .widget_custom_html',
                    'body.single .theiaStickySidebar',
                    'body.single-post #custom_html-2',
                    'body.single-post .widget_custom_html',
                    'body.single-post .theiaStickySidebar'
                ]);
            }

            // Only apply CSS hiding if mode is CSS for Sidebar
            if ($hide_sidebar && $sidebar_mode === 'css') {
                $selectors = array_merge($selectors, [
                    'body.single .sidebar', 
                    'body.single aside:not(.lrst-toc):not([class*="stocc"])', 
                    'body.single .widget-area',
                    'body.single-post .sidebar',
                    'body.single-post aside:not(.lrst-toc):not([class*="stocc"])',
                    'body.single-post .widget-area',
                    'body.single .main-sidebar'
                ]);
            }

            // Add individual widget IDs for CSS hiding
            if (!empty($hidden_widgets)) {
                foreach ($hidden_widgets as $widget_id) {
                    $selectors[] = '#' . esc_attr($widget_id);
                }
            }

            // Comprehensive Related Posts hiding (from old plugin)
            if ($hide_related) {
                $selectors = array_merge($selectors, [
                    '.related-posts',
                    '.related-articles',
                    '.related-content',
                    '.yarpp-related',
                    '.jp-relatedposts',
                    '.crp_related',
                    '.similar-posts',
                    '.you-may-also-like',
                    '[class*="related-post"]',
                    '[class*="related"]'
                ]);
            }

            // Comprehensive "More Stories" hiding (from old plugin)
            if ($hide_more_stories) {
                $selectors = array_merge($selectors, [
                    '.widget-title.header-after1',
                    'h2.widget-title.header-after1',
                    '.header-after1',
                    '[class*="more-stories"]',
                    '[class*="more_stories"]',
                    '[class*="you-may"]',
                    '[class*="missed"]',
                    '.you-may-like'
                ]);
            }

            // Custom CSS selectors
            if (!empty($custom_css)) {
                $lines = explode("\n", $custom_css);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line) && strpos($line, '{') === false) {
                        $selectors[] = $line;
                    }
                }
            }

            $site_domain = parse_url(home_url(), PHP_URL_HOST);
            if (!$site_domain) {
                $site_domain = 'localhost';
            }
            ?>
            <style id="lrst-utility-custom">
                /* --- 1. External Link Highlighting ONLY --- */
                <?php if ($highlight_links): ?>
                .entry-content a[href*="//"]:not([href*="<?php echo esc_attr($site_domain); ?>"]):not(.lrst-toc-link):not(.stocc-toc-link):not(.button):not([href^="#"]),
                .post-content a[href*="//"]:not([href*="<?php echo esc_attr($site_domain); ?>"]):not(.lrst-toc-link):not(.stocc-toc-link):not(.button):not([href^="#"]),
                article a[href*="//"]:not([href*="<?php echo esc_attr($site_domain); ?>"]):not(.lrst-toc-link):not(.stocc-toc-link):not(.button):not([href^="#"]) {
                    color: #2563eb !important;
                    font-weight: 700 !important;
                    text-decoration: underline !important;
                    text-underline-offset: 2px !important;
                    transition: all 0.2s ease !important;
                }
                .entry-content a[href*="//"]:not([href*="<?php echo esc_attr($site_domain); ?>"]):not(.lrst-toc-link):not(.stocc-toc-link):not(.button):not([href^="#"]):hover,
                .post-content a[href*="//"]:not([href*="<?php echo esc_attr($site_domain); ?>"]):not(.lrst-toc-link):not(.stocc-toc-link):not(.button):not([href^="#"]):hover,
                article a[href*="//"]:not([href*="<?php echo esc_attr($site_domain); ?>"]):not(.lrst-toc-link):not(.stocc-toc-link):not(.button):not([href^="#"]):hover {
                    color: #1d4ed8 !important;
                    background-color: rgba(37, 99, 235, 0.08) !important;
                    padding: 2px 4px !important;
                    border-radius: 3px !important;
                }
                <?php endif; ?>

                /* --- 2. CSS Hiding (Comprehensive) --- */
                <?php if (!empty($selectors)): ?>
                <?php echo implode(',', array_unique($selectors)); ?> {
                    display: none !important;
                    visibility: hidden !important;
                    opacity: 0 !important;
                    height: 0 !important;
                    width: 0 !important;
                    overflow: hidden !important;
                    position: absolute !important;
                    left: -9999px !important;
                    pointer-events: none !important;
                }
                <?php endif; ?>
                
                /* --- 3. Full Width Fixes --- */
                <?php if ($hide_sidebar): ?>
                body.single .content-area,
                body.single .site-main,
                body.single-post .content-area,
                body.single-post .site-main,
                body.single #primary,
                body.single .main-content {
                    width: 100% !important;
                    max-width: 100% !important;
                    flex: 0 0 100% !important;
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                }
                <?php endif; ?>
            </style>
            <!-- External link highlighting uses CSS only (no JS attribute modification) -->
            <?php
        } catch (Exception $e) {
            // Prevent any output on error
        }
    }
}
