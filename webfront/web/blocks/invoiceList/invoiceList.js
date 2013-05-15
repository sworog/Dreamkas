define(
    [
        '/kit/block.js',
        '/collections/invoices.js',
        './tpl/tpl.js'
    ],
    function(Block, InvoiceCollection, tpl) {
        return Block.extend({
            defaults: {
                invoiceCollection: new InvoiceCollection(),
                tpl: tpl
            },

            initialize: function() {
                var block = this;

                block.render();
                block.$table = block.$el.find('.invoiceList__table');

                block.listenTo(block.invoiceCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader');
                    }
                });

                block.invoiceCollection.fetch();
            },
            renderTable: function() {
                var block = this;

                block.$table
                    .html(block.tpl.table({
                        block: block
                    }))
                    .find('thead').removeClass('preloader');
            }
        });
    }
);