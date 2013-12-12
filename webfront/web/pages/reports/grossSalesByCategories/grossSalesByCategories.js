define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        currentUserModel = require('models/currentUser'),
        GrossSalesByCategoriesCollection = require('collections/catalogCategories');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        groupId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesByCategories: function() {
                var page = this;

                var grossSalesByCategories = null;

                if (LH.isReportsAllow(['grossSalesByCategories'])) {
                    grossSalesByCategories = new GrossSalesByCategoriesCollection([], {
                        storeId: page.models.store.id,
                        group: page.groupId
                    });
                }

                return grossSalesByCategories;
            }
        },
        initialize: function() {
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesByCategories.fetch()).done(function() {
                page.render();
            });
        }
    });
});