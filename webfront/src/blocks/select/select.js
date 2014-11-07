define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        selected: null,
        select: function(value){
            var block = this;

            block.selected = value;

            block.$el.val(value);
        }
    });
});