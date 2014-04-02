define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        pageParams = require('pages/catalog/params'),
        CatalogCategoryBlock = require('blocks/catalogCategory/catalogCategory'),
        CatalogProductsCollection = require('collections/catalogProducts'),
        StoreProductsCollection = require('collections/storeProducts'),
        СatalogGroupModel = require('models/catalogGroup'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403'),
        router = require('router');

    return Page.extend({
        __name__: 'page_catalog_category',
        section: 'products',
        partials: {
            '#content': require('tpl!./templates/category.html')
        },
        initialize: function(params) {
            var page = this;

            if (page.referrer.__name__ && page.referrer.__name__.indexOf('page_catalog') >= 0) {
                _.extend(params, pageParams);
            } else {
                pageParams.editMode = params.editMode || pageParams.editMode || 'false'
            }

            if (currentUserModel.stores.length) {
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId && !LH.isAllow('categories', 'GET::{category}')) {
                new Page403();
                return;
            }

            if (pageParams.storeId && !LH.isAllow('stores/{store}/categories/{category}')) {
                new Page403();
                return;
            }

            if (page.referrer && page.referrer.__name__ === 'page_product_form') {
                pageParams.editMode = 'true';
            }

            if (!LH.isAllow('groups', 'POST')) {
                pageParams.editMode = 'false';
            }

            router.navigate(document.location.pathname + '?editMode=' + pageParams.editMode + '&storeId=' + pageParams.storeId, {
                replace: true,
                trigger: false
            });

            page.catalogGroupModel = new СatalogGroupModel({
                id: params.catalogGroupId,
                storeId: pageParams.storeId
            });

            page.catalogProductsCollection = new CatalogProductsCollection([], {
                subCategory: params.catalogSubCategoryId,
                storeId: pageParams.storeId
            });

            page.storeProductsCollection = new StoreProductsCollection([], {
                subCategory: params.catalogSubCategoryId,
                storeId: pageParams.storeId
            });

            $.when(
                    page.catalogGroupModel.fetch(),
                    !pageParams.storeId && params.catalogSubCategoryId ? page.catalogProductsCollection.fetch() : {},
                    pageParams.storeId && params.catalogSubCategoryId ? page.storeProductsCollection.fetch() : {}
                ).then(function() {

                    if (pageParams.storeId && params.catalogSubCategoryId){
                        page.catalogProductsCollection.reset(_.map(page.storeProductsCollection.toJSON(), 'product'));
                    }

                    page.catalogCategoryModel = page.catalogGroupModel.categories.get(params.catalogCategoryId);
                    page.catalogSubCategoriesCollection = page.catalogCategoryModel.subCategories;

                    page.render();

                    new CatalogCategoryBlock({
                        el: document.getElementById('catalogCategory'),
                        catalogCategoryModel: page.catalogCategoryModel,
                        catalogSubCategoriesCollection: page.catalogSubCategoriesCollection,
                        catalogSubCategoryId: params.catalogSubCategoryId,
                        catalogProductsCollection: page.catalogProductsCollection,
                        storeProductsCollection: page.storeProductsCollection,
                        editMode: pageParams.editMode,
                        section: page.section,
                        storeId: pageParams.storeId
                    })
                });
        }
    });
});