/*
 * Celebros (C) 2022. All Rights Reserved.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality.
 * If you wish to customize it, please contact Celebros.
 */

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
