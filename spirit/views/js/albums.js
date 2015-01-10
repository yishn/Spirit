document.addEvent('domready', function() {

$$('.albumstream > li').each(function(li) {
    var src = li.getElement('img').get('src');
    
    if (src == '') return;
    li.setStyle('background-image', 'url(' + src + ')');
    li.setStyle('background-position', 'center');
});

$$('#toolbox .add a').addEvent('click', function(e) {
    e.preventDefault();
    $('dialog').load(this.get('href'));
    $('dialog').addEvent('shown', function() {
        $$('#dialog main input')[0].focus();
    });
});

});