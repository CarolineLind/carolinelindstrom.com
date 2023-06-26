jQuery( document ).ready(function() {
    toggleParent(jQuery('.menuButton'));
});

function toggleParent(element) {
    jQuery(element).click(function() {
        jQuery(element).toggleClass("clicked");
        jQuery(element).parent().toggleClass("active");
    });
}; 