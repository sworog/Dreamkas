define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product');

    return Page.extend({
        initialize: function(productId){
            var page = this;

            page.productId = productId;
        },
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initModels: {
            product: function(){
                var page = this;

                return new ProductModel({
                    id: page.productId
                });
            }
        },
        initBlocks: {
            product: function(){
                var page = this;

                return new Product({
                    model: page.models.product,
                    el: document.getElementById('product')
                });
            }
        }
    });
});