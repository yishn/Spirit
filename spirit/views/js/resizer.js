document.addEvent('domready', function() {

/**
 * Automatically resizes stream items
 */

var items = $$('main .stream > li');
items.setStyles({
    'width': '',
    'height': ''
});

var item = $$('main .stream > li')[0];
var width = item.getSize().x;
var height = item.getSize().y;
var padding = width - item.getStyle('width').toInt() + item.getStyle('margin-right').toInt() + 1;

var resize = function() {
    var globalwidth = item.getParent().getSize().x;
    var rowcount = Math.ceil(globalwidth / width);
    var newwidth = globalwidth / rowcount;
    var newheight = newwidth / width * height;

    items.setStyles({
        'height': Math.floor(newheight - padding),
        'width': Math.floor(newwidth - padding)
    });
}

resize();
window.addEvent('resize', resize);

});