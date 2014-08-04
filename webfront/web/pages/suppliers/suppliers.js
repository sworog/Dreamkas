define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'suppliers',
        models: {
            supplier: null
        },
        collections: {
            suppliers: function(){
                var SupplierCollection = require('collections/suppliers/suppliers');

                return new SupplierCollection();
            }
        },
        blocks: {
            form_supplierAdd: function() {
                var page = this,
                    Form_supplier = require('blocks/form/form_supplier/form_supplier'),
                    form_supplier = new Form_supplier({
                        collection: page.collections.suppliers,
                        el: document.getElementById('form_supplierAdd')
                    });

                form_supplier.on('submit:success', function() {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        page.render();
                    });

                    modal.modal('hide');
                });

                return form_supplier
            },
            form_supplierEdit: function() {
                var page = this,
                    Form_store = require('blocks/form/form_supplier/form_supplier'),
                    form_store = new Form_store({
                        model: page.models.supplier,
                        el: document.getElementById('form_supplierEdit')
                    });

                form_store.on('submit:success', function() {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        page.render();
                    });

                    modal.modal('hide');
                });

                return form_store;
            }
        }
    });
});