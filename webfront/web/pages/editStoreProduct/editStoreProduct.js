define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        params: {
            productId: null,
            storeId: null
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_storeProduct.ejs')
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
        },
        blocks: {
            form_storeProduct: function(){
                var page = this,
                    Form_storeProduct = require('blocks/form/form_storeProduct/form_storeProduct');

                return new Form_storeProduct({
                    model: page.models.product
                });
            }
        }
    });
});