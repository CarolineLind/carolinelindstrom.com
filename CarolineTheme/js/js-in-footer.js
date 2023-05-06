console.log('hello world');

jQuery( document ).ready(function() {
    console.log( "ready!" );
    toggleParent(jQuery('.menuButton'));
});

function toggleParent(element) {
    jQuery(element).click(function() {
        jQuery(element).toggleClass("clicked");
        jQuery(element).parent().toggleClass("active");
    });
}; 