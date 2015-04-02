document.addEvent('domready', function() {

/**
 * Albumpicker
 * Requires dialog.js
 */

$$('.albumpicker').removeEvents('click').addEvent('click', function(event) {
    event.preventDefault();
    var link = this;

    $('overlay').addClass('show');

    $('dialog').empty().adopt(new Element('main', { id: 'albumpicker' }).adopt(
        new Element('p').adopt(
            new Element('input', { type: 'text', name: 'search', placeholder: 'Search' })
        ),
        new Element('div')
    ));

    $$('#albumpicker input').addEvent('change', function() {
        new Request.HTML({
            url: link.get('href') + '/' + encodeURIComponent(this.get('value')),
            update: $$('#albumpicker div')[0],
            onSuccess: function() {
                $$('#albumpicker div ol li:not(.add) a').addEvent('click', function(e) {
                    link.fireEvent('albumlinkclick', [e, this]);
                });
                $$('#albumpicker div ol li.add a').addEvent('click', function(e) {
                    e.preventDefault();
                    $('dialog').load(this.get('href')).removeEvents('shown').addEvent('shown', function() {
                        $$('#dialog input')[0].focus();
                        $$('#dialog form')[0].set('send', {
                            onSuccess: function() { link.fireEvent('click', event); }
                        });
                        $$('#dialog form button[type="submit"]').addEvent('click', function(e) {
                            e.preventDefault();
                            this.getParent('form').send();
                        });
                    }).addEvent('closed', function() {
                        $('dialog').removeEvents();
                    });
                });

                $('dialog').removeEvents('shown').show();
                $$('#dialog input')[0].focus();
            }
        }).get();
    }).addEvent('keyup', function(e) {
        if (e.code != 13) return;
        this.fireEvent('change');    
    });

    $$('#albumpicker input').fireEvent('change');
});

});