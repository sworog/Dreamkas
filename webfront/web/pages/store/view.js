define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Store = require('blocks/store/store'),
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

            $.when(page.storeModel.fetch()).then(function(){
                page.render();

                new Store({
                    storeModel: page.storeModel,
                    el: document.getElementById('store')
                });
            });
        }
    });
});