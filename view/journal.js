$(document).ready(function() {

var $articles = $('main article')
var currentIndex = -1
var updateColorId = null
var colorThief = new ColorThief()

function sum(array) {
    return array.reduce(function(sum, x) { return sum + x }, 0)
}

function getColorFromImg(img) {
    if (img.spiritColors) return img.spiritColors

    var palette = colorThief.getPalette(img, 4, 500)

    if (!palette) return

    var maxcolor = palette.reduce(function(max, x) {
        return sum(x) > sum(max) ? x : max
    })

    var mincolor = palette.reduce(function(min, x) {
        return sum(x) < sum(min) ? x : min
    })

    img.spiritColors = [maxcolor, mincolor]
    return img.spiritColors
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

        $articles.eq(currentIndex).css('background-color', 'rgb(' + colors[1].join(',') + ')')

        $('nav').css('color', 'rgb(' + colors[0].join(',') + ')')
            .css('border-color', 'rgb(' + colors[1].join(',') + ')')
            .css('background-color', 'rgb(' + colors[1].map(function(x) { return Math.round(.8 * x) }).join(',') + ')')
    }, 500)

    currentIndex = index
}

activateArticle()

$(window).on('scroll', function() {
    activateArticle()
}).on('keypress', function(e) {
    var cond = $(window).scrollTop() - $articles.eq(currentIndex).offset().top
    var onArticle = Math.abs(cond) <= 1

    if (e.charCode == 107) {
        // k
        var index = !onArticle && cond > 0 ? currentIndex : Math.max(0, currentIndex - 1)

        $('html, body').animate({
            scrollTop: $articles.eq(index).offset().top
        }, 200)
    } else if (e.charCode == 106) {
        // j
        var index = !onArticle && cond < 0 ? currentIndex : Math.min($articles.length, currentIndex + 1)

        $('html, body').animate({
            scrollTop: $articles.eq(index).offset().top
        }, 200)
    }
})

})
