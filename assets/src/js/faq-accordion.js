import '../scss/faq-styles.scss';

jQuery(document).ready(function($) {
    // Handle tab switching
    $('.faq__tab-link').on('click', function(e) {
        e.preventDefault();

        // Remove active class from all tabs and hide all content
        $('.faq__tab-link').removeClass('active');
        $('.faq__item__content').hide(); // Hide all content first

        // Add active class to the clicked tab
        $(this).addClass('active');

        // Get the target content ID from the data attribute instead of href
        const category = $(this).data('category');
        $('#faq-list').data('category', category); // Set the category data for the FAQ list

        // Fetch FAQs based on selected category
        fetchFAQs(category);
    });

    // Function to fetch FAQs via AJAX
    function fetchFAQs(category) {
        $.ajax({
            url: sfmData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'fetch_faqs',
                category: category,
                nonce: sfmData.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#faq-list').html(response.data);
                } else {
                    $('#faq-list').html('<p>Error retrieving FAQs.</p>'); // Static error message
                }
            },
            error: function() {
                $('#faq-list').html('<p>Error retrieving FAQs.</p>');
            }
        });
    }

    // Handle FAQ accordion toggle
    $(document).on('click', '.faq__item__title', function() {
        var $content = $(this).next('.faq__item__content');
        $('.faq__item__content').not($content).slideUp(); // Close other open items
        $content.slideToggle(); // Toggle the clicked item
    });
});
