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
            },
            'set:loading': function(loading){
                var block = this;
                block.find('thead').toggleClass('preloader_rows', loading);
            }
        })
    }
);