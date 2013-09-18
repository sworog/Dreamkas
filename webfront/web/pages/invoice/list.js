define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Table_invoices = require('blocks/table/table_invoices/table_invoices'),
        InvoicesCollection = require('collections/invoices');

    return Page.extend({
        __name__: 'page_invoice_list',
        partials: {
            '#content': require('tpl!./templates/list.html')
        },
        permissions: {
            invoices: 'GET'
        },
        initialize: function(){
            var page = this;

            page.invoicesCollection = new InvoicesCollection();

            $.when(page.invoicesCollection.fetch()).then(function(){
                page.render();

                new Table_invoices({
                    collection: page.invoicesCollection,
                    el: document.getElementById('table_invoices')
                });
            });
        }
    });
});