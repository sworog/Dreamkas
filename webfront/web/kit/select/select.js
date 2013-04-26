define(
    [
        '../block.js'
    ],
    function(block) {
        return block.extend({
            initialize: function(){
                this.render();
                this.$el.val(this.$el.attr('value'));
            }
        });
    }
);