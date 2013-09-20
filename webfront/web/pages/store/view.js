define(function (require) {
    //requirements
    var Page = require('kit/core/page'),
        Store = require('blocks/store/store'),
        getUserStore = require('utils/getUserStore'),
        StoreManagerCandidatesCollection = require('collections/storeManagerCandidates'),
        StoreManagersCollection = require('collections/storeManagers'),
        DepartmentManagerCollection = require('collections/departmentManagers'),
        DepartmentManagerCandidatesCollection = require('collections/departmentManagerCandidates'),
        StoreModel = require('models/store'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_store_view',
        partials: {
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

            page.departmentManagerCandidatesCollection = new DepartmentManagerCandidatesCollection([], {
                storeId: page.storeId
            });

            page.departmentManagersCollection = new DepartmentManagerCollection([], {
                storeId: page.storeId
            });

            $.when(
                    userStoreModel || page.storeModel.fetch(),
                    LH.isAllow('stores', 'GET::{store}/storeManagers') ? page.storeManagerCandidatesCollection.fetch() : {},
                    LH.isAllow('stores', 'GET::{store}/departmentManagers') ? page.departmentManagerCandidatesCollection.fetch() : {}
                )
                .then(function () {
                page.render();

                new Store({
                    storeModel: page.storeModel,
                    storeManagerCandidatesCollection: page.storeManagerCandidatesCollection,
                    storeManagersCollection: page.storeModel.storeManagers,
                    departmentManagerCandidatesCollection: page.departmentManagerCandidatesCollection,
                    departmentManagersCollection: page.storeModel.departmentManagers,
                    el: document.getElementById('store')
                });
            });
        }
    });
});