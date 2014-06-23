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
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_storeCategory.ejs')
        },
        events: {
            'click .catalog__subCategoryLink[data-subcategory_id]': function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (e.target.classList.contains('preloader_stripes')) {
                    return;
                }

                var page = this,
                    subCategoryId = e.target.dataset.subcategory_id;

                page.models.subCategory
                    .clear({
                        silent: true
                    })
                    .set('id', subCategoryId);

                page.collections.products.subCategoryId = subCategoryId;

                e.target.classList.add('preloader_stripes');

                Promise.all([page.models.subCategory.fetch(), page.collections.products.fetch()]).then(function() {
                    page.set('params', {
                        subCategoryId: subCategoryId
                    });
                });
            }
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
        },
        collections: {
            products: function() {
                var page = this,
                    ProductsCollection = require('collections/catalogProducts');

                return new ProductsCollection([], {
                    storeId: page.params.storeId,
                    subCategoryId: page.params.subCategoryId !== '0' && page.params.subCategoryId
                });
            }
        }
    });
});