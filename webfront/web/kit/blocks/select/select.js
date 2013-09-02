define(function(require) {
        //requirements
        var Block = require('kit/core/block');

        return Block.extend({
            __name__: 'select',
            className: 'select',

            initialize: function() {
                var block = this;

                block.$el.val(block.$el.attr('value'));
            }
        });
    }
);