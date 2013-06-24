define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Table_invoices = require('blocks/table/table_invoices/table_invoices'),
        InvoicesCollection = require('collections/invoices');

    return Page.extend({
        pageName: 'page_invoice_list',
        templates: {
            '#content': require('tpl!./templates/list.html')
        },
        initCollections: {
            invoices: function() {
                return new InvoicesCollection();
            }
        },
        initBlocks: function() {
            var page = this;

            new Table_invoices({
                collection: page.collections.invoices,
                el: document.getElementById('table_invoices')
            });
        }
    });
});