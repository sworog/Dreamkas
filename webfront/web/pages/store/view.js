define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Store = require('blocks/store/store'),
        StoreManagersCollection = require('collections/storeManagers'),
        StoreModel = require('models/store');

    return Page.extend({
        pageName: 'page_store_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            stores: 'GET::{store}'
        },
        initialize: function(storeId) {
            var page = this;

            page.storeId = storeId;

            page.storeModel = new StoreModel({
                id: storeId
            });

            page.storeManagersCollection = new StoreManagersCollection();

            $.when(page.storeModel.fetch(), page.storeManagersCollection.fetch()).then(function(){
                page.render();

                new Store({
                    storeModel: page.storeModel,
                    storeManagersCollection: page.storeManagersCollection,
                    el: document.getElementById('store')
                });
            });
        }
    });
});