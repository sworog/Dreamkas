define(function(require) {
    //requirements
    var Page = require('pages/page'),
        _ = require('underscore'),
        pageParams = require('pages/catalog/params'),
        CatalogCategoryBlock = require('blocks/catalogCategory/catalogCategory'),
        СatalogGroupModel = require('models/catalogGroup'),
        СatalogCategoryModel = require('models/catalogCategory');

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

            page.catalogCategoryModel = new СatalogCategoryModel({
                id: catalogCategoryId
            });

            $.when(page.catalogGroupModel.fetch(), page.catalogCategoryModel.fetch()).then(function(){
                page.render();

                console.log(page.catalogCategoryModel);

                new CatalogCategoryBlock({
                    el: document.getElementById('catalogCategory'),
                    catalogCategoryModel: page.catalogCategoryModel,
                    catalogSubcategoryId: catalogSubcategoryId
                })
            });
        }
    });
});