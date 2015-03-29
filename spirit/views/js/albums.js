document.addEvent('domready', function() {

$$('.albumstream > li').each(function(li) {
    var src = li.getElement('img').get('src');
    
    if (src == '') return;
    if (li.getStyle('background-image') != 'none') return;

    li.setStyle('background-image', 'url(' + src + ')');
    li.setStyle('background-position', 'center');
});

$$('#toolbox .add a, .albumstream ul li.edit a').addEvent('click', function(event) {
    event.preventDefault();
    var link = this;

    $('dialog').load(this.get('href'));
    $('dialog').addEvent('shown', function() {
        $$('#dialog main input')[0].focus();
    }).addEvent('closed', function() {
        $('dialog').removeEvents();
    });;
});

});