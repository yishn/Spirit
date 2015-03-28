document.addEvent('domready', function() {

$$('#toolbox .add a, .userstream li a').addEvent('click', function(event) {
    event.preventDefault();

    $('dialog').load(this.get('href'));
    $('dialog').removeEvents('shown').addEvent('shown', function() {
        $$('#dialog main input')[0].focus();
    });
});

});