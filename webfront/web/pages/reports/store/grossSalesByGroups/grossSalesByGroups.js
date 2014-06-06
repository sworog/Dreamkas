define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        currentUserModel = require('models/currentUser.inst'),
        GrossSalesByGroupsCollection = require('collections/grossSalesByGroups');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        params: {
            storeId: null
        },
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow(['grossSalesByGroups']);
        },
        models: {
        },
        collections: {
            grossSalesByGroups: function() {
                var page = this;

                var grossSalesByGroupsCollection = new GrossSalesByGroupsCollection();

                grossSalesByGroupsCollection.storeId = page.params.storeId;

                return grossSalesByGroupsCollection;
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