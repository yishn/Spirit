document.addEvent('domready', function() {

/**
 * Photostream selection
 */

$$('#toolbox li.edit, #toolbox li.delete').setStyle(
    'display', $$('.photostream .selected').length != 0 ? 'block' : 'none'
);

$$('.photostream li input').removeEvents('change').addEvent('change', function() {
    if (this.checked) this.getParent('li').addClass('selected');
    else this.getParent('li').removeClass('selected');
    $$('#toolbox li.edit, #toolbox li.delete').setStyle(
        'display', $$('.photostream .selected').length != 0 ? 'block' : 'none'
    );
});

$$('#toolbox .edit a').addEvent('click', function(e) {
    e.preventDefault();
    var ids = $$('.photostream .selected input[name="id[]"]').get('value').join();

    window.location = this.get('href') + '/' + ids;
});

});