define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_product.ejs')
        },
        models: {
            product: function(){
                var page = this,
                    ProductModel = require('models/product');

                return new ProductModel({
                    id: page.params.productId
                });
            }
        }
    });
});