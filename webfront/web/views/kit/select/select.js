define(
    [
        '../main.js'
    ],
    function(Block) {
        return Block.extend({
            initialize: function(){
                this.render();
                this.$el.val(this.$el.attr('value'));
            }
        });
    }
);