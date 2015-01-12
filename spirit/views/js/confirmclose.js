document.addEvent('domready', function() {

/**
 * Show confirmation dialog when unloading
 * if input fields have been changed.
 */

var confirm = false;
var lambda = function() { confirm = true; };

window.addEvent('beforeunload', function(event) { if (confirm) event.stop(); });

$$('input, textarea').addEvent('change', lambda);
$$('.ace').retrieve('ace').each(function(ace) {
    ace.on('change', lambda);
});

$$('button').addEvent('click', function() { confirm = false; })

});