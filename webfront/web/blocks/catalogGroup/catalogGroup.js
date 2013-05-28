define(
    [
        '/kit/block.js',
        './catalogGroup.templates.js'
    ],
    function(Block, templates) {
        return Block.extend({
            editMode: false,
            templates: templates
        })
    }
);