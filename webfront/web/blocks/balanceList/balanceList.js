define(function(require) {

        //requirements
        var ProductCollection = require('collections/products');

        return Backbone.Block.extend({
            productCollection: new ProductCollection(),
            className: 'balanceList',
            blockName: 'balanceList',
            templates: {
                index: require('tpl!./templates/balanceList.html'),
                table: require('tpl!./templates/table.html'),
                row: require('tpl!./templates/row.html')
            },

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