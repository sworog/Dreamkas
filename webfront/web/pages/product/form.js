define(function(require) {
    //requirements
    var Page = require('pages/page'),
        ProductModel = require('models/product'),
        SubCategoryModel = require('models/catalogSubCategory'),
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
                subCategory: params.subCategory
            });

            page.subCategoryModel = new SubCategoryModel({
                id: params.subCategory
            });

            $.when(productId ? page.productModel.fetch() : {}, page.subCategoryModel.id ? page.subCategoryModel.fetch() : {}).then(function(){

                if (productId){
                    page.subCategoryModel = new SubCategoryModel(page.productModel.get('subCategory'), {
                        parse: true
                    });
                }

                page.render();

                new Form_product({
                    model: page.productModel,
                    subCategoryModel: page.subCategoryModel,
                    el: document.getElementById('form_product')
                });
            })
        }
    });
});