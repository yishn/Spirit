document.addEvent('domready', function() {

/**
 * Monthpicker
 * Requires dialog.js
 */

$$('.monthpicker').addEvent('click', function(event) {
    event.preventDefault();

    $('dialog').load(this.get('href'));
});

});