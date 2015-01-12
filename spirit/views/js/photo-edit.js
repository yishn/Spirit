document.addEvent('domready', function() {

$$('.form .albums input').setStyle('display', 'none');
$$('.form .albums label + .albums').setStyle('display', 'block');

$$('form button[type="submit"]').addEvent('click', function() {
    var form = this.getParent('form');
    var editor = form.getElement('.ace').retrieve('ace');

    form.getElement('textarea[name="description"]').set('value', editor.getValue());
});

$$('.albumpicker').addEvent('albumlinkclicked', function(link) {
    var id = link.get('href').replace('#', '');
    var name = link.getElement('strong').get('text');
    $('dialog').close();

    if (this.getParent('ul.albums').getElement('.album' + id) != null) return false;

    this.getParent('ul.albums').grab(
        new Element('li', { class: 'album' + id }).set(
            'html', name + ' <a href="#" title="Remove from album">Remove from album</a>'
        ), 'top'
    );
});

});