document.addEvent('domready', function() {

$$('.albumstream > li').each(function(li) {
    var src = li.getElement('img').get('src');
    
    if (src == '') return;
    li.setStyle('background-image', 'url(' + src + ')');
    li.setStyle('background-position', 'center');
});

$$('#toolbox .add a, .albumstream ul li.edit a').addEvent('click', function(e) {
    e.preventDefault();
    var link = this;

    $('dialog').load(this.get('href'));
    $('dialog').removeEvents('shown').addEvent('shown', function() {
        $$('#dialog main input')[0].focus();

        if (link.getParent().hasClass('add'))
            $$('#dialog main button').set('text', 'Create');
    });
});

});