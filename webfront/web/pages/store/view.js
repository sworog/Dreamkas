define(function (require) {
    //requirements
    var Page = require('kit/page'),
        Store = require('blocks/store/store'),
        getUserStore = require('utils/getUserStore'),
        StoreManagerCandidatesCollection = require('collections/storeManagerCandidates'),
        StoreManagersCollection = require('collections/storeManagers'),
        StoreModel = require('models/store'),
        Page403 = require('pages/403/403');

    return Page.extend({
        pageName: 'page_store_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function (storeId) {
            var page = this,
                userStoreModel = getUserStore(storeId);

            if (!(LH.isAllow('stores', 'GET::{store}') || userStoreModel)){
                new Page403();
                return;
            }

            page.storeId = storeId;

            page.storeModel = userStoreModel || new StoreModel({
                id: storeId
            });

            page.storeManagerCandidatesCollection = new StoreManagerCandidatesCollection([], {
                storeId: storeId
            });

            page.storeManagersCollection = new StoreManagersCollection([], {
                storeId: storeId
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