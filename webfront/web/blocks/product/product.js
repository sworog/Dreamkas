define(function(require) {
        //requirements
        var Block = require('kit/block');

        return Block.extend({
            className: 'product',
            blockName: 'product',
            templates: {
                index: require('tpl!./templates/product.html')
            },
            listeners: {
                model: {
                    change: function(){
                        var block = this;
                        block.render();
                    }
                }
            }
        })
    }
);
