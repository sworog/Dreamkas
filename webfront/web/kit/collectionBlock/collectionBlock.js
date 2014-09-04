define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            if (block.collection){
                block.collection.on({
                    'add remove reset': function(){
                        block.render();
                    }
                });
            }
        }
    });
});