document.addEvent('domready', function() {

$$('#toolbox .add a, .userstream li a').addEvent('click', function(event) {
    event.preventDefault();

    $('dialog').load(this.get('href'));
    $('dialog').removeEvents('shown').addEvent('shown', function() {
        $$('#dialog main input')[0].focus();
        $$('#dialog .delete').addEvent('click', function(event) {
            if (!window.confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
                event.preventDefault();
            }
        });
    });
});

});