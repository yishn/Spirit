document.addEvent('domready', function() {

$$('.photostream li img').setStyle('cursor', 'pointer').addEvent('click', function() {
    this.getParent().toggleClass('selected');
});

});