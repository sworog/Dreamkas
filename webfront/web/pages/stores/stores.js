define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        StoreCollection = require('collections/stores/stores'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stores',
        collections: {
            stores: new StoreCollection()
        },
        blocks: {
            form_storeAdd: function() {
                var page = this,
                    Form_store = require('blocks/form/form_store/form_store');

                return new Form_store({
                    collection: page.collections.stores,
                    el: document.getElementById('form_storeAdd')
                });
            },
            form_storeEdit: function() {
                var page = this,
                    Form_store = require('blocks/form/form_store/form_store'),
                    form_store = new Form_store({
                        model: page.models.store,
                        el: document.getElementById('form_storeEdit')
                    });

                form_store.on('submit:success', function() {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        page.collections.stores.fetch().then(function() {
                            page.render()
                        });
                    });

                    modal.modal('hide');
                });

                return form_store;
            }
        }
    })
});