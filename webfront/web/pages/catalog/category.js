define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        CatalogCategoryBlock = require('blocks/catalogCategory/catalogCategory'),
        CatalogProductsCollection = require('collections/catalogProducts'),
        СatalogGroupModel = require('models/catalogGroup');

    return Page.extend({
        pageName: 'page_catalog_category',
        templates: {
            '#content': require('tpl!./templates/category.html')
        },
        permissions: {
            categories: 'GET::{category}'
        },
        initialize: function(catalogGroupId, catalogCategoryId, catalogSubCategoryId, params){
            var page = this;

            if (page.referer && page.referer.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            if (!LH.isAllow('groups', 'POST')) {
                pageParams.editMode = false;
            }

            page.catalogGroupModel = new СatalogGroupModel({
                id: catalogGroupId
            });

            page.catalogProductsCollection = new CatalogProductsCollection([], {
                subCategory: catalogSubCategoryId
            });

            $.when(page.catalogGroupModel.fetch(), catalogSubCategoryId ? page.catalogProductsCollection.fetch() : {}).then(function(){

                page.catalogCategoryModel = page.catalogGroupModel.categories.get(catalogCategoryId);
                page.catalogSubcategoriesCollection = page.catalogCategoryModel.subCategories;

                page.render();

                new CatalogCategoryBlock({
                    el: document.getElementById('catalogCategory'),
                    catalogCategoryModel: page.catalogCategoryModel,
                    catalogSubcategoriesCollection: page.catalogSubcategoriesCollection,
                    catalogSubCategoryId: catalogSubCategoryId,
                    catalogProductsCollection: page.catalogProductsCollection,
                    editMode: pageParams.editMode
                })
            });
        }
    });
});