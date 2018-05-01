function handleCategoryOrProductClick(e) {
    e.preventDefault();

    window.scrollTo(0, 80);

    var $productsWrapper = $('.products-wrapper');
    var loader = $('.js-loader-wrapper').html();
    var url = $(e.currentTarget).data('url');

    $productsWrapper.html(loader);
    window.history.pushState("", "", url);

    if ($(e.currentTarget).hasClass('js-category')) {
        $('.js-category').removeClass('active-category');
        $(e.currentTarget).addClass('active-category');
    }

    $.ajax({
        url: url,
        type: 'POST',
        success: function (data) {
            $productsWrapper.html(data.products);
        }
    }).then(function () {
        $('.js-product').click(function (e) {
            handleCategoryOrProductClick(e);
        });

        $('.js-page').click(function (e) {
            handleCategoryOrProductClick(e);
        });
    });
}