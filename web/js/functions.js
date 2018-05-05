function handleCategoryOrProductClick(e) {
    e.preventDefault();

    var url = $(e.currentTarget).find('.js-product-link').attr('href');
    window.location = url;
}