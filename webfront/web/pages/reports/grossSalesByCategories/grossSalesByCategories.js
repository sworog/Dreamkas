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
            grossSalesByCategories: function(){
                var page = this;

                var grossSalesByCategories = null;

                if (LH.isReportsAllow(['grossSalesByCategories'])){
                    grossSalesByCategories = new GrossSalesByCategoriesCollection([], {
                        storeId: currentUserModel.stores.at(0).id,
                        group: page.groupId
                    });
                }

                return grossSalesByCategories;
            }
        },
        initialize: function(){
            var page = this;

            page.collections = {
                grossSalesByCategories: page.collections.grossSalesByCategories.call(page)
            };

            $.when(page.collections.grossSalesByCategories.fetch()).done(function(){
                page.render();
            });
        }
    });
});