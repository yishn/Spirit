document.addEvent('domready', function() {

/**
 * Photostream selection
 */

// Configure Dropzone and upload form

$$('#toolbox .upload a').removeEvents('click').addEvent('click', function(event) {
    event.preventDefault();

    $('dialog').load(this.get('href'));
    $('dialog').addEvent('closing', function(e) {
        if (!$$('#dialog .dropzone')[0].hasClass('loading')) return;
        if (!confirm('Do you really want to cancel the upload?')) e.cancel = true;
        else $$('#dialog form')[0].retrieve('dropzone').removeAllFiles(true);
    });
    $('dialog').addEvent('closed', function() {
        $('dialog').removeEvents();
    });
    $('dialog').addEvent('shown', function() {
        var form = $$('#dialog form')[0];
        var ids = '';
        var count = 0;

        form.grab(new Element('div', {
                class: 'dropzone',
            }).grab(form.getElement('.fallback')), 'top')
            .grab(new Element('div', { class: 'uploadqueue' }), 'bottom');

        var dropzone = new Dropzone(form.getElement('.dropzone'), {
            url: form.get('action') + '/id',
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            autoProcessQueue: false,
            paramName: 'file[]',
            parallelUploads: 1,
            previewsContainer: form.getElement('.uploadqueue'),
            thumbnailWidth: 99,
            thumbnailHeight: 99
        });
        form.store('dropzone', dropzone);

        dropzone.on('complete', function(file) {
            this.removeFile(file);
        });
        dropzone.on('uploadprogress', function(file, progress, sent) {
            var totalprogress = 100.0 / count * (count - this.getAcceptedFiles().length);
            totalprogress += progress / count;

            $$('#dialog .dropzone .progress').setStyle('width', totalprogress + '%');
        });
        dropzone.on('success', function(file, response) {
            ids += response.split('\n')[0] + ',';
            dropzone.processQueue();
        });
        dropzone.on('queuecomplete', function() {
            window.location.href = form.get('action').replace('upload', 'edit') + '/' + ids.substr(0, ids.length - 1);
        });

        $$('#dialog button[type="submit"]').addEvent('click', function(e) {
            e.preventDefault();
            if ($$('#dialog .dz-preview').length == 0) return;

            this.set('disabled', 'disabled');
            $$('#dialog .dz-message').setStyle('visibility', 'hidden');
            $$('#dialog .dropzone')[0].grab(new Element('div', { class: 'progress' }))
                .addClass('loading')
                .removeEventListener('click', dropzone.listeners[1].events.click);

            count = dropzone.getAcceptedFiles().length;
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

$$('#toolbox .edit a, #toolbox .delete a').removeEvents('click').addEvent('click', function(e) {
    e.preventDefault();
    var ids = $$('.photostream .selected input[name="id[]"]').get('value').join();
    
    if (this.getParent().hasClass('delete') &&
        !window.confirm("Are you sure you want to delete this? This action cannot be undone."))
        return;

    window.location = this.get('href') + '/' + ids;
});

});