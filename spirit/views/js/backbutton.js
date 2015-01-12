document.addEvent('domready', function() {

/**
 * Prepare back button
 */

$$('.back a').addEvent('click', function(event) {
    event.preventDefault();
    window.history.back();
});

});