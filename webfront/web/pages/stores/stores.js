define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stores',
        models: {
            store: null
        },
        collections: {
            stores: require('collections/stores/stores')
        },
        events: {
            'click .store__link': function (e) {
                var page = this,
                    storeId = e.currentTarget.dataset.store_id;

                if (!page.models.store || page.models.store.id !== storeId) {
                    page.models.store = page.collections.stores.get(storeId);
                    page.render();
                }

                $('#modal-storeEdit').modal('show');

            }
        },
        blocks: {
            form_storeAdd: function() {
                var page = this,
                    Form_store = require('blocks/form/form_store/form_store'),
                    form_store = new Form_store({
                        collection: page.collections.stores,
                        el: document.getElementById('form_storeAdd')
                    });

                form_store.on('submit:success', function() {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        page.render();
                    });

                    modal.modal('hide');
                });

                return form_store
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