define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        StoreProduct = require('models/storeProduct'),
        Form_storeProduct = require('blocks/form/form_storeProduct/form_storeProduct');

    return Page.extend({
        __name__: 'page_storeProduct_form',
        params: {
            productId: null,
            storeId: null
        },
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            'stores/{store}/products/{product}': 'PUT'
        },
        initialize: function() {
            var page = this;

            page.storeProductModel = new StoreProduct({
                id: page.params.productId
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