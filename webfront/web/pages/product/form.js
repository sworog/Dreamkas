define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        ProductModel = require('models/product'),
        SubCategoryModel = require('models/catalogSubCategory'),
        Form_product = require('blocks/form/form_product/form_product');

    return Page.extend({
        __name__: 'page_product_form',
        productId: null,
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            products: 'POST'
        },
        initialize: function(){
            var page = this;

            page.productModel = new ProductModel({
                id: page.productId,
                subcategory: page.subcategory
            });

            page.subcategoryModel = new SubCategoryModel({
                id: page.subcategory
            });

            $.when(page.productId ? page.productModel.fetch() : {}, page.subcategoryModel.id ? page.subcategoryModel.fetch({parse: false}) : {}).then(function(){

                if (!page.productId){
                    page.productModel = new ProductModel({
                        subcategory: page.subcategoryModel.toJSON(),
                        retailMarkupMin: page.subcategoryModel.get('retailMarkupMin'),
                        retailMarkupMax: page.subcategoryModel.get('retailMarkupMax')
                    }, {
                        parse: true
                    });
                } else {
                    page.subcategoryModel = new SubCategoryModel(page.productModel.get('subcategory'));
                }

                page.render();

                new Form_product({
                    subcategoryModel: page.subcategoryModel,
                    model: page.productModel,
                    el: document.getElementById('form_product')
                });
            })
        }
    });
});