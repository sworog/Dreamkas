define(function(require) {
    //requirements
    var Page = require('pages/storeProduct');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        collections: {
            productReturns: function(){
                var page = this,
                    ProductReturnsCollection = require('collections/productReturns');

                return new ProductReturnsCollection([], {
                    storeId: page.params.storeId,
                    productId: page.params.productId
                });
            }
        }
    });
});