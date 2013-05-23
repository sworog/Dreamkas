define(
    [
        '/kit/block.js',
        '/collections/products.js',
        './templates/_templates.js'
    ],
    function(Block, ProductCollection, templates) {
        return Block.extend({
            productCollection: new ProductCollection(),
            className: 'balanceList',
            templates: templates,

            initialize: function() {
                var block = this;

                block.render();

                block.listenTo(block.productCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader_rows');
                    }
                });

                block.productCollection.fetch();
            },
            renderTable: function() {
                var block = this;

                block.$table
                    .html(block.templates.table({
                        block: block
                    }))
                    .find('thead').removeClass('preloader_rows');
            }
        });
    }
);