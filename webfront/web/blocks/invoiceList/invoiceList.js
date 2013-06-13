define(function(require) {
        //requirements
        var Block = require('kit/block'),
            InvoiceCollection = require('collections/invoices'),
            moment = require('moment');

        return Block.extend({
            invoiceCollection: new InvoiceCollection(),
            className: 'invoiceList',
            templates: {
                index: require('tpl!./templates/invoiceList.html'),
                table: require('tpl!./templates/table.html'),
                row: require('tpl!./templates/row.html')
            },

            initialize: function() {
                var block = this;

                block.render();

                block.listenTo(block.invoiceCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader_rows');
                    }
                });

                block.invoiceCollection.fetch();
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