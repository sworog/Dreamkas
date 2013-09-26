define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Table_balance = require('blocks/table/table_balance/table_balance'),
        StoreProductsCollection = require('collections/storeProducts'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_common_balance',
        partials: {
            '#content': require('tpl!./templates/balance.html')
        },
        initialize: function(pageParams){
            var page = this;

            if (currentUserModel.stores.length){
                pageParams.storeId = currentUserModel.stores.at(0).id;
            } else {
                new Page403();
            }

            if (
                !LH.isAllow('products', 'GET')
                && (!LH.isAllow('stores', 'GET::{store}/products') || !currentUserModel.stores.length)
            ) {
                new Page403();
            }

            page.storeProductsCollection = new StoreProductsCollection([], {
                storeId: (pageParams.storeId) ? pageParams.storeId : null
            });

            $.when(page.storeProductsCollection.fetch()).then(function(){
                page.render();

                new Table_balance({
                    collection: page.storeProductsCollection,
                    el: document.getElementById('table_balance')
                })
            })
        }
    });
});