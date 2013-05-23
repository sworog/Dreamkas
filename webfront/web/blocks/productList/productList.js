define(
    [
        '/kit/block.js',
        '/collections/products.js',
        './templates/_templates.js'
    ],
    function(Block, ProductCollection, templates) {
        return Block.extend({
            className: 'productList',
            productCollection: new ProductCollection(),
            templates: templates,

            initialize: function() {
                var block = this;

                block.listenTo(block.productCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader_rows');
                    }
                });

                block.render();

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