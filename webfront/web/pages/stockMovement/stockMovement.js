define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        SuppliersCollection = require('collections/suppliers'),
        StoresCollection = require('collections/stores/stores'),
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
        models: {
            invoice: null
        },
        events: {
            'click .invoice__link': function(event) {
                var page = this,
                    invoiceId = event.currentTarget.dataset.invoice_id;

                if (!page.models.invoice || page.models.invoice.id !== invoiceId){
                    page.models.invoice = page.collections.invoices.get(invoiceId);
                    page.render();
                }

                $('#modal-invoiceEdit').modal('show');
            }
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
                            form_invoice.model.get('products').reset();
                            form_invoice.model.clear();
                            page.render();
                        });
                    });

                    modal.modal('hide');
                });

                return form_invoice;
            },
            invoiceFormEdit: function() {
                var page = this,
                    Form_invoice = require('blocks/form/form_invoice/form_invoice_modern'),
                    form_invoice = new Form_invoice({
                        el: document.getElementById('form_invoiceEdit'),
                        model: page.models.invoice,
                        collection: page.collections.invoices,
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