<?php

if (!defined('ABSPATH')) {
    exit;
}

class LRST_Toc_Engine {

    public function __construct() {
        add_filter('the_content', array($this, 'inject_toc'), 10);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public static function get_styles() {
        return array(
            'sidebar-clean' => 'Sidebar Clean',
            'numbered-box' => 'Numbered Box',
            'minimal-list' => 'Minimal List',
            'gradient-border' => 'Gradient Border',
            'floating-widget' => 'Floating Widget',
            'glass-morphism' => 'Glass Morphism',
            'dark-terminal' => 'Dark Terminal',
            'timeline-step' => 'Timeline Steps',
            'accordion-collapse' => 'Accordion (Collapsible)',
            'simple-underline' => 'Simple Underline',
            'dots-bullets' => 'Dots & Bullets',
            'highlight-bold' => 'Highlight Bold',
            'card-icon' => 'Card with Icon',
            'dual-column' => 'Dual Column',
            'neon-glow' => 'Neon Glow'
        );
    }

    public function enqueue_assets() {
        if (!is_single()) return;
        
        // Inline styles for TOC
        $css = "
        /* LRST TOC Base */
        .lrst-toc { margin: 30px 0; padding: 20px; border-radius: 12px; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; position: relative; }
        .lrst-toc-header { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 15px; font-weight: 700; color: #1e293b; cursor: pointer; }
        .lrst-toc-list { list-style: none !important; margin: 0 !important; padding: 0 !important; display: flex; flex-direction: column; gap: 8px; transition: max-height 0.3s ease; overflow: hidden; }
        .lrst-toc-list a { text-decoration: none; color: #475569; font-size: 15px; transition: all 0.2s; display: block; padding: 6px 10px; border-radius: 6px; position: relative; }
        .lrst-toc-list a:hover { background: #f1f5f9; color: #0f172a; }
        .lrst-toc-list a.active { background: #eff6ff; color: #3b82f6; font-weight: 500; }
        .lrst-toc-toggle { background: none; border: none; cursor: pointer; font-size: 12px; padding: 4px; color: #64748b; }
        
        /* 1. Sidebar Clean */
        .lrst-toc-sidebar-clean { border-left: 4px solid #3b82f6; }

        /* 2. Numbered Box */
        .lrst-toc-numbered-box .lrst-toc-list { counter-reset: toc-counter; }
        .lrst-toc-numbered-box .lrst-toc-list a:before { content: counter(toc-counter); counter-increment: toc-counter; background: #3b82f6; color: white; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; margin-right: 10px; }

        /* 3. Minimal List */
        .lrst-toc-minimal-list { background: transparent; border: none; box-shadow: none; padding: 0; }
        .lrst-toc-minimal-list .lrst-toc-header { border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }

        /* 4. Gradient Border */
        .lrst-toc-gradient-border { border: 2px solid transparent; background-image: linear-gradient(white, white), linear-gradient(135deg, #3b82f6, #8b5cf6); background-origin: border-box; background-clip: content-box, border-box; }
        
        /* 5. Floating Widget */
        .lrst-toc-floating-widget { position: fixed; bottom: 20px; right: 20px; width: 300px; z-index: 9999; margin: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.2); transform: translateY(calc(100% - 60px)); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .lrst-toc-floating-widget.expanded { transform: translateY(0); }
        .lrst-toc-floating-widget .lrst-toc-header { margin-bottom: 0; padding: 10px 0; }
        .lrst-toc-floating-widget .lrst-toc-list { max-height: 0; }
        .lrst-toc-floating-widget.expanded .lrst-toc-list { max-height: 500px; overflow-y: auto; margin-top: 15px !important; }

        /* 6. Glass Morphism */
        .lrst-toc-glass-morphism { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
        
        /* 7. Dark Terminal */
        .lrst-toc-dark-terminal { background: #1e293b; border: 1px solid #334155; }
        .lrst-toc-dark-terminal .lrst-toc-header { color: #fff; border-bottom: 1px solid #334155; padding-bottom: 10px; font-family: monospace; }
        .lrst-toc-dark-terminal .lrst-toc-list a { color: #94a3b8; font-family: monospace; }
        .lrst-toc-dark-terminal .lrst-toc-list a:hover { background: #334155; color: #fff; }
        .lrst-toc-dark-terminal .lrst-toc-list a:before { content: '> '; color: #10b981; margin-right: 5px; }

        /* 8. Timeline Steps */
        .lrst-toc-timeline-step .lrst-toc-list { border-left: 2px solid #e2e8f0; margin-left: 10px !important; padding-left: 10px !important; gap: 0; }
        .lrst-toc-timeline-step .lrst-toc-list a { padding-left: 20px; border-radius: 0; }
        .lrst-toc-timeline-step .lrst-toc-list a:before { content: ''; position: absolute; left: -16px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background: white; border: 2px solid #cbd5e1; border-radius: 50%; transition: 0.2s; }
        .lrst-toc-timeline-step .lrst-toc-list a.active:before { background: #3b82f6; border-color: #3b82f6; }
        .lrst-toc-timeline-step .lrst-toc-list a.active { background: transparent; color: #3b82f6; font-weight: 600; }

        /* 9. Accordion */
        .lrst-toc-accordion-collapse .lrst-toc-list { max-height: 0; }
        .lrst-toc-accordion-collapse.open .lrst-toc-list { max-height: 1000px; }

        /* 10. Simple Underline */
        .lrst-toc-simple-underline { background: transparent; border: none; box-shadow: none; padding: 0; }
        .lrst-toc-simple-underline .lrst-toc-header { font-size: 20px; border-bottom: none; }
        .lrst-toc-simple-underline .lrst-toc-list { gap: 4px; }
        .lrst-toc-simple-underline .lrst-toc-list a { padding: 4px 0; border-radius: 0; background: transparent !important; }
        .lrst-toc-simple-underline .lrst-toc-list a.active { color: #3b82f6; text-decoration: underline; text-underline-offset: 4px; }

        /* 11. Dots & Bullets */
        .lrst-toc-dots-bullets .lrst-toc-list a { display: list-item; list-style-type: disc; list-style-position: inside; }
        .lrst-toc-dots-bullets .lrst-toc-list a.active { color: #e11d48; }

        /* 12. Highlight Bold */
        .lrst-toc-highlight-bold { background: #f8fafc; border: none; }
        .lrst-toc-highlight-bold .lrst-toc-list a.active { background: transparent; color: #0f172a; font-weight: 800; border-left: 3px solid #0f172a; border-radius: 0 4px 4px 0; }

        /* 13. Card Icon */
        .lrst-toc-card-icon .lrst-toc-header:before { content: '📑'; font-size: 24px; margin-right: 5px; }
        .lrst-toc-card-icon { background: linear-gradient(to right, #fff, #f8fafc); }

        /* 14. Dual Column */
        .lrst-toc-dual-column .lrst-toc-list { flex-direction: row; flex-wrap: wrap; }
        .lrst-toc-dual-column .lrst-toc-list li { width: 50%; }

        /* 15. Neon Glow */
        .lrst-toc-neon-glow { background: #000; border: 1px solid #333; }
        .lrst-toc-neon-glow .lrst-toc-header { color: #fff; text-transform: uppercase; letter-spacing: 2px; }
        .lrst-toc-neon-glow .lrst-toc-list a { color: #666; font-family: 'Courier New', monospace; }
        .lrst-toc-neon-glow .lrst-toc-list a:hover { color: #fff; text-shadow: 0 0 5px #fff; }
        .lrst-toc-neon-glow .lrst-toc-list a.active { color: #0ff; text-shadow: 0 0 10px #0ff; background: transparent; }

        /* Smooth Scroll */
        html { scroll-behavior: smooth; }
        ";
        
        wp_add_inline_style('wp-block-library', $css);
        
        // Inline JS
        $js = "
        document.addEventListener('DOMContentLoaded', function() {
            const toc = document.querySelector('.lrst-toc');
            if(!toc) return;

            // Accordion / Floating Toggle
            const toggle = toc.querySelector('.lrst-toc-header');
            if(toggle && (toc.classList.contains('lrst-toc-accordion-collapse') || toc.classList.contains('lrst-toc-floating-widget'))) {
                toggle.addEventListener('click', () => {
                    toc.classList.toggle('open');
                    toc.classList.toggle('expanded');
                });
            }

            const links = document.querySelectorAll('.lrst-toc-list a');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        links.forEach(l => l.classList.remove('active'));
                        const id = entry.target.getAttribute('id');
                        const link = document.querySelector(`.lrst-toc-list a[href='#\${id}']`);
                        if(link) link.classList.add('active');
                    }
                });
            }, { rootMargin: '-100px 0px -60% 0px' });
            
            document.querySelectorAll('h2[id]').forEach(h2 => observer.observe(h2));
        });
        ";
        wp_add_inline_script('wp-block-library', $js);
    }

    public function inject_toc($content) {
        if (!is_single() || !in_the_loop() || !is_main_query() || get_option('lrst_toc_enabled') !== '1') {
            return $content;
        }

        // Extract H2s
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $content, $matches, PREG_SET_ORDER);
        $min = intval(get_option('lrst_toc_min_headings', '3'));
        
        if (count($matches) < $min) return $content;

        $headings = array();
        foreach ($matches as $i => $match) {
            $text = strip_tags($match[1]);
            $id = 'toc-' . sanitize_title($text) . '-' . $i;
            $headings[] = array('text' => $text, 'id' => $id);
            
            // Inject ID
            $replacement = '<h2 id="' . $id . '">' . $match[1] . '</h2>';
            // Only replace the first occurrence of this specific string to avoid duplicates if headers are identical
            $content = $this->str_replace_first($match[0], $replacement, $content);
        }

        $toc_html = $this->render_toc($headings);
        
        // Insert before first H2
        return $this->str_replace_first('<h2', $toc_html . '<h2', $content);
    }

    private function render_toc($headings) {
        $style = get_option('lrst_toc_style', 'sidebar-clean');
        $style_class = 'lrst-toc-' . $style;
        
        ob_start();
        ?>
        <div class="lrst-toc <?php echo esc_attr($style_class); ?>">
            <div class="lrst-toc-header">
                <span>Table of Contents</span>
                <?php if ($style === 'accordion-collapse' || $style === 'floating-widget'): ?>
                <span class="lrst-toc-toggle">▼</span>
                <?php endif; ?>
            </div>
            <ul class="lrst-toc-list">
                <?php foreach ($headings as $h): ?>
                    <li><a href="#<?php echo esc_attr($h['id']); ?>"><?php echo esc_html($h['text']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    private function str_replace_first($search, $replace, $subject) {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }
}
