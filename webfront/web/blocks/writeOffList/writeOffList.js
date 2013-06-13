define(function(require) {
        //requirements
        var Block = require('kit/block'),
            WriteOffCollection = require('collections/writeOff'),
            moment = require('moment');

        return Block.extend({
            writeOffCollection: new WriteOffCollection(),
            className: 'writeOffList',
            templates: {
                index: require('tpl!./templates/writeOffList.html'),
                table: require('tpl!./templates/table.html'),
                row: require('tpl!./templates/row.html')
            },

            initialize: function() {
                var block = this;

                block.render();

                block.listenTo(block.writeOffCollection, {
                    reset: function() {
                        block.renderTable();
                    },
                    request: function() {
                        block.$table.find('thead').addClass('preloader_rows');
                    }
                });

                block.writeOffCollection.fetch();
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