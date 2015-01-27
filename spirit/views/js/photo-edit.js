document.addEvent('domready', function() {

$$('.form .albums input, input[name="globalalbums"]').setStyle('display', 'none');
$$('.form .albums label + .albums').setStyle('display', 'block');

// Submit button

$$('form button[type="submit"]').addEvent('click', function() {
    var form = this.getParent('form');

    form.getElements('.globalalbums, ul.form').each(function(ulform) {
        if (!ulform.hasClass('globalalbums')) {
            var editor = ulform.getElement('.ace').retrieve('ace');
            ulform.getElement('textarea[name="description[]"]').set('value', editor.getValue());
        }

        ulform.getElement('input[name="albums[]"], input[name="globalalbums"]').set('value', '');

        ulform.getElements('ul.albums > li:not(:last-child) > a').each(function(el) {
            var albumId = el.get('href').replace('#', '');
            ulform.getElement('input[name="albums[]"], input[name="globalalbums"]').value += albumId + ','
        });
    });
});

// Disable/Enable albumlists

var disenableAlbumlists = function() {
    var count = $$('.globalalbums .albums > li:not(:last-child) > a').length;

    if (count != 0) $$('ul.form ul.albums').addClass('disabled');
    else $$('ul.form ul.albums').removeClass('disabled');
};

// Albumpicker

var removeAlbum = function(e) {
    e.preventDefault();
    this.getParent('li').destroy();

    disenableAlbumlists();
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

    disenableAlbumlists();
});

});