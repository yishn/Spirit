document.addEvent('domready', function() {

/**
 * Prepare back button
 */

$$('.back a').addEvent('click', function(e) {
    e.preventDefault();
    window.history.back();
});

});