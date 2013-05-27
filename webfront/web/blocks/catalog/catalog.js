define(
    [
        '/kit/block.js',
        './catalog.templates.js'
    ],
    function(Block, templates) {
        return Block.extend({
            editMode: false,
            templates: templates
        })
    }
);