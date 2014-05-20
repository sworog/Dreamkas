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
        router = require('kit/router/router');

    return Page.extend({
        __name__: 'page_catalog_category',
        params: {
            catalogGroupId: null,
            catalogCategoryId: null,
            catalogSubCategoryId: null,
            editMode: null,
            section: 'products'
        },
        partials: {
            '#content': require('tpl!./templates/category.html')
        },
        initialize: function() {
            var page = this;

            if (page.referrer.__name__ && page.referrer.__name__.indexOf('page_catalog') >= 0) {
                _.extend(page.params, pageParams);
            } else {
                pageParams.editMode = page.params.editMode || pageParams.editMode || 'false'
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
                id: page.params.catalogGroupId,
                storeId: pageParams.storeId
            });

            page.catalogProductsCollection = new CatalogProductsCollection([], {
                subCategory: page.params.catalogSubCategoryId,
                storeId: pageParams.storeId
            });

            page.storeProductsCollection = new StoreProductsCollection([], {
                subCategory: page.params.catalogSubCategoryId,
                storeId: pageParams.storeId
            });

            $.when(
                    page.catalogGroupModel.fetch(),
                    !pageParams.storeId && page.params.catalogSubCategoryId ? page.catalogProductsCollection.fetch() : {},
                    pageParams.storeId && page.params.catalogSubCategoryId ? page.storeProductsCollection.fetch() : {}
                ).then(function() {

                    if (pageParams.storeId && page.params.catalogSubCategoryId){
                        page.catalogProductsCollection.reset(_.map(page.storeProductsCollection.toJSON(), 'product'));
                    }

                    page.catalogCategoryModel = page.catalogGroupModel.categories.get(page.params.catalogCategoryId);
                    page.catalogSubCategoriesCollection = page.catalogCategoryModel.subCategories;

                    page.render();

                    new CatalogCategoryBlock({
                        el: document.getElementById('catalogCategory'),
                        catalogCategoryModel: page.catalogCategoryModel,
                        catalogSubCategoriesCollection: page.catalogSubCategoriesCollection,
                        catalogSubCategoryId: page.params.catalogSubCategoryId,
                        catalogProductsCollection: page.catalogProductsCollection,
                        storeProductsCollection: page.storeProductsCollection,
                        editMode: pageParams.editMode,
                        section: page.params.section,
                        storeId: pageParams.storeId
                    })
                });
        }
    });
});