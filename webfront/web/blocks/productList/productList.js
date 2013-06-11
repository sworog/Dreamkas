define(
    [
        '/collections/products.js',
        './productList.templates.js'
    ],
    function(ProductCollection, templates) {
        return Backbone.Block.extend({
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