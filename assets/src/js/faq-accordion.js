import '../scss/faq-styles.scss';
jQuery(document).ready(function($) {
    $('.faq-item__title').on('click', function() {
        var $content = $(this).next('.faq-item__content');
        $('.faq-item__content').not($content).slideUp(); // Close other open items
        $content.slideToggle(); // Toggle the clicked item
    });
});
