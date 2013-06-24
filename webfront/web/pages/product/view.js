define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product');

    return Page.extend({
        pageName: 'page_product_view',
        initialize: function(productId) {
            var page = this;

            page.productId = productId;
        },
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initModels: {
            product: function() {
                var page = this;

                return new ProductModel({
                    id: page.productId
                });
            }
        },
        initBlocks: function() {
            var page = this;

            new Product({
                model: page.models.product,
                el: document.getElementById('product')
            });
        }
    });
});