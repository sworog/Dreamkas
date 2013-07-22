define(function(require) {
        //requirements
        var Block = require('kit/block');

        return Block.extend({
            blockName: 'product',
            templates: {
                index: require('tpl!blocks/product/templates/index.html')
            },
            listeners: {
                model: {
                    change: function() {
                        var block = this;
                        block.render();
                    }
                }
            }
        })
    }
);
