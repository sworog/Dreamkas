define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_storeProduct.ejs')
        },
        models: {
            product: function(){
                var page = this,
                    ProductModel = require('models/product');

                return new ProductModel({
                    id: page.params.productId,
                    storeId: page.params.storeId
                });
            }
        }
    });
});