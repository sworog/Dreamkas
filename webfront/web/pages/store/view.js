define(function (require) {
    //requirements
    var Page = require('kit/page'),
        Store = require('blocks/store/store'),
        getUserStore = require('utils/getUserStore'),
        StoreManagerCandidatesCollection = require('collections/storeManagerCandidates'),
        StoreManagersCollection = require('collections/storeManagers'),
        StoreModel = require('models/store'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_store_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function () {
            var page = this,
                userStoreModel = getUserStore(page.storeId);

            if (!(LH.isAllow('stores', 'GET::{store}') || userStoreModel)){
                new Page403();
                return;
            }

            page.storeModel = userStoreModel || new StoreModel({
                id: page.storeId
            });

            page.storeManagerCandidatesCollection = new StoreManagerCandidatesCollection([], {
                storeId: page.storeId
            });

            page.storeManagersCollection = new StoreManagersCollection([], {
                storeId: page.storeId
            });

            $.when(userStoreModel || page.storeModel.fetch(), LH.isAllow('stores', 'GET::{store}/managers') ? page.storeManagerCandidatesCollection.fetch() : {}).then(function () {
                page.render();

                new Store({
                    storeModel: page.storeModel,
                    storeManagerCandidatesCollection: page.storeManagerCandidatesCollection,
                    storeManagersCollection: page.storeModel.managers,
                    el: document.getElementById('store')
                });
            });
        }
    });
});