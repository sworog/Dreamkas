define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        currentUserModel = require('models/currentUser'),
        GrossSalesByProductsCollection = require('collections/grossSalesByProducts');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        groupId: null,
        categoryId: null,
        subCategoryId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isReportsAllow(['grossSalesByProducts']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesByProducts: function() {
                var page = this;

                var grossSalesByProductsCollection = new GrossSalesByProductsCollection();

                grossSalesByProductsCollection.subCategoryId = page.subCategoryId;
                grossSalesByProductsCollection.storeId = page.storeId;

                return grossSalesByProductsCollection;
            }
        },
        initialize: function() {
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesByProducts.fetch()).done(function() {
                page.render();
            });
        }
    });
});