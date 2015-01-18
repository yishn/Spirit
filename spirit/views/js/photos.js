document.addEvent('domready', function() {

/**
 * Photostream selection
 */

$$('#toolbox .upload a').addEvent('click', function(event) {
    event.preventDefault();
    $('dialog').load(this.get('href'));
});

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

$$('#toolbox .edit a, #toolbox .delete a').addEvent('click', function(e) {
    e.preventDefault();
    var ids = $$('.photostream .selected input[name="id[]"]').get('value').join();
    
    if (this.getParent().hasClass('delete') &&
        !window.confirm("Are you sure you want to delete this? This action cannot be undone."))
        return;

    window.location = this.get('href') + '/' + ids;
});

});