define(
    [
        '/kit/block.js',
        './table.templates.js'
    ],
    function(Block, templates) {
        return Block.extend({
            loading: false,
            columns: [],
            data: [],
            tagName: 'table',
            className: 'table',
            templates: templates,

            'set:loading': function(loading){
                var block = this;
                block.find('thead').toggleClass('preloader_rows', loading);
            }
        })
    }
);