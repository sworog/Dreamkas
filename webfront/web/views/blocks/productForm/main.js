define(
    [
        '/models/Product.js',
        'tpl!./main.html'
    ],
    function(Product, main) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;

                block.render();
            },
            tpl: {
                main: main
            },
            model: Product
        });
    }
);