define(function(require) {
    //requirements
    var Page = require('pages/page'),
        ProductModel = require('models/product'),
        Form_product = require('blocks/form/form_product/form_product');

    return Page.extend({
        pageName: 'page_product_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            products: 'POST'
        },
        initialize: function(productId, params){
            var page = this;

            if (productId && typeof productId !== 'string'){
                params = productId;
                productId = null;
            }

            params = params || {};

            page.productId = productId;

            page.productModel = new ProductModel({
                id: page.productId,
                subcategory: params.subcategory
            });

            $.when(productId ? page.productModel.fetch() : {}).then(function(){
                page.render();

                new Form_product({
                    model: page.productModel,
                    el: document.getElementById('form_product')
                });
            })
        }
    });
});