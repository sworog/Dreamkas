define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        StoreProduct = require('models/storeProduct'),
        Form_storeProduct = require('blocks/form/form_storeProduct/form_storeProduct');

    return Page.extend({
        __name__: 'page_storeProduct_form',
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            'stores/{store}/products/{product}': 'PUT'
        },
        initialize: function(params) {
            var page = this;

            params = params || {};

            page.storeId = params.storeId;
            page.productId = params.productId;

            page.storeProductModel = new StoreProduct({
                id: page.productId
            });

            $.when(page.storeProductModel.fetch()).then(function() {
                page.render();

                new Form_storeProduct({
                    model: page.storeProductModel,
                    el: document.getElementById('form_storeProduct')
                })
            })
        }
    });
});