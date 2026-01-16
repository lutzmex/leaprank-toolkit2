<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Header_Engine {

    public function __construct() {
        add_action('init', array($this, 'init_header'));
    }

    public function init_header() {
        if (get_option('lrst_header_enabled') !== '1') {
            return;
        }

        // Call the render function of the selected template
        $selected = get_option('lrst_selected_template', 'final-perfect');
        $templates = $this->get_all_templates();
        
        if (isset($templates[$selected]) && isset($templates[$selected]['render'])) {
            call_user_func($templates[$selected]['render']);
        } else {
            // Fallback
            $this->render_final_perfect();
        }
    }

    /**
     * Get all available templates
     */
    public function get_all_templates() {
        return array(
            // --- PREMIUM ---
            'final-perfect' => array('name' => 'Final Perfect', 'description' => 'Balanced layout with logo left, navigation right.', 'features' => array('Logo left, nav right', 'Static gray border'), 'render' => array($this, 'render_final_perfect'), 'group' => 'Premium'),
            'compact-centered' => array('name' => 'Compact Centered', 'description' => 'Centered navigation with logo.', 'features' => array('Centered layout', 'Glass morphism'), 'render' => array($this, 'render_compact_centered'), 'group' => 'Premium'),
            'minimal-clean' => array('name' => 'Minimal Clean', 'description' => 'Ultra-minimal design with no borders.', 'features' => array('No borders', 'Clean white bg'), 'render' => array($this, 'render_minimal_clean'), 'group' => 'Premium'),
            'gradient-modern' => array('name' => 'Gradient Modern', 'description' => 'Modern gradient background.', 'features' => array('Gradient bg', 'Animated hover'), 'render' => array($this, 'render_gradient_modern'), 'group' => 'Premium'),
            'corporate-pro' => array('name' => 'Corporate Professional', 'description' => 'Traditional corporate style.', 'features' => array('Conservative', 'High contrast'), 'render' => array($this, 'render_corporate_pro'), 'group' => 'Premium'),
            
            // --- MODERN SAAS ---
            'tech-pill-blue' => array('name' => 'Tech Pill Blue', 'description' => 'Floating pill shape.', 'features' => array('Floating Pill', 'Tech Blue'), 'render' => array($this, 'render_tech_pill_blue'), 'group' => 'Modern SaaS'),
            'startup-split-purple' => array('name' => 'Startup Split Purple', 'description' => 'Bold purple branding.', 'features' => array('Split Layout', 'Purple Gradient'), 'render' => array($this, 'render_startup_split_purple'), 'group' => 'Modern SaaS'),
            'saas-dashboard-look' => array('name' => 'SaaS Dashboard', 'description' => 'App-like header with sidebar toggle.', 'features' => array('Dashboard feel', 'Sidebar icon'), 'render' => array($this, 'render_saas_dashboard_look'), 'group' => 'Modern SaaS'),
            'startup-bold-gradient' => array('name' => 'Startup Bold Gradient', 'description' => 'High energy gradient header.', 'features' => array('Full Gradient', 'White Text'), 'render' => array($this, 'render_startup_bold_gradient'), 'group' => 'Modern SaaS'),
            'minimal-sticky-bar' => array('name' => 'Minimal Sticky Bar', 'description' => 'Stays at top with subtle shadow.', 'features' => array('Sticky', 'Subtle Shadow'), 'render' => array($this, 'render_minimal_sticky_bar'), 'group' => 'Modern SaaS'),

            // --- ENTERPRISE ---
            'news-dense-gray' => array('name' => 'News Dense Gray', 'description' => 'Info dense for news sites.', 'features' => array('Compact', 'Gray Theme'), 'render' => array($this, 'render_news_dense_gray'), 'group' => 'Enterprise'),
            'enterprise-blue-classic' => array('name' => 'Enterprise Blue', 'description' => 'Trustworthy corporate blue.', 'features' => array('Classic Blue', 'Solid'), 'render' => array($this, 'render_enterprise_blue_classic'), 'group' => 'Enterprise'),
            'finance-trust-header' => array('name' => 'Finance Trust', 'description' => 'Secure and professional look.', 'features' => array('Green Accents', 'Secure Icon'), 'render' => array($this, 'render_finance_trust_header'), 'group' => 'Enterprise'),
            'education-lms' => array('name' => 'Education LMS', 'description' => 'Perfect for online courses.', 'features' => array('Clean', 'Focus'), 'render' => array($this, 'render_education_lms'), 'group' => 'Enterprise'),
            'real-estate-pro' => array('name' => 'Real Estate Pro', 'description' => 'Elegant and spacious.', 'features' => array('Gold Accents', 'Serif Fonts'), 'render' => array($this, 'render_real_estate_pro'), 'group' => 'Enterprise'),
            'consulting-expert' => array('name' => 'Consulting Expert', 'description' => 'Authority building header.', 'features' => array('Dark Blue', 'Professional'), 'render' => array($this, 'render_consulting_expert'), 'group' => 'Enterprise'),
            'nonprofit-charity' => array('name' => 'Non-Profit Charity', 'description' => 'Welcoming and open.', 'features' => array('Soft Colors', 'Donate Button'), 'render' => array($this, 'render_nonprofit_charity'), 'group' => 'Enterprise'),

            // --- CREATIVE & LIFESTYLE ---
            'creative-agency-dark' => array('name' => 'Creative Agency Dark', 'description' => 'Dark mode for creatives.', 'features' => array('Dark Mode', 'Glow'), 'render' => array($this, 'render_creative_agency_dark'), 'group' => 'Creative'),
            'creative-split-screen' => array('name' => 'Creative Split', 'description' => 'Asymmetrical artistic layout.', 'features' => array('Asymmetric', 'Bold'), 'render' => array($this, 'render_creative_split_screen'), 'group' => 'Creative'),
            'lifestyle-magazine' => array('name' => 'Lifestyle Magazine', 'description' => 'Elegant typography focus.', 'features' => array('Serif', 'Clean'), 'render' => array($this, 'render_lifestyle_magazine'), 'group' => 'Creative'),
            'restaurant-foodie' => array('name' => 'Restaurant Foodie', 'description' => 'Appetizing design.', 'features' => array('Warm Colors', 'Centered'), 'render' => array($this, 'render_restaurant_foodie'), 'group' => 'Creative'),
            'travel-booking-hero' => array('name' => 'Travel Hero', 'description' => 'Inspires adventure.', 'features' => array('Transparent', 'White Text'), 'render' => array($this, 'render_travel_booking_hero'), 'group' => 'Creative'),
            'gaming-neon-dark' => array('name' => 'Gaming Neon', 'description' => 'High contrast neon.', 'features' => array('Neon', 'Black Bg'), 'render' => array($this, 'render_gaming_neon_dark'), 'group' => 'Creative'),
            'fitness-gym-bold' => array('name' => 'Fitness Bold', 'description' => 'Strong and energetic.', 'features' => array('Italic', 'High Energy'), 'render' => array($this, 'render_fitness_gym_bold'), 'group' => 'Creative'),

            // --- TECH & NICHE ---
            'ecommerce-shop-light' => array('name' => 'E-Commerce Shop', 'description' => 'Shopping focus.', 'features' => array('Cart Icon', 'Clean'), 'render' => array($this, 'render_ecommerce_shop_light'), 'group' => 'Tech'),
            'app-store-style' => array('name' => 'App Store Style', 'description' => 'Like a mobile app store.', 'features' => array('Blur', 'Gray Bg'), 'render' => array($this, 'render_app_store_style'), 'group' => 'Tech'),
            'developer-docs-theme' => array('name' => 'Developer Docs', 'description' => 'Documentation style header.', 'features' => array('Monospace', 'Simple'), 'render' => array($this, 'render_developer_docs_theme'), 'group' => 'Tech'),
            'tech-news-portal' => array('name' => 'Tech News Portal', 'description' => 'Busy tech news site.', 'features' => array('Dense', 'Tech Blue'), 'render' => array($this, 'render_tech_news_portal'), 'group' => 'Tech'),
            'health-clean-green' => array('name' => 'Health Clean', 'description' => 'Medical and wellness.', 'features' => array('Green', 'Trust'), 'render' => array($this, 'render_health_clean_green'), 'group' => 'Tech'),
        );
    }

    // =========================================================================
    // TEMPLATE RENDERS
    // =========================================================================

    public function render_final_perfect() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-final-header{position:fixed;top:8px;left:50%;transform:translateX(-50%);z-index:999999;width:calc(100% - 16px);max-width:1600px;transition:all 0.3s ease,transform 0.3s ease}
            #phm-final-header.scrolled{top:4px;box-shadow:0 6px 24px rgba(15,23,42,0.1)}
            .phm-final-glass{background:rgba(255,255,255,0.88);backdrop-filter:blur(20px) saturate(180%);-webkit-backdrop-filter:blur(20px) saturate(180%);border:1px solid rgba(226,232,240,0.8);border-radius:14px;padding:6px 16px;display:flex;align-items:center;justify-content:space-between;gap:20px;box-shadow:0 2px 16px rgba(15,23,42,0.06), 0 0 0 1px rgba(255,255,255,0.9) inset}
            .phm-final-logo-link{display:flex;align-items:center;justify-content:center;flex-shrink:0;text-decoration:none;padding:6px;border-radius:50%;border:1.5px solid rgba(148,163,184,0.3);background:rgba(255,255,255,0.6);transition:transform 0.2s ease}
            .phm-final-logo-link:hover{transform:scale(1.03)}
            .phm-final-logo{width:auto;height:44px;max-width:160px;object-fit:contain;display:block}
            .phm-final-nav{display:flex;align-items:center;gap:3px;padding:4px;background:rgba(241,245,249,0.7);border-radius:10px;border:1px solid rgba(226,232,240,0.6)}
            .phm-final-link{display:inline-flex;align-items:center;gap:5px;padding:8px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:#475569;text-decoration:none;transition:all 0.2s;white-space:nowrap}
            .phm-final-link:hover{background:rgba(100,116,139,0.08);color:#1e293b}
            .phm-final-link.active{background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;box-shadow:0 2px 8px rgba(59,130,246,0.2)}
            .phm-nav-icon{width:14px;height:14px;stroke-width:2.3}
            .phm-final-toggle{display:none;flex-direction:column;gap:4px;background:none;border:none;padding:8px;cursor:pointer;border-radius:6px}
            .phm-final-toggle span{display:block;width:20px;height:2px;background:#64748b;border-radius:2px}
            @media(max-width:1024px){
                .phm-final-nav{position:absolute;top:100%;left:0;right:0;flex-direction:column;background:white;padding:20px;display:none;box-shadow:0 10px 20px rgba(0,0,0,0.1);border-radius:12px;margin-top:10px;}
                .phm-final-nav.show{display:flex}
                .phm-final-toggle{display:flex}
            }
            body{padding-top:76px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-final'); }, 1);
    }

    public function render_compact_centered() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-compact-header{position:fixed;top:10px;left:50%;transform:translateX(-50%);z-index:999999;width:calc(100% - 20px);max-width:1400px}
            .phm-compact-glass{background:rgba(255,255,255,0.9);backdrop-filter:blur(16px);border:1px solid rgba(226,232,240,0.8);border-radius:16px;padding:8px 24px;display:flex;align-items:center;justify-content:center;gap:24px;box-shadow:0 4px 16px rgba(0,0,0,0.06)}
            .phm-compact-logo-link{padding:6px;border-radius:50%;border:2px solid rgba(59,130,246,0.15);transition:all 0.3s}
            .phm-compact-logo-link:hover{border-color:rgba(59,130,246,0.4);transform:scale(1.05)}
            .phm-compact-logo{height:48px;max-width:180px;object-fit:contain}
            .phm-compact-nav{display:flex;gap:6px}
            .phm-compact-link{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;font-size:14px;font-weight:600;color:#475569;text-decoration:none;transition:all 0.2s}
            .phm-compact-link:hover{background:rgba(59,130,246,0.08);color:#3b82f6}
            .phm-compact-link.active{background:#3b82f6;color:white}
            .phm-nav-icon{width:16px;height:16px}
            .phm-compact-toggle{display:none}
            @media(max-width:1024px){
                .phm-compact-nav{display:none} 
                .phm-compact-glass{justify-content:space-between}
                .phm-compact-toggle{display:block;border:none;background:none;font-size:24px;cursor:pointer}
            }
            body{padding-top:85px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-compact'); }, 1);
    }

    public function render_minimal_clean() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-minimal-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:1px solid #e5e7eb}
            .phm-minimal-container{max-width:1400px;margin:0 auto;padding:12px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-minimal-logo{height:48px;max-width:200px;object-fit:contain}
            .phm-minimal-nav{display:flex;gap:8px}
            .phm-minimal-link{padding:10px 20px;font-size:14px;font-weight:600;color:#64748b;text-decoration:none;border-radius:8px;transition:all 0.2s;display:flex;align-items:center;gap:6px}
            .phm-minimal-link:hover{color:#1e293b;background:#f8fafc}
            .phm-minimal-link.active{color:#3b82f6;background:#eff6ff}
            .phm-nav-icon{width:16px;height:16px}
            .phm-minimal-toggle{display:none;background:none;border:none;cursor:pointer}
            .phm-minimal-toggle span{display:block;width:24px;height:2px;background:#333;margin:4px 0}
            @media(max-width:1024px){
                .phm-minimal-nav{display:none}
                .phm-minimal-toggle{display:block}
            }
            body{padding-top:73px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-minimal'); }, 1);
    }

    public function render_gradient_modern() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-gradient-header{position:fixed;top:10px;left:50%;transform:translateX(-50%);z-index:999999;width:calc(100% - 20px);max-width:1500px}
            .phm-gradient-glass{background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(168,85,247,0.1));backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.3);border-radius:20px;padding:10px 28px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 8px 32px rgba(0,0,0,0.08)}
            .phm-gradient-logo-link{padding:8px;border-radius:50%;background:white;box-shadow:0 4px 12px rgba(99,102,241,0.15);transition:all 0.3s}
            .phm-gradient-logo-link:hover{transform:scale(1.08) rotate(3deg)}
            .phm-gradient-logo{height:52px;max-width:200px;object-fit:contain}
            .phm-gradient-nav{display:flex;gap:4px;padding:6px;background:rgba(255,255,255,0.4);border-radius:12px}
            .phm-gradient-link{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;font-size:14px;font-weight:600;color:#64748b;text-decoration:none;transition:all 0.3s}
            .phm-gradient-link:hover{background:rgba(255,255,255,0.8);color:#6366f1;transform:translateY(-2px)}
            .phm-gradient-link.active{background:linear-gradient(135deg,#6366f1,#a855f7);color:white;box-shadow:0 4px 16px rgba(99,102,241,0.4)}
            .phm-nav-icon{width:16px;height:16px}
            .phm-gradient-toggle{display:none;background:none;border:none}
            .phm-gradient-toggle span{display:block;width:24px;height:2px;background:#6366f1;margin:4px 0}
            @media(max-width:1024px){.phm-gradient-nav{display:none}.phm-gradient-toggle{display:block}}
            body{padding-top:90px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-gradient'); }, 1);
    }

    public function render_corporate_pro() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-corporate-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:2px solid #e5e7eb;box-shadow:0 2px 8px rgba(0,0,0,0.04)}
            .phm-corporate-container{max-width:1400px;margin:0 auto;padding:16px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-corporate-logo-link{padding:8px;border:2px solid #e5e7eb;border-radius:8px;transition:border-color 0.2s}
            .phm-corporate-logo-link:hover{border-color:#3b82f6}
            .phm-corporate-logo{height:48px;max-width:200px;object-fit:contain}
            .phm-corporate-nav{display:flex;gap:4px;padding:4px;background:#f8fafc;border-radius:8px}
            .phm-corporate-link{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:6px;font-size:14px;font-weight:600;color:#334155;text-decoration:none;transition:all 0.2s}
            .phm-corporate-link:hover{background:#e2e8f0;color:#1e293b}
            .phm-corporate-link.active{background:#3b82f6;color:white}
            .phm-nav-icon{width:16px;height:16px}
            .phm-corporate-toggle{display:none;background:none;border:none}
            .phm-corporate-toggle span{display:block;width:24px;height:2px;background:#1e293b;margin:4px 0}
            @media(max-width:1024px){.phm-corporate-nav{display:none}.phm-corporate-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-corporate'); }, 1);
    }

    // --- ADDITIONAL TEMPLATES ---

    public function render_tech_pill_blue() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-tech-pill-header{position:fixed;top:15px;left:50%;transform:translateX(-50%);z-index:999999;width:auto;max-width:90%;}
            .phm-tech-pill-glass{background:#1e293b;border-radius:50px;padding:8px 30px;display:flex;align-items:center;gap:30px;box-shadow:0 10px 25px rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.1)}
            .phm-tech-pill-logo{height:32px;filter:brightness(0) invert(1);}
            .phm-tech-pill-nav{display:flex;gap:5px}
            .phm-tech-pill-link{color:#94a3b8;text-decoration:none;font-weight:500;font-size:14px;padding:8px 16px;border-radius:20px;transition:0.3s;display:flex;align-items:center;gap:6px}
            .phm-tech-pill-link:hover{color:white;background:rgba(255,255,255,0.1)}
            .phm-tech-pill-link.active{background:#3b82f6;color:white;box-shadow:0 4px 12px rgba(59,130,246,0.4)}
            .phm-nav-icon{width:14px;height:14px}
            .phm-tech-pill-toggle{display:none;filter:invert(1);background:none;border:none}
            .phm-tech-pill-toggle span{display:block;width:20px;height:2px;background:white;margin:4px 0}
            @media(max-width:1024px){.phm-tech-pill-nav{display:none}.phm-tech-pill-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-tech-pill'); }, 1);
    }

    public function render_startup_split_purple() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-startup-split-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:1px solid #f3e8ff}
            .phm-startup-split-container{max-width:1400px;margin:0 auto;padding:15px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-startup-split-logo{height:44px;}
            .phm-startup-split-nav{display:flex;gap:20px}
            .phm-startup-split-link{color:#581c87;text-decoration:none;font-weight:600;font-size:15px;position:relative;padding:5px 0;display:flex;align-items:center;gap:6px}
            .phm-startup-split-link:after{content:'';position:absolute;bottom:0;left:0;width:0;height:2px;background:#9333ea;transition:0.3s}
            .phm-startup-split-link:hover:after,.phm-startup-split-link.active:after{width:100%}
            .phm-nav-icon{width:16px;height:16px;color:#9333ea}
            .phm-startup-split-toggle{display:none;background:none;border:none}
            .phm-startup-split-toggle span{display:block;width:24px;height:3px;background:#581c87;margin:4px 0;border-radius:3px}
            @media(max-width:1024px){.phm-startup-split-nav{display:none}.phm-startup-split-toggle{display:block}}
            body{padding-top:76px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-startup-split'); }, 1);
    }

    public function render_news_dense_gray() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-news-dense-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#f8fafc;border-bottom:1px solid #e2e8f0}
            .phm-news-dense-container{max-width:1600px;margin:0 auto;padding:8px 30px;display:flex;align-items:center;gap:30px}
            .phm-news-dense-logo{height:36px;}
            .phm-news-dense-nav{display:flex;gap:15px;flex-wrap:wrap;flex:1}
            .phm-news-dense-link{font-family:serif;color:#334155;text-decoration:none;font-size:16px;font-weight:500;padding:4px 8px;border-radius:4px;display:flex;align-items:center;gap:4px}
            .phm-news-dense-link:hover{background:#e2e8f0;color:#0f172a}
            .phm-news-dense-link.active{background:#334155;color:white}
            .phm-nav-icon{width:14px;height:14px}
            .phm-news-dense-toggle{display:none;background:none;border:none}
            .phm-news-dense-toggle span{display:block;width:24px;height:2px;background:#333;margin:4px 0}
            @media(max-width:1024px){.phm-news-dense-nav{display:none}.phm-news-dense-toggle{display:block}.phm-news-dense-container{justify-content:space-between}}
            body{padding-top:54px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-news-dense'); }, 1);
    }

    public function render_creative_agency_dark() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-creative-agency-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:black;color:white;}
            .phm-creative-agency-container{max-width:1400px;margin:0 auto;padding:20px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-creative-agency-logo{height:40px;filter:invert(1);}
            .phm-creative-agency-nav{display:flex;gap:30px}
            .phm-creative-agency-link{color:#a3a3a3;text-decoration:none;font-size:14px;text-transform:uppercase;letter-spacing:1px;transition:0.3s;display:flex;align-items:center;gap:8px}
            .phm-creative-agency-link:hover{color:white;text-shadow:0 0 10px rgba(255,255,255,0.5)}
            .phm-creative-agency-link.active{color:white;border-bottom:1px solid white}
            .phm-nav-icon{width:14px;height:14px}
            .phm-creative-agency-toggle{display:none;background:none;border:none}
            .phm-creative-agency-toggle span{display:block;width:24px;height:2px;background:white;margin:6px 0}
            @media(max-width:1024px){.phm-creative-agency-nav{display:none}.phm-creative-agency-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-creative-agency'); }, 1);
    }

    public function render_ecommerce_shop_light() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-ecommerce-shop-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:1px solid #f1f5f9;box-shadow:0 2px 10px rgba(0,0,0,0.03)}
            .phm-ecommerce-shop-container{max-width:1400px;margin:0 auto;padding:15px 30px;display:grid;grid-template-columns:1fr auto 1fr;align-items:center}
            .phm-ecommerce-shop-logo{height:48px;margin:0 auto;}
            .phm-ecommerce-shop-nav{display:flex;gap:16px;}
            .phm-ecommerce-shop-link{color:#475569;text-decoration:none;font-weight:600;font-size:14px;display:flex;align-items:center;gap:6px;padding:8px 12px;border-radius:6px;transition:0.2s}
            .phm-ecommerce-shop-link:hover{background:#f1f5f9;color:#0f172a}
            .phm-ecommerce-shop-link.active{color:#f59e0b}
            .phm-nav-icon{width:18px;height:18px;color:#94a3b8}
            .phm-ecommerce-shop-toggle{display:none;background:none;border:none}
            .phm-ecommerce-shop-toggle span{display:block;width:24px;height:2px;background:#333;margin:5px 0}
            .phm-ecommerce-right{display:flex;justify-content:flex-end;gap:15px}
            @media(max-width:1024px){.phm-ecommerce-shop-container{display:flex;justify-content:space-between}.phm-ecommerce-shop-nav{display:none}.phm-ecommerce-shop-toggle{display:block}}
            body{padding-top:79px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-ecommerce-shop'); }, 1);
    }

    public function render_saas_dashboard_look() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-saas-dashboard-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#f8fafc;border-bottom:1px solid #cbd5e1;height:64px;display:flex;align-items:center;padding:0 24px}
            .phm-saas-dashboard-container{width:100%;max-width:1600px;margin:0 auto;display:flex;align-items:center;justify-content:space-between}
            .phm-saas-dashboard-logo{height:32px}
            .phm-saas-dashboard-nav{display:flex;gap:4px;background:#e2e8f0;padding:4px;border-radius:8px}
            .phm-saas-dashboard-link{color:#475569;text-decoration:none;font-weight:500;font-size:14px;padding:6px 12px;border-radius:6px;transition:0.2s;display:flex;align-items:center;gap:6px}
            .phm-saas-dashboard-link:hover{color:#0f172a;background:white;box-shadow:0 1px 2px rgba(0,0,0,0.05)}
            .phm-saas-dashboard-link.active{color:#2563eb;background:white;box-shadow:0 1px 2px rgba(0,0,0,0.05);font-weight:600}
            .phm-nav-icon{width:14px;height:14px}
            .phm-saas-dashboard-toggle{display:none;background:none;border:none}
            .phm-saas-dashboard-toggle span{display:block;width:20px;height:2px;background:#334155;margin:4px 0}
            @media(max-width:1024px){.phm-saas-dashboard-nav{display:none}.phm-saas-dashboard-toggle{display:block}}
            body{padding-top:64px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-saas-dashboard'); }, 1);
    }

    public function render_startup_bold_gradient() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-startup-bold-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:linear-gradient(90deg, #ec4899, #8b5cf6, #3b82f6);color:white}
            .phm-startup-bold-container{max-width:1400px;margin:0 auto;padding:15px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-startup-bold-logo{height:40px;filter:brightness(0) invert(1)}
            .phm-startup-bold-nav{display:flex;gap:20px}
            .phm-startup-bold-link{color:rgba(255,255,255,0.9);text-decoration:none;font-weight:600;font-size:15px;padding:8px 16px;border-radius:20px;background:rgba(255,255,255,0.1);transition:0.3s;display:flex;align-items:center;gap:6px}
            .phm-startup-bold-link:hover{background:white;color:#8b5cf6}
            .phm-startup-bold-link.active{background:white;color:#8b5cf6;box-shadow:0 4px 12px rgba(0,0,0,0.2)}
            .phm-nav-icon{width:16px;height:16px}
            .phm-startup-bold-toggle{display:none;background:none;border:none}
            .phm-startup-bold-toggle span{display:block;width:24px;height:2px;background:white;margin:4px 0}
            @media(max-width:1024px){.phm-startup-bold-nav{display:none}.phm-startup-bold-toggle{display:block}}
            body{padding-top:74px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-startup-bold'); }, 1);
    }

    public function render_minimal_sticky_bar() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-minimal-sticky-header{position:sticky;top:0;z-index:999999;background:white;box-shadow:0 1px 0 #f1f5f9;transition:0.3s}
            #phm-minimal-sticky-header.scrolled{box-shadow:0 4px 12px rgba(0,0,0,0.05)}
            .phm-minimal-sticky-container{max-width:1400px;margin:0 auto;padding:12px 30px;display:flex;align-items:center;justify-content:space-between}
            .phm-minimal-sticky-logo{height:36px}
            .phm-minimal-sticky-nav{display:flex;gap:24px}
            .phm-minimal-sticky-link{color:#64748b;text-decoration:none;font-weight:500;font-size:14px;position:relative;display:flex;align-items:center;gap:6px}
            .phm-minimal-sticky-link:after{content:'';position:absolute;bottom:-18px;left:0;right:0;height:2px;background:#0f172a;transform:scaleX(0);transition:0.2s}
            .phm-minimal-sticky-link:hover{color:#0f172a}
            .phm-minimal-sticky-link.active{color:#0f172a}
            .phm-minimal-sticky-link.active:after{transform:scaleX(1)}
            .phm-nav-icon{width:14px;height:14px}
            .phm-minimal-sticky-toggle{display:none;background:none;border:none}
            .phm-minimal-sticky-toggle span{display:block;width:20px;height:2px;background:#0f172a;margin:4px 0}
            @media(max-width:1024px){.phm-minimal-sticky-nav{display:none}.phm-minimal-sticky-toggle{display:block}}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-minimal-sticky'); }, 1);
    }

    public function render_enterprise_blue_classic() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-enterprise-blue-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#1e40af;border-bottom:4px solid #1e3a8a}
            .phm-enterprise-blue-container{max-width:1400px;margin:0 auto;padding:0 40px;display:flex;align-items:center;height:70px;justify-content:space-between}
            .phm-enterprise-blue-logo{height:40px;filter:brightness(0) invert(1)}
            .phm-enterprise-blue-nav{display:flex;height:100%}
            .phm-enterprise-blue-link{color:#bfdbfe;text-decoration:none;font-weight:600;font-size:14px;padding:0 16px;display:flex;align-items:center;height:100%;transition:0.2s;gap:6px}
            .phm-enterprise-blue-link:hover{color:white;background:#1e3a8a}
            .phm-enterprise-blue-link.active{color:white;background:#172554}
            .phm-nav-icon{width:16px;height:16px}
            .phm-enterprise-blue-toggle{display:none;background:none;border:none}
            .phm-enterprise-blue-toggle span{display:block;width:24px;height:2px;background:white;margin:5px 0}
            @media(max-width:1024px){.phm-enterprise-blue-nav{display:none}.phm-enterprise-blue-toggle{display:block}}
            body{padding-top:74px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-enterprise-blue'); }, 1);
    }

    public function render_finance_trust_header() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-finance-trust-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-top:4px solid #059669;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05)}
            .phm-finance-trust-container{max-width:1400px;margin:0 auto;padding:15px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-finance-trust-logo{height:42px}
            .phm-finance-trust-nav{display:flex;gap:24px}
            .phm-finance-trust-link{color:#064e3b;text-decoration:none;font-weight:700;font-size:14px;text-transform:uppercase;letter-spacing:0.5px;padding:8px 0;border-bottom:2px solid transparent;transition:0.2s;display:flex;align-items:center;gap:6px}
            .phm-finance-trust-link:hover{color:#059669}
            .phm-finance-trust-link.active{color:#059669;border-bottom-color:#059669}
            .phm-nav-icon{width:14px;height:14px}
            .phm-finance-trust-toggle{display:none;background:none;border:none}
            .phm-finance-trust-toggle span{display:block;width:24px;height:2px;background:#064e3b;margin:4px 0}
            @media(max-width:1024px){.phm-finance-trust-nav{display:none}.phm-finance-trust-toggle{display:block}}
            body{padding-top:76px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-finance-trust'); }, 1);
    }

    public function render_creative_split_screen() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-creative-split-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:2px solid black}
            .phm-creative-split-container{max-width:100%;margin:0;padding:0;display:flex;height:80px}
            .phm-creative-split-logo-link{background:black;display:flex;align-items:center;justify-content:center;padding:0 40px;height:100%}
            .phm-creative-split-logo{height:40px;filter:invert(1)}
            .phm-creative-split-nav{display:flex;align-items:center;gap:30px;padding:0 40px;flex:1}
            .phm-creative-split-link{color:black;text-decoration:none;font-weight:700;font-size:16px;text-transform:lowercase;display:flex;align-items:center;gap:6px}
            .phm-creative-split-link:hover{text-decoration:underline}
            .phm-creative-split-link.active{background:black;color:white;padding:4px 10px}
            .phm-nav-icon{width:16px;height:16px}
            .phm-creative-split-toggle{display:none;background:none;border:none;margin-right:20px}
            .phm-creative-split-toggle span{display:block;width:24px;height:3px;background:black;margin:4px 0}
            @media(max-width:1024px){.phm-creative-split-nav{display:none}.phm-creative-split-toggle{display:block}.phm-creative-split-container{justify-content:space-between;align-items:center}}
            body{padding-top:82px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-creative-split'); }, 1);
    }

    public function render_app_store_style() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-app-store-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:rgba(245,245,247,0.8);backdrop-filter:blur(20px);border-bottom:1px solid rgba(0,0,0,0.1)}
            .phm-app-store-container{max-width:1200px;margin:0 auto;padding:12px 20px;display:flex;align-items:center;justify-content:space-between}
            .phm-app-store-logo{height:36px;border-radius:8px}
            .phm-app-store-nav{display:flex;gap:8px}
            .phm-app-store-link{color:#1d1d1f;text-decoration:none;font-size:13px;padding:6px 12px;border-radius:15px;background:white;box-shadow:0 1px 3px rgba(0,0,0,0.05);transition:0.2s;display:flex;align-items:center;gap:4px}
            .phm-app-store-link:hover{background:#0071e3;color:white}
            .phm-app-store-link.active{background:#1d1d1f;color:white}
            .phm-nav-icon{width:12px;height:12px}
            .phm-app-store-toggle{display:none;background:none;border:none}
            .phm-app-store-toggle span{display:block;width:20px;height:2px;background:#1d1d1f;margin:4px 0}
            @media(max-width:1024px){.phm-app-store-nav{display:none}.phm-app-store-toggle{display:block}}
            body{padding-top:64px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-app-store'); }, 1);
    }

    public function render_developer_docs_theme() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-developer-docs-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#0d1117;border-bottom:1px solid #30363d}
            .phm-developer-docs-container{max-width:1600px;margin:0 auto;padding:16px 32px;display:flex;align-items:center;justify-content:space-between}
            .phm-developer-docs-logo{height:32px;filter:invert(1)}
            .phm-developer-docs-nav{display:flex;gap:24px}
            .phm-developer-docs-link{color:#c9d1d9;text-decoration:none;font-family:monospace;font-size:14px;display:flex;align-items:center;gap:6px}
            .phm-developer-docs-link:hover{color:#58a6ff}
            .phm-developer-docs-link.active{color:#58a6ff;border-bottom:1px solid #58a6ff}
            .phm-nav-icon{width:14px;height:14px}
            .phm-developer-docs-toggle{display:none;background:none;border:none}
            .phm-developer-docs-toggle span{display:block;width:20px;height:2px;background:#c9d1d9;margin:4px 0}
            @media(max-width:1024px){.phm-developer-docs-nav{display:none}.phm-developer-docs-toggle{display:block}}
            body{padding-top:65px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-developer-docs'); }, 1);
    }

    public function render_health_clean_green() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-health-clean-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:1px solid #e5e7eb}
            .phm-health-clean-container{max-width:1200px;margin:0 auto;padding:12px 20px;display:flex;align-items:center;justify-content:space-between}
            .phm-health-clean-logo{height:44px}
            .phm-health-clean-nav{display:flex;gap:8px}
            .phm-health-clean-link{color:#166534;text-decoration:none;font-size:15px;padding:8px 16px;border-radius:20px;background:#f0fdf4;display:flex;align-items:center;gap:6px}
            .phm-health-clean-link:hover{background:#dcfce7}
            .phm-health-clean-link.active{background:#16a34a;color:white}
            .phm-nav-icon{width:16px;height:16px}
            .phm-health-clean-toggle{display:none;background:none;border:none}
            .phm-health-clean-toggle span{display:block;width:22px;height:2px;background:#166534;margin:4px 0}
            @media(max-width:1024px){.phm-health-clean-nav{display:none}.phm-health-clean-toggle{display:block}}
            body{padding-top:76px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-health-clean'); }, 1);
    }

    public function render_lifestyle_magazine() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-lifestyle-mag-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;border-bottom:1px solid black}
            .phm-lifestyle-mag-container{max-width:1400px;margin:0 auto;padding:20px 40px;display:flex;flex-direction:column;align-items:center;gap:15px}
            .phm-lifestyle-mag-logo{height:50px}
            .phm-lifestyle-mag-nav{display:flex;gap:30px;border-top:1px solid #e5e7eb;padding-top:15px;width:100%;justify-content:center}
            .phm-lifestyle-mag-link{color:black;text-decoration:none;font-family:serif;font-style:italic;font-size:16px;display:flex;align-items:center;gap:6px}
            .phm-lifestyle-mag-link:hover{color:#ea580c}
            .phm-lifestyle-mag-link.active{color:#ea580c;font-weight:bold}
            .phm-nav-icon{width:14px;height:14px}
            .phm-lifestyle-mag-toggle{display:none;position:absolute;right:20px;top:20px;background:none;border:none}
            .phm-lifestyle-mag-toggle span{display:block;width:24px;height:1px;background:black;margin:6px 0}
            @media(max-width:1024px){.phm-lifestyle-mag-nav{display:none}.phm-lifestyle-mag-toggle{display:block}.phm-lifestyle-mag-container{flex-direction:row;justify-content:center}}
            body{padding-top:120px!important}
            @media(max-width:1024px){body{padding-top:90px!important}}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-lifestyle-mag'); }, 1);
    }

    public function render_tech_news_portal() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-tech-news-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#0f172a;color:white}
            .phm-tech-news-container{max-width:1600px;margin:0 auto;padding:0 20px;display:flex;align-items:center;height:60px;justify-content:space-between}
            .phm-tech-news-logo{height:28px;filter:invert(1)}
            .phm-tech-news-nav{display:flex;height:100%}
            .phm-tech-news-link{color:#94a3b8;text-decoration:none;font-size:13px;font-weight:600;padding:0 15px;height:100%;display:flex;align-items:center;border-top:3px solid transparent;transition:0.2s;gap:6px}
            .phm-tech-news-link:hover{color:white;background:#1e293b}
            .phm-tech-news-link.active{color:#38bdf8;border-top-color:#38bdf8;background:#1e293b}
            .phm-nav-icon{width:12px;height:12px}
            .phm-tech-news-toggle{display:none;background:none;border:none}
            .phm-tech-news-toggle span{display:block;width:20px;height:2px;background:white;margin:4px 0}
            @media(max-width:1024px){.phm-tech-news-nav{display:none}.phm-tech-news-toggle{display:block}}
            body{padding-top:60px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-tech-news'); }, 1);
    }

    public function render_education_lms() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-education-lms-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#fff7ed;border-bottom:1px solid #fed7aa}
            .phm-education-lms-container{max-width:1200px;margin:0 auto;padding:15px 30px;display:flex;align-items:center;justify-content:space-between}
            .phm-education-lms-logo{height:40px}
            .phm-education-lms-nav{display:flex;gap:20px}
            .phm-education-lms-link{color:#9a3412;text-decoration:none;font-weight:500;font-size:15px;display:flex;align-items:center;gap:6px}
            .phm-education-lms-link:hover{color:#ea580c;text-decoration:underline}
            .phm-education-lms-link.active{background:#ffedd5;padding:6px 12px;border-radius:8px;color:#c2410c}
            .phm-nav-icon{width:16px;height:16px}
            .phm-education-lms-toggle{display:none;background:none;border:none}
            .phm-education-lms-toggle span{display:block;width:24px;height:2px;background:#9a3412;margin:4px 0}
            @media(max-width:1024px){.phm-education-lms-nav{display:none}.phm-education-lms-toggle{display:block}}
            body{padding-top:76px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-education-lms'); }, 1);
    }

    public function render_real_estate_pro() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-real-estate-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#27272a;color:#fafafa}
            .phm-real-estate-container{max-width:1400px;margin:0 auto;padding:20px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-real-estate-logo{height:40px;filter:brightness(0) invert(1)}
            .phm-real-estate-nav{display:flex;gap:30px}
            .phm-real-estate-link{color:#a1a1aa;text-decoration:none;font-family:serif;font-size:16px;letter-spacing:1px;display:flex;align-items:center;gap:6px}
            .phm-real-estate-link:hover{color:#fbbf24}
            .phm-real-estate-link.active{color:#fbbf24;border-bottom:1px solid #fbbf24}
            .phm-nav-icon{width:14px;height:14px}
            .phm-real-estate-toggle{display:none;background:none;border:none}
            .phm-real-estate-toggle span{display:block;width:24px;height:1px;background:white;margin:6px 0}
            @media(max-width:1024px){.phm-real-estate-nav{display:none}.phm-real-estate-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-real-estate'); }, 1);
    }

    public function render_travel_booking_hero() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-travel-hero-header{position:absolute;top:0;left:0;right:0;z-index:999999;background:linear-gradient(to bottom, rgba(0,0,0,0.6), transparent);padding-bottom:40px}
            #phm-travel-hero-header.scrolled{position:fixed;background:white;box-shadow:0 2px 10px rgba(0,0,0,0.1);padding-bottom:0}
            .phm-travel-hero-container{max-width:1400px;margin:0 auto;padding:20px 40px;display:flex;align-items:center;justify-content:space-between}
            .phm-travel-hero-logo{height:44px;filter:brightness(0) invert(1)}
            #phm-travel-hero-header.scrolled .phm-travel-hero-logo{filter:none}
            .phm-travel-hero-nav{display:flex;gap:20px}
            .phm-travel-hero-link{color:white;text-decoration:none;font-weight:600;font-size:15px;display:flex;align-items:center;gap:6px;text-shadow:0 1px 3px rgba(0,0,0,0.3)}
            #phm-travel-hero-header.scrolled .phm-travel-hero-link{color:#333;text-shadow:none}
            .phm-travel-hero-link.active{background:rgba(255,255,255,0.2);padding:6px 14px;border-radius:20px}
            #phm-travel-hero-header.scrolled .phm-travel-hero-link.active{background:#0ea5e9;color:white}
            .phm-nav-icon{width:16px;height:16px}
            .phm-travel-hero-toggle{display:none;background:none;border:none}
            .phm-travel-hero-toggle span{display:block;width:24px;height:2px;background:white;margin:4px 0}
            #phm-travel-hero-header.scrolled .phm-travel-hero-toggle span{background:#333}
            @media(max-width:1024px){.phm-travel-hero-nav{display:none}.phm-travel-hero-toggle{display:block}}
            /* No body padding for absolute header to overlay hero */
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-travel-hero'); }, 1);
    }

    public function render_gaming_neon_dark() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-gaming-neon-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#050505;border-bottom:2px solid #22c55e}
            .phm-gaming-neon-container{max-width:1600px;margin:0 auto;padding:15px 30px;display:flex;align-items:center;justify-content:space-between}
            .phm-gaming-neon-logo{height:40px}
            .phm-gaming-neon-nav{display:flex;gap:30px}
            .phm-gaming-neon-link{color:#4ade80;text-decoration:none;font-family:'Courier New', monospace;font-weight:bold;font-size:16px;text-transform:uppercase;display:flex;align-items:center;gap:8px}
            .phm-gaming-neon-link:hover{text-shadow:0 0 10px #4ade80}
            .phm-gaming-neon-link.active{color:#050505;background:#22c55e;padding:4px 8px;box-shadow:0 0 15px #22c55e}
            .phm-nav-icon{width:16px;height:16px}
            .phm-gaming-neon-toggle{display:none;background:none;border:none}
            .phm-gaming-neon-toggle span{display:block;width:24px;height:3px;background:#22c55e;margin:5px 0}
            @media(max-width:1024px){.phm-gaming-neon-nav{display:none}.phm-gaming-neon-toggle{display:block}}
            body{padding-top:74px!important;background-color:#111}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-gaming-neon'); }, 1);
    }

    public function render_nonprofit_charity() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-nonprofit-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;box-shadow:0 2px 15px rgba(0,0,0,0.05)}
            .phm-nonprofit-container{max-width:1200px;margin:0 auto;padding:15px 20px;display:flex;align-items:center;justify-content:space-between}
            .phm-nonprofit-logo{height:48px}
            .phm-nonprofit-nav{display:flex;gap:20px}
            .phm-nonprofit-link{color:#333;text-decoration:none;font-size:15px;font-weight:500;display:flex;align-items:center;gap:6px}
            .phm-nonprofit-link:hover{color:#dc2626}
            .phm-nonprofit-link.active{color:#dc2626;border-bottom:2px solid #dc2626}
            .phm-nav-icon{width:16px;height:16px}
            .phm-nonprofit-toggle{display:none;background:none;border:none}
            .phm-nonprofit-toggle span{display:block;width:22px;height:2px;background:#333;margin:4px 0}
            @media(max-width:1024px){.phm-nonprofit-nav{display:none}.phm-nonprofit-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-nonprofit'); }, 1);
    }

    public function render_restaurant_foodie() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-restaurant-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#fff;border-bottom:1px solid #fb923c}
            .phm-restaurant-container{max-width:1200px;margin:0 auto;padding:15px 30px;display:flex;align-items:center;justify-content:center;flex-direction:column}
            .phm-restaurant-logo{height:50px;margin-bottom:10px}
            .phm-restaurant-nav{display:flex;gap:25px}
            .phm-restaurant-link{color:#7c2d12;text-decoration:none;font-weight:600;font-size:15px;text-transform:uppercase;display:flex;align-items:center;gap:6px}
            .phm-restaurant-link:hover{color:#ea580c}
            .phm-restaurant-link.active{color:#ea580c;border-bottom:2px dotted #ea580c}
            .phm-nav-icon{width:16px;height:16px}
            .phm-restaurant-toggle{display:none;position:absolute;left:20px;top:20px;background:none;border:none}
            .phm-restaurant-toggle span{display:block;width:24px;height:2px;background:#7c2d12;margin:4px 0}
            @media(max-width:1024px){.phm-restaurant-nav{display:none}.phm-restaurant-toggle{display:block}.phm-restaurant-container{padding-top:10px}}
            body{padding-top:100px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-restaurant'); }, 1);
    }

    public function render_fitness_gym_bold() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-fitness-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:white;transform:skewY(-1deg);transform-origin:top left;border-bottom:4px solid #ef4444;top:-10px;padding-top:10px}
            .phm-fitness-container{max-width:1400px;margin:0 auto;padding:10px 40px;display:flex;align-items:center;justify-content:space-between;transform:skewY(1deg)}
            .phm-fitness-logo{height:44px}
            .phm-fitness-nav{display:flex;gap:20px}
            .phm-fitness-link{color:#1f2937;text-decoration:none;font-weight:900;font-size:16px;font-style:italic;text-transform:uppercase;display:flex;align-items:center;gap:4px}
            .phm-fitness-link:hover{color:#ef4444}
            .phm-fitness-link.active{color:#ef4444}
            .phm-nav-icon{width:16px;height:16px}
            .phm-fitness-toggle{display:none;background:none;border:none}
            .phm-fitness-toggle span{display:block;width:26px;height:4px;background:#1f2937;margin:4px 0;transform:skewX(-10deg)}
            @media(max-width:1024px){.phm-fitness-nav{display:none}.phm-fitness-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-fitness'); }, 1);
    }

    public function render_consulting_expert() {
        add_action('wp_head', function() {
            ?>
            <style>
            .mid-header-wrapper,.site-header,#masthead,nav.navigation,.main-navigation,header.header{display:none!important}
            #phm-consulting-header{position:fixed;top:0;left:0;right:0;z-index:999999;background:#172554;color:white}
            .phm-consulting-container{max-width:1200px;margin:0 auto;padding:0 30px;display:flex;align-items:center;justify-content:space-between;height:80px}
            .phm-consulting-logo{height:40px;filter:brightness(0) invert(1)}
            .phm-consulting-nav{display:flex;gap:2px;background:#1e3a8a;padding:4px;border-radius:40px}
            .phm-consulting-link{color:#93c5fd;text-decoration:none;font-size:14px;font-weight:500;padding:8px 20px;border-radius:30px;transition:0.3s;display:flex;align-items:center;gap:6px}
            .phm-consulting-link:hover{color:white}
            .phm-consulting-link.active{background:#2563eb;color:white;box-shadow:0 2px 10px rgba(0,0,0,0.2)}
            .phm-nav-icon{width:14px;height:14px}
            .phm-consulting-toggle{display:none;background:none;border:none}
            .phm-consulting-toggle span{display:block;width:22px;height:2px;background:white;margin:5px 0}
            @media(max-width:1024px){.phm-consulting-nav{display:none}.phm-consulting-toggle{display:block}}
            body{padding-top:80px!important}
            </style>
            <?php
        }, 1);
        add_action('wp_body_open', function() { $this->render_header_html('phm-consulting'); }, 1);
    }

    // =========================================================================
    // HELPER: RENDER HTML STRUCTURE
    // =========================================================================

    private function render_header_html($prefix) {
        $home = home_url('/');
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : get_site_icon_url(100);
        $site_name = get_bloginfo('name');
        
        if (!$logo) {
            $logo = 'https://via.placeholder.com/240x60/3b82f6/ffffff?text=' . urlencode($site_name);
        }
        
        // Add universal sticky behavior CSS
        ?>
        <style>
        [id*="-header"] {
            transition: transform 0.3s ease, top 0.3s ease, box-shadow 0.3s ease !important;
        }
        </style>
        <?php
        
        // Exact 7-8 categories from prompt
        $categories = array(
            array('url' => $home, 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Home'),
            array('url' => $home . 'category/business-consumer-services/', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Business'),
            array('url' => $home . 'category/computers-electronics-technology/', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Technology'),
            array('url' => $home . 'category/finance/', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Finance'),
            array('url' => $home . 'category/health/', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Health'),
            array('url' => $home . 'category/lifestyle/', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Lifestyle'),
            array('url' => $home . 'category/ecommerce-shopping/', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'label' => 'Shopping'),
            array('url' => $home . 'category/arts-entertainment/', 'icon' => 'M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z', 'label' => 'Entertainment'),
        );
        ?>
        <header id="<?php echo esc_attr($prefix); ?>-header">
            <div class="<?php echo esc_attr($prefix); ?>-glass <?php echo esc_attr($prefix); ?>-container">
                <a href="<?php echo esc_url($home); ?>" class="<?php echo esc_attr($prefix); ?>-logo-link">
                    <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($site_name); ?>" class="<?php echo esc_attr($prefix); ?>-logo">
                </a>
                
                <nav class="<?php echo esc_attr($prefix); ?>-nav" id="<?php echo esc_attr($prefix); ?>Nav">
                    <?php foreach ($categories as $i => $cat): ?>
                    <a href="<?php echo esc_url($cat['url']); ?>" class="<?php echo esc_attr($prefix); ?>-link <?php echo $i === 0 ? 'active' : ''; ?>">
                        <svg class="<?php echo esc_attr($prefix); ?>-nav-icon phm-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr($cat['icon']); ?>"/>
                        </svg>
                        <?php echo esc_html($cat['label']); ?>
                    </a>
                    <?php endforeach; ?>
                </nav>
                
                <button class="<?php echo esc_attr($prefix); ?>-toggle" onclick="document.getElementById('<?php echo esc_js($prefix); ?>Nav').classList.toggle('show')">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </header>
        
        <script>
        (function() {
            var stickyMode = '<?php echo esc_js(get_option('lrst_sticky_mode', 'smart')); ?>';
            var header = document.getElementById('<?php echo esc_js($prefix); ?>-header');
            if (!header) return;
            
            window.addEventListener('scroll', function() {
                var currentScroll = window.pageYOffset;
                
                // Add 'scrolled' class for visual effects
                if (currentScroll > 20) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                
                // Static Mode: Remove fixed positioning (scrolls with page)
                if (stickyMode === 'static') {
                    header.style.position = 'absolute';
                    header.style.top = '0';
                }
                
                // Smart and Fixed modes both keep header fixed at top
                // (Smart mode removed due to layout issues, behaves as Fixed)
            });
        })();
        </script>
        <?php
    }
}
