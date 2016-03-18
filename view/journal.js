$(document).ready(function() {

var $articles = $('main article')
var currentIndex = -1
var updateColorId = null
var colorThief = new ColorThief()

function activateArticle(index) {
    if (currentIndex == index) return

    $articles.filter(function() {
        return !$(this).hasClass('inactive')
    }).addClass('inactive')

    $articles.eq(index).removeClass('inactive')

    clearTimeout(updateColorId)
    updateColorId = setTimeout(function() {
        var $img = $articles.eq(currentIndex).find('img')
        var palette = colorThief.getPalette($img.get(0), 3)

        if (!palette) return

        var color = palette.reduce(function(min, x) {
            return x[0] + x[1] + x[2] < min[0] + min[1] + min[2] ? x : min
        })

        $img.css('background-color', 'rgb(' + color.join(',') + ')')
        $('nav').css('background-color', $img.css('background-color'))
    }, 300)

    currentIndex = index
}

activateArticle(0)

$(window).bind('scroll', function() {
    var index = $articles.map(function() {
        var distance = Math.abs($(this).offset().top + $(this).height() / 2
            - $(window).scrollTop() - $(window).height() / 2)
        return distance
    }).get().reduce(function(min, x, i, a) {
        return x < a[min] ? i : min
    }, 0)

    activateArticle(index)
})

})
