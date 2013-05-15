define(
    [
        '/kit/block.js',
        '/collections/products.js',
        './tpl/tpl.js'
    ],
    function(Block, ProductCollection, tpl) {
        return Block.extend({
            defaults: {
                tpl: tpl
            },

            tagName: 'div',
            className: 'productList',

            productCollection: new ProductCollection(),
            initialize: function() {
                var block = this;

                block.listenTo(block.productCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader');
                    }
                });

                block.render();

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