define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        initialize: function(){
            var block = this;

            block.$el.dropdown();
        }
    });
});