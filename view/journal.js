$(document).ready(function() {

var $articles = $('main article')
var currentIndex = -1
var updateColorId = null
var colorThief = new ColorThief()

function getColorFromImg(img) {
    var palette = colorThief.getPalette(img, 3, 50)

    if (!palette) return

    var maxcolor = palette.reduce(function(max, x) {
        return x[0] + x[1] + x[2] > max[0] + max[1] + max[2] ? x : max
    })

    var mincolor = palette.reduce(function(min, x) {
        return x[0] + x[1] + x[2] < min[0] + min[1] + min[2] ? x : min
    })

    return ['rgb(' + maxcolor.join(',') + ')', 'rgb(' + mincolor.join(',') + ')']
}

function activateArticle(index) {
    if (index == null) {
        var index = $articles.map(function() {
            var distance = Math.abs($(this).offset().top + $(this).height() / 2
                - $(window).scrollTop() - $(window).height() / 2)
            return distance
        }).get().reduce(function(min, x, i, a) {
            return x < a[min] ? i : min
        }, 0)

        activateArticle(index)
        return
    }

    if (currentIndex == index) return

    clearTimeout(updateColorId)
    updateColorId = setTimeout(function() {
        var $img = $articles.eq(currentIndex).find('img')
        var colors = getColorFromImg($img.get(0))

        if (!colors) return

        $('nav').css('color', colors[0]).css('background-color', colors[1])
    }, 300)

    currentIndex = index
}

activateArticle()

$(window).bind('scroll', function() { activateArticle() })

})
