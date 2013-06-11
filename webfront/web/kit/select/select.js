define(function(require) {
        return Backbone.Block.extend({
            initialize: function() {
                this.render();
                this.$el.val(this.$el.attr('value'));
            }
        });
    }
);