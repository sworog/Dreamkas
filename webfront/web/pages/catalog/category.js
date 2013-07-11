define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        CatalogCategoryBlock = require('blocks/catalogCategory/catalogCategory'),
        СatalogSubcategoriesCollection = require('collections/catalogSubcategories'),
        СatalogGroupModel = require('models/catalogGroup');

    return Page.extend({
        pageName: 'page_catalog_category',
        templates: {
            '#content': require('tpl!./templates/category.html')
        },
        permissions: {
            categories: 'GET::{category}'
        },
        initialize: function(catalogGroupId, catalogCategoryId, catalogSubcategoryId, params){
            var page = this;

            if (page.referer && page.referer.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            page.catalogGroupModel = new СatalogGroupModel({
                id: catalogGroupId
            });

            page.catalogSubcategoriesCollection = new СatalogSubcategoriesCollection(null, {
                parentCategoryId: catalogCategoryId,
                parentGroupId: catalogGroupId
            });

            $.when(page.catalogGroupModel.fetch(), page.catalogSubcategoriesCollection.fetch()).then(function(){
                page.render();

                page.catalogCategoryModel = page.catalogGroupModel.categories.get(catalogCategoryId);

                new CatalogCategoryBlock({
                    el: document.getElementById('catalogCategory'),
                    catalogCategoryModel: page.catalogCategoryModel,
                    catalogSubcategoriesCollection: page.catalogSubcategoriesCollection,
                    catalogSubcategoryId: catalogSubcategoryId
                })
            });
        }
    });
});