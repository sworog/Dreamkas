define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product'),
        StoreProduct = require('models/storeProduct'),
        currentUserModel = require('models/currentUser');

    return Page.extend({
        __name__: 'page_product_view',
        productId: null,
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            products: 'GET::{product}'
        },
        initialize: function() {
            var page = this;

            page.productModel = new ProductModel({
                id: page.productId
            });

            if (LH.isAllow('stores/{store}/products/{product}', 'GET') && currentUserModel.stores) {
                page.storeProductModel = new StoreProduct({
                    id: page.productId
                });
            }

            $.when(page.productModel.fetch(), page.storeProductModel ? page.storeProductModel.fetch() : {}).then(function(){
                page.render();

                new Product({
                    model: page.productModel,
                    storeProductModel: page.storeProductModel,
                    el: document.getElementById('product')
                });
            });
        }
    });
});