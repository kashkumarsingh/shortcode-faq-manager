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
        add_action('wp_ajax_fetch_faqs', [$this, 'fetch_faqs']);
        add_action('wp_ajax_nopriv_fetch_faqs', [$this, 'fetch_faqs']);
    }

    /**
     * Enqueue assets (CSS and JS).
     */
    public function enqueue_assets()
    {
        wp_enqueue_style('faq-styles', plugin_dir_url(__FILE__) . '../assets/build/css/faq-accordion.min.css', [], '1.0.0');
        wp_enqueue_script('faq-accordion', plugin_dir_url(__FILE__) . '../assets/build/js/faq-accordion.min.js', ['jquery'], '1.0.0', true);

        wp_localize_script('faq-accordion', 'sfmData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sfm_nonce'),
        ]);
    }

    /**
     * Renders the FAQ shortcode with tabs and accordion.
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function render_faq_shortcode($atts)
    {
        $atts = shortcode_atts([
            'count' => -1,
            'category' => 'general' // Set default category to 'general'
        ], $atts, 'faqs');

        $categories = get_terms(['taxonomy' => 'faq_category', 'hide_empty' => true]);

        ob_start();
        ?>
        <div class="faq">
            <div class="faq__tabs">
                <ul class="faq__tab-list">
                    <?php foreach ($categories as $category): ?>
                        <li class="faq__tab-item">
                            <a href="#" class="faq__tab-link <?php echo esc_attr($category->slug === 'general' ? 'active' : ''); ?>" data-category="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="faq__list" id="faq-list">
                <?php echo $this->get_faqs('general', $atts['count']); // Load 'general' category by default ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get FAQs based on category and count.
     *
     * @param string $category Category slug.
     * @param int $count Number of FAQs to display.
     * @return string
     */
    private function get_faqs($category, $count)
    {
        $args = [
            'post_type' => 'faq',
            'posts_per_page' => intval($count),
        ];

        if ($category) {
            $args['tax_query'] = [[
                'taxonomy' => 'faq_category',
                'field'    => 'slug',
                'terms'    => $category,
            ]];
        }

        $faqs = new WP_Query($args);
        if (!$faqs->have_posts()) {
            return esc_html__('No FAQs found.', 'shortcode-faq-manager');
        }

        ob_start();
        while ($faqs->have_posts()) {
            $faqs->the_post();
            ?>
            <div class="faq__item">
                <h3 class="faq__item__title"><?php echo esc_html(get_the_title()); ?></h3>
                <div class="faq__item__content" style="display:none;">
                    <?php echo wp_kses_post(get_the_content()); ?>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * AJAX handler to fetch FAQs.
     */
    public function fetch_faqs()
    {
        check_ajax_referer('sfm_nonce', 'nonce');

        $category = sanitize_text_field($_POST['category']);
        $faqs = $this->get_faqs($category, -1);
        wp_send_json_success($faqs);
    }
}
