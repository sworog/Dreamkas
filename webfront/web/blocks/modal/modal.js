define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        _ = require('lodash');

    return Block.extend({
        events: {
            'hidden.bs.modal': function(){
                var block = this;

                _.each(block.el.querySelectorAll('form'), function(form){
                    form.reset();
                });
            }
        },
        render: function(){
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$el.modal({
                show: false
            });
        },
        show: function(opt){
            var block = this;

            block.set(opt);

            block.render();

            block.$el.modal('show');
        }
    });
});