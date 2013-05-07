define(
    [
        '/views/kit/main.js',
        '/collections/products.js',
        './tpl/tpl.js'
    ],
    function(Block, ProductCollection, tpl) {
        return Block.extend({
            productCollection: new ProductCollection(),
            tpl: tpl,

            initialize: function() {
                var block = this;

                block.listenTo(block.productCollection, {
                    sync: function(){
                        block.renderTable();
                    }
                });

                block.render();

                block.$table = block.$el.find('.balanceList__table');

                block.productCollection.fetch();
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