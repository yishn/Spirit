document.addEvent('domready', function() {

/**
 * Monthpicker
 * Requires dialog.js
 */

$$('.monthpicker').removeEvents('click').addEvent('click', function(event) {
    event.preventDefault();

    $('dialog').load(this.get('href'));

    $('dialog').addEvent('shown', function() {
        $$('#dialog #monthpicker ul li a').addEvent('click', function(e) {
            e.preventDefault();
            $('dialog').load(this.get('href'));
        });

        var changed = function() {
            var year = '' + Math.max(1, Math.min($$('#dialog #monthpicker ul li input')[0].value.toInt(), 9999));
            var count = 4 - year.length;
            var href = $$('#dialog #monthpicker ul li a')[0].get('href');

            for (i = 0; i < count; i++) year = '0' + year;
            href = href.substr(0, href.length - 4) + year;

            $('dialog').load(href);
        };

        $$('#dialog #monthpicker ul li input').addEvent('change', changed)
            .addEvent('keyup', function(e) {
                if (e.code != 13) return;
                changed();
            });
    });
});

});