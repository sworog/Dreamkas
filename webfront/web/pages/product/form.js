define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        ProductModel = require('models/product'),
        SubCategoryModel = require('models/catalogSubCategory'),
        Form_product = require('blocks/form/form_product/form_product');

    return Page.extend({
        __name__: 'page_product_form',
        params: {
            productId: null,
            subCategory: null
        },
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            products: 'POST'
        },
        initialize: function(){
            var page = this;

            page.productModel = new ProductModel({
                id: page.params.productId,
                subCategory: page.params.subCategory
            });

            page.subCategoryModel = new SubCategoryModel({
                id: page.params.subCategory
            });

            $.when(page.params.productId ? page.productModel.fetch() : {}, page.subCategoryModel.id ? page.subCategoryModel.fetch({parse: false}) : {}).then(function(){

                if (!page.params.productId){
                    page.productModel = new ProductModel({
                        subCategory: page.subCategoryModel.toJSON(),
                        retailMarkupMin: page.subCategoryModel.get('retailMarkupMin'),
                        retailMarkupMax: page.subCategoryModel.get('retailMarkupMax')
                    }, {
                        parse: true
                    });
                } else {
                    page.subCategoryModel = new SubCategoryModel(page.productModel.get('subCategory'));
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