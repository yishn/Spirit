document.addEvent('domready', function() {

$$('.form .albums input').setStyle('display', 'none');
$$('.form .albums label + .albums').setStyle('display', 'block');

$$('form button[type="submit"]').addEvent('click', function() {
    var form = this.getParent('form');
    var editor = form.getElement('.ace').retrieve('ace');

    form.getElement('textarea[name="description"]').set('value', editor.getValue());

    form.getElement('input[name="albums"]').set('value', '');
    form.getElements('ul.albums > li:not(:last-child) > a').each(function(el) {
        var albumId = el.get('href').replace('#', '');
        form.getElement('input[name="albums"]').value += albumId + ','
    });
});

var removeAlbum = function(e) {
    e.preventDefault();
    this.getParent('li').destroy();
};

$$('ul.albums > li:not(:last-child) > a').addEvent('click', removeAlbum);

$$('.albumpicker').addEvent('albumlinkclick', function(link) {
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