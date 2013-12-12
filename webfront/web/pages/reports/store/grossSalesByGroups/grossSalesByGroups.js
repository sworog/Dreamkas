define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        currentUserModel = require('models/currentUser'),
        GrossSalesByGroupsCollection = require('collections/catalogGroups');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow(['grossSalesByGroups']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesByGroups: function() {
                var page = this;

                return new GrossSalesByGroupsCollection([], {
                    storeId: page.storeId
                });
            }
        },
        initialize: function() {
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesByGroups.fetch()).done(function() {
                page.render();
            });
        }
    });
});