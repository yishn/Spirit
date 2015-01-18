document.addEvent('domready', function() {

/**
 * Load more button
 */

var throbbersrc = $$('.throbber').get('src');

$$('#pagination .previous').getParent().dispose();
$$('#pagination .next').set('text', 'Load more').removeEvents('click').addEvent('click', function(e) {
    e.preventDefault();

    this.set('html', '<img src="' + throbbersrc[0] + '" alt="Loading&hellip;" />');

    var next = this;
    var request = new Request.HTML({ 
        url: next.get('href'), 
        evalScripts: false,
        onSuccess: function(tree, ell, html, js) {
            $$('.stream').adopt(ell.filter('.stream')[0].getChildren('li'));
            $('pagination').adopt(ell.filter('#pagination')[0].getElements('li'));

            // Update history
            window.history.pushState(null, null, next.get('href'));

            document.fireEvent('domready');
            next.getParent().dispose();
        }
    }).get();
});

});