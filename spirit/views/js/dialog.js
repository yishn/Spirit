document.addEvent('domready', function() {

var dialog = new Element('div', { id: 'dialog' }).inject($$('body')[0]);
var overlay = new Element('div', { id: 'overlay' }).inject(dialog, 'after');

dialog.load = function(url) {
    dialog.fireEvent('loading');
    overlay.addClass('show');

    var request = new Request.HTML({
        'url': url,
        onSuccess: function(tree, ell, html, js) {
            dialog.empty().adopt(ell.filter('main')[0]);
            dialog.addClass('show');
            dialog.fireEvent('shown');
        }
    }).get();
};

dialog.close = function() {
    dialog.fireEvent('closing');

    dialog.removeClass('show');
    overlay.removeClass('show');

    dialog.fireEvent('closed');
};

overlay.addEvent('click', dialog.close);

});