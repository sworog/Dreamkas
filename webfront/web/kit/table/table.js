define(
    [
        '/kit/block.js'
    ],
    function(Block) {
        return Block.extend({
            loading: false,
            className: 'table',

            initialize: function(){
                var block = this;
            }
        })
    }
);