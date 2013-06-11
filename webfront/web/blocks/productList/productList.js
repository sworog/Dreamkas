define(function(require) {
        //requirements
        var ProductCollection = require('collections/products');

        return Backbone.Block.extend({
            className: 'productList',
            productCollection: new ProductCollection(),
            templates: {
                index: require('tpl!./templates/productList.html'),
                table: require('tpl!./templates/table.html'),
                row: require('tpl!./templates/row.html')
            },

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