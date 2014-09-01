define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        deepExtend = require('kit/deepExtend/deepExtend');

    return Block.extend({
        render: function(){
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$el.modal({
                show: false
            });
        },
        show: function(data){
            var block = this;

            block.initData(data);
            block.render();

            block.$el.modal('show');
        },
        hide: function(callback){
            var block = this;

            block.$el.one('hidden.bs.modal', function(e) {
                callback && callback.call(block, e);
            });

            block.$el.modal('hide');
        }
    });
});