document.addEvent('domready', function() {

/**
 * Photostream selection
 */

$$('#toolbox li.edit, #toolbox li.delete').setStyle(
    'display', $$('.photostream .selected').length != 0 ? 'block' : 'none'
);

$$('.photostream li input').removeEvents('change').addEvent('change', function() {
    if (this.checked) this.getParent().addClass('selected');
    else this.getParent().removeClass('selected');
    $$('#toolbox li.edit, #toolbox li.delete').setStyle(
        'display', $$('.photostream .selected').length != 0 ? 'block' : 'none'
    );
});

/**
 * Load more button
 */

var throbbersrc = $$('.throbber').get('src');

$$('#pagination .previous').getParent().dispose();
$$('#pagination .next').set('text', 'Load more photos').removeEvents('click').addEvent('click', function(e) {
    e.preventDefault();

    this.set('html', '<img src="' + throbbersrc[0] + '" alt="Loading&hellip;" />');

    var next = this;
    var request = new Request.HTML({ 
        url: next.get('href'), 
        evalScripts: false,
        onSuccess: function(tree, ell, html, js) {
            $$('.photostream').adopt(ell.filter('.photostream')[0].getElements('li'));
            $('pagination').adopt(ell.filter('#pagination')[0].getElements('li'));
            document.fireEvent('domready');
            next.getParent().dispose();
        }
    }).get();
});

});