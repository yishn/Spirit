$(document).ready(function() {

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
    var $articles = $('main article:not(.inactive)')

    if (index == null) {
        var index = $articles.map(function() {
            var distance = Math.abs($(window).scrollTop() - $(this).offset().top)
            return distance
        }).get().reduce(function(min, x, i, a) {
            return x < a[min] ? i : min
        }, 0)

        activateArticle(index)
        return
    }

    clearTimeout(updateColorId)
    updateColorId = setTimeout(function() {
        var $articles = $('main article:not(.inactive)')
        var $img = $articles.eq(currentIndex).find('img')
        var colors = getColorFromImg($img.get(0))

        if (!colors) return

        $articles.eq(currentIndex).css('background-color', 'rgb(' + colors[1].join(',') + ')')
            .parents('.imageset').next('.description').css('background-color', 'rgb(' + colors[1].join(',') + ')')

        $('nav').css('color', 'rgb(' + colors[0].join(',') + ')')
            .css('background-color', 'rgb(' + colors[1].map(function(x) { return Math.round(.8 * x) }).join(',') + ')')
    }, 500)

    currentIndex = index
}

activateArticle()

$('main article .image img').on('load', function() {
    $(this).parent('.image').addClass('loaded')

    if (currentIndex == $('main article:not(.inactive) .image img').get().indexOf(this))
        activateArticle(currentIndex)
})

$(window).on('scroll', function() {
    activateArticle()
}).on('keypress', function(e) {
    var $articles = $('main article:not(.inactive)')
    var cond = $(window).scrollTop() - $articles.eq(currentIndex).offset().top
    var snapped = Math.abs(cond) <= 1

    if (e.charCode == 107) {
        // k
        var index = !snapped && cond > 0 ? currentIndex : Math.max(0, currentIndex - 1)

        $('html, body').animate({
            scrollTop: $articles.eq(index).offset().top
        }, 200)
    } else if (e.charCode == 106) {
        // j
        var index = !snapped && cond < 0 ? currentIndex : Math.min($articles.length, currentIndex + 1)

        $('html, body').animate({
            scrollTop: $articles.eq(index).offset().top
        }, 200)
    }
})

// Handle image sets

function showNextSlide($imageset) {
    var $articles = $imageset.find('article')
    var currentArticle = $imageset.find('article:not(.inactive)').get(0)
    var globalIndex = $('main article:not(.inactive)').get().indexOf(currentArticle)
    var index = $articles.get().indexOf(currentArticle)
    var nextIndex = (index + 1) % $articles.length

    $articles.eq(index).addClass('inactive')
    $articles.eq(nextIndex).removeClass('inactive')
    $imageset.find('.progress').css('width', (nextIndex + 1) * 100 / $articles.length + '%')

    if (globalIndex == currentIndex) activateArticle(currentIndex)
}

$('.imageset').each(function() {
    var $imageset = $(this)

    $imageset.after($('<section/>', {
        class: 'description'
    }).append($imageset.find('aside')))
    .find('.image img').on('load', function() {
        if ($imageset.find('.image img').length != $imageset.find('.image.loaded img').length)
            return

        $imageset.addClass('render')
            .append($('<div/>', { class: 'progress' }).css('width', 100 / $imageset.find('article').length + '%'))
            .find('article:not(:first-child)').addClass('inactive')

        setInterval(function() { showNextSlide($imageset) }, 5000)
    })
})

})
