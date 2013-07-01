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
        permissions: {
            invoices: 'get'
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