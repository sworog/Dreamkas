define(function(require) {
        //requirements
        var Block = require('kit/block');

        return Block.extend({
            blockName: 'store',
            templates: {
                index: require('tpl!blocks/store/templates/index.html')
            }
        })
    }
);
