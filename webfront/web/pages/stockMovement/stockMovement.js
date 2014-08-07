define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'stockMovement',
        collections: {
            suppliers: function(){
                var SuppliersCollection = require('collections/suppliers/suppliers');

                return new SuppliersCollection();
            },
            stores: function(){
                var StoresCollection = require('collections/stores/stores');

                return new StoresCollection();
            },
            invoices: function(){
                var InvoicesCollection = require('collections/invoices/invoices');

                return new InvoicesCollection();
            }
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
            form_invoiceAdd: function(){
                var block = this,
                    Form_invoice = require('blocks/form/form_invoice/form_invoice');

                return new Form_invoice({
                    el: '#form_invoiceAdd'
                });
            }
        }
    });
});