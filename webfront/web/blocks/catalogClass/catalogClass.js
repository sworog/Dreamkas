define(
    [
        '/kit/block.js',
        './catalogClass.templates.js'
    ],
    function(Block, templates) {
        return Block.extend({
            editMode: false,
            templates: templates
        })
    }
);