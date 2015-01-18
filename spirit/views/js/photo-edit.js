document.addEvent('domready', function() {

$$('.form .albums input').setStyle('display', 'none');
$$('.form .albums label + .albums').setStyle('display', 'block');

$$('form button[type="submit"]').addEvent('click', function() {
    var form = this.getParent('form');

    form.getElements('ul.form').each(function(ulform) {
        var editor = ulform.getElement('.ace').retrieve('ace');

        ulform.getElement('textarea[name="description[]"]').set('value', editor.getValue());
        ulform.getElement('input[name="albums[]"]').set('value', '');

        ulform.getElements('ul.albums > li:not(:last-child) > a').each(function(el) {
            var albumId = el.get('href').replace('#', '');
            ulform.getElement('input[name="albums[]"]').value += albumId + ','
        });
    });
});

var removeAlbum = function(e) {
    e.preventDefault();
    this.getParent('li').destroy();
};

$$('ul.albums > li:not(:last-child) > a').addEvent('click', removeAlbum);

$$('.albumpicker').addEvent('albumlinkclick', function(e, link) {
    e.preventDefault();
    
    var path = link.get('href').split('/');
    var id = path[path.length - 1];
    var name = link.getElement('strong').get('text');

    $('dialog').close();
    if (this.getParent('ul.albums').getElement('[href="#' + id + '"]') != null) return false;

    this.getParent('ul.albums').grab(
        new Element('li').set('text', name + ' ').adopt(new Element('a', {
            'href': '#' + id,
            'title': 'Remove from album',
            'text': 'Remove from album',
            'events': {
                'click': removeAlbum
            }
        })), 'top'
    );
});

});