define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        params: {
            subCategoryId: null
        },
        partials: {
            content: require('ejs!./content.ejs')
        },
        models: {
            subCategory: function(){
                var page = this,
                    SubCategoryModel = require('models/subCategory');

                return new SubCategoryModel({
                    id: page.params.subCategoryId
                });
            }
        },
        blocks: {
            form_product: function(){
                var page = this,
                    Form_product = require('blocks/form/form_product/form_product');

                return new Form_product({
                    models: {
                        subCategory: page.models.subCategory
                    }
                });
            }
        }
    });
});