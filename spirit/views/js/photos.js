document.addEvent('domready', function() {

/**
 * Photostream selection
 */

$$('#toolbox li.edit, #toolbox li.delete').setStyle('visibility', 'hidden');
$$('.photostream li input').removeEvents('change').addEvent('change', function() {
    if (this.checked) this.getParent().addClass('selected');
    else this.getParent().removeClass('selected');
    $$('#toolbox li.edit, #toolbox li.delete').setStyle('visibility', this.checked ? 'visible' : 'hidden');
});

/**
 * Infinite scrolling
 */

$$('#pagination .previous').getParent().setStyle('display', 'none');
$$('#pagination .next').set('text', 'Load more photos').removeEvents('click').addEvent('click', function(e) {
    e.preventDefault();

    var next = this;
    var request = new Request.HTML({ 
        url: next.get('href'), 
        evalScripts: false,
        onSuccess: function(tree, ell, html, js) {
            $$('.photostream').adopt(ell.filter('.photostream')[0].getElements('li'));
            $('pagination').adopt(ell.filter('#pagination')[0].getElements('li'));
            document.fireEvent('domready');
            next.dispose();
        }
    }).get();
});

});