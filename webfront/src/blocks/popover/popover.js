define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    require('bootstrap');

    return Block.extend({
        render: function(){

            Block.prototype.render.apply(this, arguments);

            this.$el.popover({
                trigger: this.trigger
            });
        },
        trigger: 'focus'
    });
});