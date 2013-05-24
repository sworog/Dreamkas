define(
    [
        '/kit/block.js',
        '/collections/invoices.js',
        './invoiceList.templates.js'
    ],
    function(Block, InvoiceCollection, templates) {
        return Block.extend({
            invoiceCollection: new InvoiceCollection(),
            className: 'invoiceList',
            templates: templates,

            initialize: function() {
                var block = this;

                block.render();

                block.listenTo(block.invoiceCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader_rows');
                    }
                });

                block.invoiceCollection.fetch();
            },
            renderTable: function() {
                var block = this;

                block.$table
                    .html(block.templates.table({
                        block: block
                    }))
                    .find('thead').removeClass('preloader_rows');
            }
        });
    }
);