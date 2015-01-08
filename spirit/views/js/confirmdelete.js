document.addEvent('domready', function() {

$$('.delete').addEvent('click', function(e) {
    if (!window.confirm("Are you sure you want to delete this? This action cannot be undone.")) {
        e.preventDefault();
    }
});

});