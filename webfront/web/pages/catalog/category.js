define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        pageParams = require('pages/catalog/params'),
        CatalogCategoryBlock = require('blocks/catalogCategory/catalogCategory'),
        CatalogProductsCollection = require('collections/catalogProducts'),
        СatalogGroupModel = require('models/catalogGroup'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    var router = new Backbone.Router();

    return Page.extend({
        __name__: 'page_catalog_category',
        templates: {
            '#content': require('tpl!./templates/category.html')
        },
        initialize: function(catalogGroupId, catalogCategoryId, catalogSubCategoryId, params){
            var page = this;

            if (page.referrer && page.referrer.__name__.indexOf('page_catalog') >= 0){
                _.extend(pageParams, params);
            } else {
                _.extend(pageParams, {
                    editMode: false
                }, params)
            }

            if (!pageParams.storeId && !LH.isAllow('categories', 'GET::{category}')){
                new Page403();
                return;
            }

            if (pageParams.storeId && !LH.isAllow('stores/{store}/categories/{category}')){
                new Page403();
                return;
            }

            if (page.referrer && page.referrer.__name__ === 'page_product_form'){
                pageParams.editMode = true;
            }

            if (!LH.isAllow('groups', 'POST')) {
                pageParams.editMode = false;
            }

            router.navigate(router.toFragment(document.location.pathname, {
                editMode: pageParams.editMode,
                storeId: pageParams.storeId
            }));

            page.catalogGroupModel = new СatalogGroupModel({
                id: catalogGroupId,
                storeId: pageParams.storeId
            });

            page.catalogProductsCollection = new CatalogProductsCollection([], {
                subCategory: catalogSubCategoryId,
                storeId: pageParams.storeId
            });

            $.when(page.catalogGroupModel.fetch(), catalogSubCategoryId ? page.catalogProductsCollection.fetch() : {}).then(function(){

                page.catalogCategoryModel = page.catalogGroupModel.categories.get(catalogCategoryId);
                page.catalogSubCategoriesCollection = page.catalogCategoryModel.subCategories;

                page.render();

                new CatalogCategoryBlock({
                    el: document.getElementById('catalogCategory'),
                    catalogCategoryModel: page.catalogCategoryModel,
                    catalogSubCategoriesCollection: page.catalogSubCategoriesCollection,
                    catalogSubCategoryId: catalogSubCategoryId,
                    catalogProductsCollection: page.catalogProductsCollection,
                    editMode: pageParams.editMode
                })
            });
        }
    });
});