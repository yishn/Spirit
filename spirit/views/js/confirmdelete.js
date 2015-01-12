document.addEvent('domready', function() {

$$('.delete').addEvent('click', function(event) {
    if (!window.confirm("Are you sure you want to delete this? This action cannot be undone.")) {
        event.preventDefault();
    }
});

});