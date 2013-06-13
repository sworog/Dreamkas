define(function(require) {
        //requirements
        var Block = require('kit/block');

        return Block.extend({
            initialize: function() {
                this.render();
                this.$el.val(this.$el.attr('value'));
            }
        });
    }
);