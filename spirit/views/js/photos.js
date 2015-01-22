document.addEvent('domready', function() {

/**
 * Photostream selection
 */

// Configure Dropzone and upload form

$$('#toolbox .upload a').addEvent('click', function(event) {
    event.preventDefault();
    $('dialog').load(this.get('href'));
    $('dialog').removeEvents('shown').addEvent('shown', function() {
        var form = $$('#dialog form')[0];
        form.grab(new Element('div', {
                class: 'dropzone',
            }).grab(form.getElement('.fallback')), 'top')
            .grab(new Element('div', { class: 'uploadqueue' }), 'bottom');

        form.store('dropzone', new Dropzone(form.getElement('.dropzone'), {
            url: form.get('action'),
            uploadMultiple: true,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            autoProcessQueue: false,
            previewsContainer: form.getElement('.uploadqueue'),
            thumbnailWidth: 100,
            thumbnailHeight: 100
        }));

        $$('#dialog button[type="submit"]').addEvent('click', function(e) {
            e.preventDefault();
            form.retrieve('dropzone').processQueue();
        });
    });
});

// Photo selection

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

// Bulk edit/delete

$$('#toolbox .edit a, #toolbox .delete a').addEvent('click', function(e) {
    e.preventDefault();
    var ids = $$('.photostream .selected input[name="id[]"]').get('value').join();
    
    if (this.getParent().hasClass('delete') &&
        !window.confirm("Are you sure you want to delete this? This action cannot be undone."))
        return;

    window.location = this.get('href') + '/' + ids;
});

});