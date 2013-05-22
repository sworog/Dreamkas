define(
    [
        '/kit/block.js',
        '/collections/writeOff.js',
        './tpl/tpl.js'
    ],
    function(Block, WriteOffCollection, tpl) {
        return Block.extend({
            tpl: tpl,
            invoiceCollection: new WriteOffCollection(),

            initialize: function() {
                var block = this;

                block.render();
                block.$table = block.$el.find('.writeOff__table');

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