define(
    [
        '/collections/writeOff.js',
        './writeOffList.templates.js'
    ],
    function(WriteOffCollection, templates) {
        return Backbone.Block.extend({
            writeOffCollection: new WriteOffCollection(),
            className: 'writeOffList',
            templates: templates,

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