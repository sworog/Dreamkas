define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        currentUserModel = require('models/currentUser'),
        GrossSalesBySubCategoriesCollection = require('collections/grossSalesBySubCategories');

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
            return !LH.isReportsAllow(['grossSalesBySubCategories']);
        },
        models: {
            store: currentUserModel.stores.length ? currentUserModel.stores.at(0) : null
        },
        collections: {
            grossSalesBySubCategories: function(){
                var page = this;

                var grossSalesBySubCategoriesCollection = new GrossSalesBySubCategoriesCollection();

                grossSalesBySubCategoriesCollection.storeId = page.storeId;
                grossSalesBySubCategoriesCollection.categoryId = page.categoryId;

                return grossSalesBySubCategoriesCollection;
            }
        },
        initialize: function(){
            var page = this;

            page.collections = _.transform(page.collections, function(result, collection, collectionName) {
                result[collectionName] = typeof collection === 'function' ? collection.call(page) : collection
            });

            $.when(page.collections.grossSalesBySubCategories.fetch()).done(function(){
                page.render();
            });
        }
    });
});