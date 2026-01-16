jQuery(document).ready(function($) {
    // Fade in effect for content
    $('.lrst-content').hide().fadeIn(400);

    // Template Card Selection Visuals
    $('.lrst-template-card').click(function() {
        $('.lrst-template-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    // Style Card Selection
    $('.lrst-style-card').click(function() {
        $('.lrst-style-card').removeClass('active');
        $(this).addClass('active');
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    // Smooth scroll to top on save
    if (window.location.search.indexOf('settings-updated=true') > -1) {
        $('html, body').animate({ scrollTop: 0 }, 500);
        $('<div class="lrst-notice">Settings Saved Successfully! 🚀</div>')
            .insertBefore('.lrst-wrap')
            .delay(3000)
            .fadeOut();
    }
});
