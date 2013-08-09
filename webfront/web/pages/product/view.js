define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product'),
        StoreProduct = require('models/storeProduct');

    return Page.extend({
        pageName: 'page_product_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            products: 'GET::{product}'
        },
        initialize: function(productId) {
            var page = this;

            page.productId = productId;

            page.productModel = new ProductModel({
                id: page.productId
            });

            if (LH.isAllow('stores/{store}/products/{product}', 'GET')) {
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