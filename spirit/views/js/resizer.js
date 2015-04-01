document.addEvent('domready', function() {

/**
 * Automatically resizes photostream items
 */

var items = $$('main .photostream > li');
if (items.length == 0) return;
var item = items[0];

items.setStyles({
    'width': item.getStyle('width'),
    'height': item.getStyle('height')
});

item.getElement('img').addEvent('load', function() {
    var width = item.getSize().x;
    var height = item.getSize().y;
    var padding = width - item.getStyle('width').toInt() + item.getStyle('margin-right').toInt();

    var resize = function() {
        var globalwidth = item.getParent().getSize().x;
        var rowcount = Math.ceil(globalwidth / width);
        var newwidth = globalwidth / rowcount;
        var newheight = newwidth / width * height;

        items.setStyles({
            'height': Math.floor(newheight - padding - 1),
            'width': Math.floor(newwidth - padding - 1)
        });
    }

    resize();
    window.removeEvent('resize').addEvent('resize', resize);  
});

});