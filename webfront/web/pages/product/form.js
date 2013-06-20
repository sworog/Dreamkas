define(function(require) {
    //requirements
    var Page = require('kit/page'),
        ProductModel = require('models/product'),
        Form_product = require('blocks/form/form_product/form_product');

    return Page.extend({
        initialize: function(productId) {
            var page = this;

            page.productId = productId;
        },
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        initModels: {
            product: function() {
                var page = this;

                return new ProductModel({
                    id: page.productId
                })
            }
        },
        initBlocks: function() {
            var page = this;

            new Form_product({
                model: page.models.product,
                el: document.getElementById('form_product')
            });
        }
    });
});