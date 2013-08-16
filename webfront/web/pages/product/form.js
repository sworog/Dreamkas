define(function(require) {
    //requirements
    var Page = require('kit/page'),
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

            $.when(productId ? page.productModel.fetch() : {}, page.subCategoryModel.id ? page.subCategoryModel.fetch({parse: false}) : {}).then(function(){

                if (!productId){
                    page.productModel = new ProductModel({
                        subCategory: page.subCategoryModel.toJSON(),
                        retailMarkupMin: page.subCategoryModel.get('retailMarkupMin'),
                        retailMarkupMax: page.subCategoryModel.get('retailMarkupMax')
                    }, {
                        parse: true
                    });
                }

                page.render();

                new Form_product({
                    subCategoryModel: page.subCategoryModel,
                    model: page.productModel,
                    el: document.getElementById('form_product')
                });
            })
        }
    });
});