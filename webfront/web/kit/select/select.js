define(function(require) {
        //requirements
        var Block = require('kit/block');

        return Block.extend({
            initialize: function() {
                var block = this;

                Block.prototype.initialize.call(block);
                block.$el.val(block.$el.attr('value'));
            }
        });
    }
);