<?php
class Shortcode_FAQ_Manager
{

    /**
     * Initializes the shortcode and related hooks.
     */
    public function init()
    {
        add_shortcode('faqs', [$this, 'render_faq_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Enqueue assets (CSS and JS).
     */
    public function enqueue_assets()
    {
        // Enqueue minified CSS
        wp_enqueue_style('faq-styles', plugin_dir_url(__FILE__) . '../assets/build/css/faq-accordion.min.css', [], '1.0.0', 'all');

        // Enqueue minified JS
        wp_enqueue_script('faq-accordion', plugin_dir_url(__FILE__) . '../assets/build/js/faq-accordion.min.js', ['jquery'], '1.0.0', true);

        // Localize script for security
        wp_localize_script('faq-accordion', 'sfmData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sfm_nonce'),
        ]);
    }

    /**
     * Renders the FAQ shortcode with accordion functionality.
     * 
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function render_faq_shortcode($atts)
    {
        $atts = shortcode_atts([
            'count' => -1, // Default: show all FAQs
        ], $atts, 'faqs');

        $args = [
            'post_type' => 'faq',
            'posts_per_page' => intval($atts['count']),
        ];

        $faqs = new WP_Query($args);

        if (!$faqs->have_posts()) {
            return esc_html__('No FAQs found.', 'shortcode-faq-manager');
        }

        ob_start();
        ?>
        <div class="faq-list">
            <?php while ($faqs->have_posts()):
                $faqs->the_post(); ?>
                <div class="faq-item">
                    <h3 class="faq-item__title"><?php echo esc_html(get_the_title()); ?></h3>
                    <div class="faq-item__content" style="display: none;">
                        <?php echo wp_kses_post(get_the_content()); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
