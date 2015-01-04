document.addEvent('domready', function() {

$$('#toolbox li.edit, #toolbox li.delete').setStyle('visibility', 'hidden');

$$('.photostream li img').setStyle('cursor', 'pointer').addEvent('click', function() {
    this.getParent().toggleClass('selected');
    $$('#toolbox li.edit, #toolbox li.delete').setStyle(
        'visibility', $$('.photostream .selected').length == 0 ? 'hidden' : 'visible'
    );
});

});