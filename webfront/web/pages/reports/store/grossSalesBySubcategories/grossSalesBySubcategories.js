define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        currentUserModel = require('models/currentUser'),
        GrossSalesBySubcategoriesCollection = require('collections/grossSalesBySubcategories');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        groupId: null,
        categoryId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function(){
            return !LH.isReportsAllow(['grossSalesBySubcategories']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesBySubcategories: function(){
                var page = this;

                var grossSalesBySubcategoriesCollection = new GrossSalesBySubcategoriesCollection();

                grossSalesBySubcategoriesCollection.storeId = page.storeId;
                grossSalesBySubcategoriesCollection.categoryId = page.categoryId;

                return grossSalesBySubcategoriesCollection;
            }
        },
        initialize: function(){
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesBySubcategories.fetch()).done(function(){
                page.render();
            });
        }
    });
});