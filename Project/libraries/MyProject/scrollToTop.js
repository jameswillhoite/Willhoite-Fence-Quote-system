(function() {
    /**
     * Scroll to top
     * via of w3Schools
     */
    jQuery('body').append("<button id=\"scrollToTop\" title=\"Go to top\"><span class=\"fas fa-caret-up\"></span> </button>");
    jQuery(document).on('scroll', function() {
        var btn = jQuery('button#scrollToTop');
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            btn.css("display", "block");
        } else {
            btn.css("display", "none");
        }
    });
    jQuery('button#scrollToTop').on('click', function() {
        jQuery('html, body').animate({
            scrollTop: "0"
        }, "slow");
    });
})();