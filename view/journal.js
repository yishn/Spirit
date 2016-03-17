$(document).ready(function() {

var $articles = $('main article')

function activateArticle(index) {
    $articles.filter(function() {
        return !$(this).hasClass('inactive')
    }).addClass('inactive')

    $articles.eq(index).removeClass('inactive')
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
