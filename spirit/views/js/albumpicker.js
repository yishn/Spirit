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
            new Element('input', { type: 'text', name: 'search', placeholder: 'Filter' })
        ),
        new Element('div')
    ));

    $$('#albumpicker input').addEvent('change', function() {
        new Request.HTML({
            url: link.get('href') + '/' + encodeURIComponent(this.get('value')),
            update: $$('#albumpicker div')[0],
            onSuccess: function() {
                $$('#albumpicker div ol li a').addEvent('click', function(e) {
                    link.fireEvent('albumlinkclick', [e, this]);
                });
                $('dialog').show();
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