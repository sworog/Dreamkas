define(
    [
        '/kit/block.js',
        '/collections/products.js',
        './tpl/tpl.js'
    ],
    function(Block, ProductCollection, tpl) {
        return Block.extend({
            tpl: tpl,
            productCollection: new ProductCollection(),
            initialize: function() {
                var block = this;

                block.listenTo(block.productCollection, {
                    reset: function(){
                        block.renderTable();
                        block.$table.find('thead').removeClass('preloader');
                    },
                    request: function(){
                        block.$table.find('thead').addClass('preloader');
                    }
                });

                block.render();

                block.$table = block.$el.find('.productList__table');

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