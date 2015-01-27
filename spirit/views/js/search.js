document.addEvent('domready', function() {

/**
 * Search
 */

$$('#toolbox .search')
    .setStyle('display', 'block')
    .addEvent('keyup', function(event) {
        if (event.code != 13) return;

        var search = encodeURIComponent(this.getElement('input').get('value'));
        var href = $$('header nav .current a')[0].get('href') + '/search/' + search;

        window.location.href = href;
    });

});