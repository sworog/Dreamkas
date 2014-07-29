define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        SuppliersCollection = require('collections/suppliers'),
        StoresCollection = require('collections/stores'),
        InvoicesCollection = require('collections/invoices/invoices'),
        InvoiceModel = require('models/invoice/invoice');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        collections: {
            suppliers: new SuppliersCollection(),
            stores: new StoresCollection(),
            invoices: new InvoicesCollection()
        },
        blocks: {
            inputDate: function() {
                var page = this;

                $('.datepicker-default').datepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy',
                    autoclose: true
                });

                return {
                    remove: function() {
                        $('.datepicker-default').datepicker('remove')
                    }
                }
            },
            invoiceFormAdd: function() {
                var page = this,
                    Form_invoice = require('blocks/form/form_invoice/form_invoice_modern'),
                    form_invoice = new Form_invoice({
                        el: document.getElementById('form_invoiceAdd'),
                        model: new InvoiceModel(),
                        parent: page
                    });

                form_invoice.on('submit:success', function(response) {
                    var modal = page.$el.find('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        page.collections.invoices.fetch().then(function() {
                            page.render();
                        });
                    });

                    modal.modal('hide');
                });

                return form_invoice;
            }
        }
    });
});