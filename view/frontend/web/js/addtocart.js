define([
    "jquery",
    "catalogAddToCart"
], function ($, catalogAddToCart) {
    return function (options) {
        const initAddToCart = (element) => {
            if (!element.data().hasOwnProperty("mageCatalogAddToCart")) {
                element.catalogAddToCart();
            }
        };
        $(options.selectors.addtocartform).each( function() {
            initAddToCart($(this));
        });
        $(document).on(options.events.contentready, function() {
            setTimeout(function() {
                initAddToCart($(options.selectors.addtocartform));
            }, 200);
        });
    };
});
