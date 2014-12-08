define(function(require, exports, module) {
    //requirements
    var Block = require('kit/collectionBlock/collectionBlock');

    return Block.extend({
        selected: null,
        select: function(value){
            var block = this;

            block.selected = value;

            block.$el.val(value);
        }
    });
});