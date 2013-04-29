define(
    [
        '/kit/block.js',
        '/collections/invoices.js',
        './tpl/tpl.js'
    ],
    function(block, invoiceCollection, tpl) {
        return block.extend({
            tpl: tpl,
            invoiceCollection: new invoiceCollection(),
            initialize: function() {
                var block = this;

                block.listenTo(block.invoiceCollection, {
                    reset: function(){
                        block.renderTable();
                        block.$table.removeClass('table_loading');
                    },
                    request: function(){
                        block.$table.addClass('table_loading');
                    }
                });

                block.render();

                block.$table = block.$el.find('.invoiceList__table');

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