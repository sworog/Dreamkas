define(function(require) {
    //requirements
    var Page = require('kit/page'),
        CatalogCategoryBlock = require('blocks/catalogCategory/catalogCategory'),
        CatalogProductsCollection = require('collections/catalogProducts'),
        СatalogGroupModel = require('models/catalogGroup');

    return Page.extend({
        pageName: 'page_catalog_category',
        catalogGroupId: null,
        catalogCategoryId: null,
        catalogSubCategoryId: null,
        templates: {
            '#content': require('tpl!./templates/category.html')
        },
        permissions: {
            categories: 'GET::{category}'
        },
        initialize: function(){
            var page = this;

            if (page.referer && page.referer.pageName.indexOf('page_catalog') >= 0){
                page.editMode = page.referer.editMode;
            }

            if (page.referer && page.referer.pageName === 'page_product_form'){
                page.editMode = true;
            }

            if (!LH.isAllow('groups', 'POST')) {
                page.editMode = false;
            }

            page.catalogGroupModel = new СatalogGroupModel({
                id: page.catalogGroupId,
                storeId: page.storeId
            });

            page.catalogProductsCollection = new CatalogProductsCollection([], {
                subCategory: page.catalogSubCategoryId,
                storeId: page.storeId
            });

            $.when(page.catalogGroupModel.fetch(), page.catalogSubCategoryId ? page.catalogProductsCollection.fetch() : {}).then(function(){

                page.catalogCategoryModel = page.catalogGroupModel.categories.get(page.catalogCategoryId);
                page.catalogSubCategoriesCollection = page.catalogCategoryModel.subCategories;

                page.render();

                new CatalogCategoryBlock({
                    el: document.getElementById('catalogCategory'),
                    catalogCategoryModel: page.catalogCategoryModel,
                    catalogSubCategoriesCollection: page.catalogSubCategoriesCollection,
                    catalogSubCategoryId: page.catalogSubCategoryId,
                    catalogProductsCollection: page.catalogProductsCollection,
                    editMode: page.editMode
                })
            });
        }
    });
});