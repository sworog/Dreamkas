define(
    [
        '/kit/block.js',
        '/collections/writeOff.js',
        './tpl/tpl.js'
    ],
    function(Block, WriteOffCollection, tpl) {
        return Block.extend({
            tpl: tpl,
            writeOffCollection: new WriteOffCollection(),

            initialize: function() {
                var block = this;

                block.render();
                block.$table = block.$el.find('.writeOffList__table');

                block.listenTo(block.writeOffCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader_rows');
                    }
                });

                block.writeOffCollection.fetch();
            },
            renderTable: function() {
                var block = this;

                block.$table
                    .html(block.tpl.table({
                        block: block
                    }))
                    .find('thead').removeClass('preloader_rows');
            }
        });
    }
);