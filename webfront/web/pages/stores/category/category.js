define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store'),
        exportCatalog = require('kit/exportCatalog'),
        router = require('router');

    return Page.extend({
        params: {
            categoryId: null,
            subCategoryId: null,
            groupId: null,
            section: 'subCategories'
        },
        listeners: {
            'change:params.section': function(section){
                var page = this;

                page.el.querySelector('.content').setAttribute('section', section);
            }
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
            }
        }
    });
});