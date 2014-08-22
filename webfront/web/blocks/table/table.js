define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        collection: null,
        initResources: function(){
            var block = this;

            Block.prototype.initResources.apply(block, arguments);

            block.listenTo(block.collection, {
                'add remove reset': function(){
                    block.render();
                }
            });
        }
    });
});