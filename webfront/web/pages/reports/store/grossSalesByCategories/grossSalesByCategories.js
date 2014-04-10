define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        currentUserModel = require('models/currentUser'),
        GrossSalesByCategoriesCollection = require('collections/grossSalesByCategories');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        params: {
            storeId: null,
            groupId: null
        },
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow(['grossSalesByCategories']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesByCategories: function() {
                var page = this;

                var grossSalesByCategories = new GrossSalesByCategoriesCollection();

                grossSalesByCategories.storeId = page.params.storeId;
                grossSalesByCategories.groupId = page.params.groupId;

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