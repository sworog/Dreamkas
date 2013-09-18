define(function(require) {
        //requirements
        var Block = require('kit/core/block');

        return Block.extend({
            __name__: 'product',
            template: require('tpl!blocks/product/templates/index.html')
        })
    }
);
