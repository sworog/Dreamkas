define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        currentUserModel = require('models/currentUser'),
        GrossSalesByProductsCollection = require('collections/storeProducts');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        groupId: null,
        categoryId: null,
        subcategoryId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow(['grossSalesByProducts']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesByProducts: function(){
                var page = this;

                return new GrossSalesByProductsCollection([], {
                    storeId: page.models.store.id,
                    group: page.groupId,
                    category: page.categoryId,
                    subcategory: page.subcategoryId
                });
            }
        },
        initialize: function(){
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesByProducts.fetch()).done(function(){
                page.render();
            });
        }
    });
});