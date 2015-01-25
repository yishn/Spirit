document.addEvent('domready', function() {

/**
 * Photostream selection
 */

// Configure Dropzone and upload form

$$('#toolbox .upload a').addEvent('click', function(event) {
    event.preventDefault();

    $('dialog').load(this.get('href'));
    $('dialog').removeEvents('closing').addEvent('closing', function(e) {
        if (!$$('#dialog .dropzone')[0].hasClass('loading')) return;
        if (!confirm('Do you really want to cancel the upload?')) e.cancel = true;
        else $$('#dialog form')[0].retrieve('dropzone').removeAllFiles(true);
    });
    $('dialog').removeEvents('shown').addEvent('shown', function() {
        var form = $$('#dialog form')[0];

        form.grab(new Element('div', {
                class: 'dropzone',
            }).grab(form.getElement('.fallback')), 'top')
            .grab(new Element('div', { class: 'uploadqueue' }), 'bottom');

        var dropzone = new Dropzone(form.getElement('.dropzone'), {
            url: form.get('action') + '/id',
            uploadMultiple: true,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            autoProcessQueue: false,
            previewsContainer: form.getElement('.uploadqueue'),
            thumbnailWidth: 99,
            thumbnailHeight: 99
        });
        form.store('dropzone', dropzone);

        dropzone.on('totaluploadprogress', function(progress, total, sent) {
            $$('#dialog .dropzone .progress').setStyle('width', progress + '%');
        });
        dropzone.on('successmultiple', function(file, response) {
            var ids = response.split('\n')[0];
            window.location.href = form.get('action').replace('upload', 'edit') + '/' + ids;
        });

        $$('#dialog button[type="submit"]').addEvent('click', function(e) {
            e.preventDefault();
            if ($$('#dialog .dz-preview').length == 0) return;

            this.set('disabled', 'disabled');
            $$('#dialog .dz-message').setStyle('visibility', 'hidden');
            $$('#dialog .dropzone')[0].grab(new Element('div', { class: 'progress' }))
                .addClass('loading')
                .removeEventListener('click', dropzone.listeners[1].events.click);

            dropzone.processQueue();
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