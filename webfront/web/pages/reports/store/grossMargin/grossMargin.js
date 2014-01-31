define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        currentUserModel = require('models/currentUser'),
        StoreGrossMarginCollection = require('collections/storeGrossMargin');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        groupId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow(['storeGrossMargin']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            storeGrossMargin: function() {
                var page = this;

                var storeGrossMargin = new StoreGrossMarginCollection();

                storeGrossMargin.storeId = page.storeId;

                return storeGrossMargin;
            }
        },
        initialize: function() {
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.storeGrossMargin.fetch()).done(function() {
                page.render();
            });
        }
    });
});