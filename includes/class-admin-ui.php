<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Admin_Ui {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_menu_page() {
        add_menu_page(
            'LeapRank Toolkit',
            'LeapRank Toolkit',
            'manage_options',
            'leaprank-toolkit',
            array($this, 'render_dashboard'),
            'dashicons-superhero',
            2
        );
    }

    public function register_settings() {
        // Header Group
        register_setting('lrst_header_group', 'lrst_header_enabled');
        register_setting('lrst_header_group', 'lrst_selected_template');
        register_setting('lrst_header_group', 'lrst_sticky_mode');
        
        // TOC Group
        register_setting('lrst_toc_group', 'lrst_toc_enabled');
        register_setting('lrst_toc_group', 'lrst_toc_style');
        register_setting('lrst_toc_group', 'lrst_toc_min_headings');
        
        // Utility Group
        register_setting('lrst_utility_group', 'lrst_hide_partners');
        register_setting('lrst_utility_group', 'lrst_hide_sidebar');
        register_setting('lrst_utility_group', 'lrst_sidebar_mode'); // css or server
        register_setting('lrst_utility_group', 'lrst_partners_mode'); // css or server
        register_setting('lrst_utility_group', 'lrst_hide_related');
        register_setting('lrst_utility_group', 'lrst_hide_more_stories');
        register_setting('lrst_utility_group', 'lrst_highlight_links');
        register_setting('lrst_utility_group', 'lrst_hidden_widgets'); // Array of widget IDs
        register_setting('lrst_utility_group', 'lrst_custom_css');

        // Category Manager Group
        register_setting('lrst_category_group', 'lrst_excluded_categories');
    }

    public function render_dashboard() {
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
        ?>
        <div class="lrst-wrap">
            <!-- Sidebar Navigation -->
            <div class="lrst-sidebar">
                <div class="lrst-logo-area">
                    <span class="dashicons dashicons-superhero"></span>
                    <h2>LeapRank</h2>
                    <span class="lrst-version">v<?php echo LRST_VERSION; ?></span>
                </div>
                
                <nav class="lrst-nav-menu">
                    <a href="?page=leaprank-toolkit&tab=dashboard" class="<?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-dashboard"></span> Dashboard
                    </a>
                    <a href="?page=leaprank-toolkit&tab=headers" class="<?php echo $active_tab === 'headers' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-layout"></span> Headers <span class="lrst-badge">30+</span>
                    </a>
                    <a href="?page=leaprank-toolkit&tab=toc" class="<?php echo $active_tab === 'toc' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-list-view"></span> Smart TOC
                    </a>
                    <a href="?page=leaprank-toolkit&tab=categories" class="<?php echo $active_tab === 'categories' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-category"></span> Category Manager
                    </a>
                    <a href="?page=leaprank-toolkit&tab=utilities" class="<?php echo $active_tab === 'utilities' ? 'active' : ''; ?>">
                        <span class="dashicons dashicons-admin-tools"></span> Utilities
                    </a>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="lrst-content">
                <?php if ($active_tab === 'dashboard'): ?>
                    <?php $this->tab_dashboard(); ?>
                <?php else: ?>
                    <form method="post" action="options.php">
                        <?php 
                        switch($active_tab) {
                            case 'headers':
                                settings_fields('lrst_header_group');
                                $this->tab_headers();
                                break;
                            case 'toc':
                                settings_fields('lrst_toc_group');
                                $this->tab_toc();
                                break;
                            case 'categories':
                                settings_fields('lrst_category_group');
                                $this->tab_categories();
                                break;
                            case 'utilities':
                                settings_fields('lrst_utility_group');
                                $this->tab_utilities();
                                break;
                        }
                        ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function tab_dashboard() {
        ?>
        <div class="lrst-header-banner">
            <h1>Welcome to LeapRank Toolkit</h1>
            <p>Your all-in-one solution for SaaS website optimization.</p>
        </div>

        <div class="lrst-grid-2">
            <div class="lrst-card">
                <h3>🚀 Quick Status</h3>
                <div class="lrst-status-row">
                    <span>Premium Header</span>
                    <span class="lrst-status <?php echo get_option('lrst_header_enabled') ? 'on' : 'off'; ?>">
                        <?php echo get_option('lrst_header_enabled') ? 'Active' : 'Inactive'; ?>
                    </span>
                </div>
                <div class="lrst-status-row">
                    <span>Smart TOC</span>
                    <span class="lrst-status <?php echo get_option('lrst_toc_enabled') ? 'on' : 'off'; ?>">
                        <?php echo get_option('lrst_toc_enabled') ? 'Active' : 'Inactive'; ?>
                    </span>
                </div>
            </div>
            
            <div class="lrst-card">
                <h3>💡 Tips</h3>
                <ul class="lrst-tips">
                    <li>Use <strong>Sticky Smart</strong> mode for better UX.</li>
                    <li>Hide "Partners" widget on single posts for cleaner reading.</li>
                </ul>
            </div>
        </div>
        <?php
    }

    private function tab_headers() {
        $header_engine = new LRST_Header_Engine();
        $templates = $header_engine->get_all_templates();
        $selected = get_option('lrst_selected_template', 'saas-modern-1');
        $enabled = get_option('lrst_header_enabled');
        $sticky = get_option('lrst_sticky_mode', 'smart');
        ?>
        <div class="lrst-section-header">
            <h2>Header Manager</h2>
            <div class="lrst-toggle-switch">
                <input type="checkbox" name="lrst_header_enabled" value="1" id="h_enable" <?php checked($enabled, '1'); ?>>
                <label for="h_enable">Enable Headers</label>
            </div>
        </div>

        <div class="lrst-card">
            <h3>Configuration</h3>
            <div class="lrst-form-group">
                <label>Sticky Behavior</label>
                <select name="lrst_sticky_mode">
                    <option value="smart" <?php selected($sticky, 'smart'); ?>>Smart (Hide on scroll down, Show on up)</option>
                    <option value="fixed" <?php selected($sticky, 'fixed'); ?>>Fixed (Always visible)</option>
                    <option value="static" <?php selected($sticky, 'static'); ?>>Static (Scrolls with page)</option>
                </select>
            </div>
        </div>

        <h3>Choose Template (<?php echo count($templates); ?> Available)</h3>
        <div class="lrst-templates-grid">
            <?php foreach ($templates as $id => $tpl): 
                $layout = isset($tpl['layout']) ? $tpl['layout'] : 'left-right';
            ?>
                <label class="lrst-template-card <?php echo $selected === $id ? 'active' : ''; ?>">
                    <input type="radio" name="lrst_selected_template" value="<?php echo esc_attr($id); ?>" <?php checked($selected, $id); ?>>
                    <div class="lrst-tpl-preview <?php echo 'layout-' . $layout; ?>">
                        <span class="mini-logo"></span>
                        <span class="mini-nav"></span>
                        <span class="mini-cta"></span>
                    </div>
                    <div class="lrst-tpl-info">
                        <h4><?php echo esc_html($tpl['name']); ?></h4>
                        <span class="lrst-badge-small"><?php echo esc_html($tpl['group']); ?></span>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>

        
        <div class="lrst-save-bar">
            <button type="submit" class="button button-primary button-hero">
                <span class="dashicons dashicons-saved" style="margin-top: 4px;"></span> Save Header Settings
            </button>
        </div>
        <?php
    }

    private function tab_toc() {
        $enabled = get_option('lrst_toc_enabled');
        $current_style = get_option('lrst_toc_style');
        $min = get_option('lrst_toc_min_headings');
        
        $styles = LRST_Toc_Engine::get_styles();
        ?>
        <div class="lrst-section-header">
            <h2>Smart Table of Contents</h2>
            <div class="lrst-toggle-switch">
                <input type="checkbox" name="lrst_toc_enabled" value="1" id="t_enable" <?php checked($enabled, '1'); ?>>
                <label for="t_enable">Enable TOC</label>
            </div>
        </div>

        <div class="lrst-grid-2">
            <div class="lrst-card">
                <h3>Settings</h3>
                <div class="lrst-form-group">
                    <label>Minimum H2 Headings</label>
                    <select name="lrst_toc_min_headings">
                        <option value="2" <?php selected($min, '2'); ?>>2 Headings</option>
                        <option value="3" <?php selected($min, '3'); ?>>3 Headings</option>
                        <option value="4" <?php selected($min, '4'); ?>>4 Headings</option>
                        <option value="5" <?php selected($min, '5'); ?>>5 Headings</option>
                    </select>
                </div>
            </div>
        </div>

        <h3>Select Design (<?php echo count($styles); ?> Available)</h3>
        <div class="lrst-styles-grid">
            <?php foreach ($styles as $slug => $name): ?>
            <label class="lrst-style-card <?php echo $current_style === $slug ? 'active' : ''; ?>">
                <input type="radio" name="lrst_toc_style" value="<?php echo esc_attr($slug); ?>" <?php checked($current_style, $slug); ?>>
                <div class="lrst-style-preview">
                    <div class="mock-toc <?php echo esc_attr($slug); ?>"></div>
                </div>
                <h4><?php echo esc_html($name); ?></h4>
            </label>
            <?php endforeach; ?>
        </div>

        <div class="lrst-save-bar">
            <button type="submit" class="button button-primary button-hero">
                <span class="dashicons dashicons-saved" style="margin-top: 4px;"></span> Save TOC Settings
            </button>
        </div>
        <?php
    }

    private function tab_categories() {
        $excluded = get_option('lrst_excluded_categories', array());
        if (!is_array($excluded)) $excluded = array();
        
        $categories = get_categories(array('hide_empty' => false));
        ?>
        <div class="lrst-section-header">
            <h2>Category Manager</h2>
            <p>Hide specific categories from the entire website (Home, Archives, Search, Feeds). Individual posts will remain accessible via direct link.</p>
        </div>

        <div class="lrst-card">
            <h3>Select Categories to Hide</h3>
            <div class="lrst-categories-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                <?php foreach ($categories as $cat): ?>
                    <label class="lrst-checkbox-row">
                        <input type="checkbox" name="lrst_excluded_categories[]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo in_array($cat->term_id, $excluded) ? 'checked' : ''; ?>>
                        <div class="info">
                            <strong><?php echo esc_html($cat->name); ?></strong>
                            <span><?php echo esc_html($cat->count); ?> posts</span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if (empty($categories)): ?>
                <p>No categories found.</p>
            <?php endif; ?>
        </div>

        <div class="lrst-save-bar">
            <button type="submit" class="button button-primary button-hero">
                <span class="dashicons dashicons-saved" style="margin-top: 4px;"></span> Save Category Settings
            </button>
        </div>
        <?php
    }

    private function tab_utilities() {
        $widgets = LRST_Utility_Engine::get_all_widgets();
        $hidden_widgets = get_option('lrst_hidden_widgets', array());
        if (!is_array($hidden_widgets)) $hidden_widgets = array();
        ?>
        <div class="lrst-section-header">
            <h2>Utilities & Performance</h2>
            <p>Clean up your single post pages for faster loading and better UX.</p>
        </div>

        <!-- Core Hiding Controls -->
        <div class="lrst-card">
            <h3>🧹 Quick Clean-Up (Single Posts Only)</h3>
            
            <div class="lrst-dual-mode-row">
                <label class="lrst-checkbox-row" style="flex: 1;">
                    <input type="checkbox" name="lrst_hide_partners" value="1" <?php checked(get_option('lrst_hide_partners'), '1'); ?>>
                    <div class="info">
                        <strong>Hide "Our Partners" Widget</strong>
                        <span>Removes partner widget from sidebar</span>
                    </div>
                </label>
                <div class="lrst-mode-select">
                    <select name="lrst_partners_mode">
                        <option value="css" <?php selected(get_option('lrst_partners_mode', 'css'), 'css'); ?>>CSS Hide</option>
                        <option value="server" <?php selected(get_option('lrst_partners_mode', 'css'), 'server'); ?>>Server Remove</option>
                    </select>
                </div>
            </div>

            <div class="lrst-dual-mode-row">
                <label class="lrst-checkbox-row" style="flex: 1;">
                    <input type="checkbox" name="lrst_hide_sidebar" value="1" <?php checked(get_option('lrst_hide_sidebar'), '1'); ?>>
                    <div class="info">
                        <strong>Hide Entire Sidebar</strong>
                        <span>Makes content full-width</span>
                    </div>
                </label>
                <div class="lrst-mode-select">
                    <select name="lrst_sidebar_mode">
                        <option value="css" <?php selected(get_option('lrst_sidebar_mode', 'server'), 'css'); ?>>CSS Hide</option>
                        <option value="server" <?php selected(get_option('lrst_sidebar_mode', 'server'), 'server'); ?>>Server Remove</option>
                    </select>
                </div>
            </div>

            <label class="lrst-checkbox-row">
                <input type="checkbox" name="lrst_hide_related" value="1" <?php checked(get_option('lrst_hide_related'), '1'); ?>>
                <div class="info">
                    <strong>Hide Related Posts</strong>
                    <span>Removes "Related Articles" sections</span>
                </div>
            </label>
            
            <label class="lrst-checkbox-row">
                <input type="checkbox" name="lrst_hide_more_stories" value="1" <?php checked(get_option('lrst_hide_more_stories'), '1'); ?>>
                <div class="info">
                    <strong>Hide "More Stories" / "You May Have Missed"</strong>
                    <span>Removes story recommendation blocks</span>
                </div>
            </label>

            <label class="lrst-checkbox-row">
                <input type="checkbox" name="lrst_highlight_links" value="1" <?php checked(get_option('lrst_highlight_links', '1'), '1'); ?>>
                <div class="info">
                    <strong>Highlight External Links (Bold & Blue)</strong>
                    <span>Make outgoing links stand out (internal links unaffected)</span>
                </div>
            </label>
        </div>

        <!-- Widget Scanner -->
        <div class="lrst-card">
            <h3>🔍 Widget Scanner (<?php echo count($widgets); ?> Detected)</h3>
            <p style="margin-bottom: 15px; color: #64748b;">All active widgets on your site. Check to hide from single posts.</p>
            
            <?php if (!empty($widgets)): ?>
            <div class="lrst-widget-list">
                <?php foreach ($widgets as $widget_id => $widget): ?>
                <label class="lrst-widget-item">
                    <input type="checkbox" name="lrst_hidden_widgets[]" value="<?php echo esc_attr($widget_id); ?>" <?php echo in_array($widget_id, $hidden_widgets) ? 'checked' : ''; ?>>
                    <div class="widget-info">
                        <strong><?php echo esc_html($widget['name']); ?></strong>
                        <span class="widget-meta">ID: <?php echo esc_html($widget_id); ?> | Sidebar: <?php echo esc_html($widget['sidebar']); ?></span>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p>No widgets detected. You may need to add widgets to your sidebars first.</p>
            <?php endif; ?>
        </div>

        <!-- Custom CSS -->
        <div class="lrst-card">
            <h3>🎯 Advanced: Custom CSS Selectors</h3>
            <p>Add your own CSS selectors to hide (one per line).</p>
            <textarea name="lrst_custom_css" class="widefat" rows="5" placeholder=".my-widget-class&#10;#specific-element&#10;[data-widget='unwanted']"><?php echo esc_textarea(get_option('lrst_custom_css')); ?></textarea>
        </div>

        <!-- Improved Save Button -->
        <div class="lrst-save-bar">
            <button type="submit" class="button button-primary button-hero">
                <span class="dashicons dashicons-saved" style="margin-top: 4px;"></span> Save All Utility Settings
            </button>
        </div>
        <?php
    }
}
