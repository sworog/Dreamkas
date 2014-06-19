define(function(require) {
    //requirements
    var Page = require('pages/storeProduct');

    return Page.extend({
        params: {
            productId: null
        },
        partials: {
            content: require('tpl!./content.ejs')
        },
        collections: {
            productWriteOffs: function(){
                var page = this,
                    ProductWriteOffsCollection = require('collections/productWriteOffs');

                return new ProductWriteOffsCollection([], {
                    storeId: page.params.storeId,
                    productId: page.params.productId
                });
            }
        }
    });
});