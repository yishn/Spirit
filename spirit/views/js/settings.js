document.addEvent('domready', function() {

$$('#toolbox .about a').addEvent('click', function(event) {
    event.preventDefault();
    var link = this;

    $('dialog').load(this.get('href'));
    $('dialog').removeEvents('shown').addEvent('shown', function() {
        $$('#dialog main').grab(new Element('p', { style: 'text-align: right;' }).grab(new Element('button', { text: 'Close' })));
        $$('#dialog main button').addEvent('click', $('dialog').close);
    });
});

});