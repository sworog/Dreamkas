define(
    [
        '/kit/block.js',
        '/collections/products.js',
        './tpl/tpl.js'
    ],
    function(block, productCollection, tpl) {
        return block.extend({
            tpl: tpl,
            productCollection: new productCollection(),
            initialize: function() {
                var block = this;

                block.listenTo(block.productCollection, {
                    reset: function(){
                        block.renderTable();
                        block.$table.removeClass('table_loading');
                    },
                    request: function(){
                        block.$table.addClass('table_loading');
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