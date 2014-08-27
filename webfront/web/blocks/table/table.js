define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        collection: null,
        initData: function(){
            var block = this;

            Block.prototype.initData.apply(block, arguments);

            block.listenTo(block.collection, {
                'add remove reset': function(){
                    block.render();
                }
            });
        }
    });
});