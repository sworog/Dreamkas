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
            productInvoices: function(){
                var page = this,
                    ProductInvoicesCollection = require('collections/productInvoices');

                return new ProductInvoicesCollection([], {
                    storeId: page.params.storeId,
                    productId: page.params.productId
                });
            }
        }
    });
});