define(
    [
        '/kit/block.js',
        '/collections/products.js',
        './tpl/tpl.js'
    ],
    function(Block, ProductCollection, tpl) {
        return Block.extend({
            defaults: {
                productCollection: new ProductCollection(),
                tpl: tpl
            },

            initialize: function() {
                var block = this;

                block.render();
                block.$table = block.$el.find('.balanceList__table');

                block.listenTo(block.productCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader');
                    }
                });

                block.productCollection.fetch();
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