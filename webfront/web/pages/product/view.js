define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product'),
        StoreProduct = require('models/storeProduct'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/403/403');

    return Page.extend({
        pageName: 'page_product_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function(productId) {
            var page = this;

            page.productId = productId;

            if (!LH.isAllow('stores/{store}/products/{product}')){
                new Page403();
                return;
            }

            if (!LH.isAllow('products', 'GET::{product}') && !currentUserModel.stores.length){
                new Page403();
                return;
            }

            page.productModel = new ProductModel({
                id: page.productId
            });

            if (LH.isAllow('stores/{store}/products/{product}', 'GET') && currentUserModel.stores.length) {
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