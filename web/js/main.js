$(document).ready(function() {
    $(window).off('popstate').on('popstate', function() {
        $( location ).attr("href", $(location).attr('href'));
    });

    $('.menu').on('click', '.js-category', function (e) {
        handleCategoryOrProductClick(e);
    });

    $('.js-product').click(function (e) {
        handleCategoryOrProductClick(e);
    });

    $('.js-page').click(function (e) {
        window.scrollTo(0, 80);
        handleCategoryOrProductClick(e);
    });

    $('.js-toggle-category').click(function (e) {
        e.preventDefault();

        $('.category-nav').toggle(200);
    });

    $(document).mouseup(function (e) {
        if ($(e.target).hasClass('js-toggle-category')) {
            return;
        }

        var container = $('.category-nav');
        if (container.has(e.target).length === 0 || $(e.target).hasClass('js-category')){
            container.hide(200);
        }
    });
});