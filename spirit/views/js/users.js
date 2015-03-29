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
        $$('#dialog button[type="submit"]').addEvent('click', function(event) {
            if ($$('#dialog input[type="password"]')[0].value == '' && $$('#dialog input[type="password"]')[1].value == '')
                return;
            if ($$('#dialog input[type="password"]')[0].value == $$('#dialog input[type="password"]')[1].value) 
                return;

            alert('The passwords do not coincide!');
            $$('#dialog input[type="password"]').set('value', '')[0].focus();
            event.preventDefault();
        });
    });
});

});