define(function(require) {
    //requirements
    var Page = require('kit/page'),
        StoreProduct = require('models/storeProduct'),
        Form_storeProduct = require('blocks/form/form_storeProduct/form_storeProduct');

    return Page.extend({
        pageName: 'page_storeProduct_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            'stores/{store}/products/{product}': 'PUT'
        },
        initialize: function(storeId, storeProductId, params) {
            var page = this;

            params = params || {};

            page.storeId = storeId;
            page.storeProductId = storeProductId;

            page.storeProductModel = new StoreProduct({
                id: page.storeProductId
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