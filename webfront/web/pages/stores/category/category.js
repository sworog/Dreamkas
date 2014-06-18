define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store'),
        exportCatalog = require('kit/exportCatalog'),
        SubCategoryModel = require('models/subCategory'),
        router = require('router');

    return Page.extend({
        params: {
            categoryId: null,
            groupId: null,
            subCategoryId: '0',
            subSection: 'products'
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_storeCategory.ejs')
        },
        models: {
            group: function(){
                var GroupModel = require('models/group'),
                    page = this;

                return new GroupModel({
                    id: page.params.groupId
                });
            },
            category: function(){
                var CategoryModel = require('models/category'),
                    page = this;

                return new CategoryModel({
                    groupId: page.params.groupId,
                    id: page.params.categoryId
                });
            },
            subCategory: function(){
                var page = this;

                return new SubCategoryModel({
                    id: page.params.subCategoryId !== '0' && page.params.subCategoryId,
                    groupId: page.params.groupId,
                    categoryId: page.params.categoryId
                });
            }
        }
    });
});