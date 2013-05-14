define(
    [
        '/kit/block.js',
        '/collections/invoices.js',
        './tpl/tpl.js'
    ],
    function(Block, InvoiceCollection, tpl) {
        return Block.extend({
            tpl: tpl,
            invoiceCollection: new InvoiceCollection(),
            initialize: function() {
                var block = this;

                block.render();

                block.$table = block.$el.find('.invoiceList__table');

                block.listenTo(block.invoiceCollection, {
                    reset: function(){
                        block.renderTable();
                        block.$table.find('thead').removeClass('preloader');
                    },
                    request: function(){
                        block.$table.find('thead').addClass('preloader');
                    }
                });

                block.invoiceCollection.fetch();
            },
            renderTable: function() {
                var block = this;

                block.$table.html(block.tpl.table({
                    block: block
                }));
            }
        });
    }
);