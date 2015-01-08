document.addEvent('domready', function() {

$$('.form .albums input').setStyle('display', 'none');
$$('.form .albums input + .albums').setStyle('display', 'block');

});