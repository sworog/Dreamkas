define(
    [
        '/kit/block.js',
        '/models/product.js',
        './tpl/tpl.js'
    ],
    function(Block, ProductModel, tpl) {
        return Block.extend({
            productId: null,
            tpl: tpl,

            initialize: function() {
                var block = this;

                block.productModel = new ProductModel({
                    id: block.productId
                });

                block.listenTo(block.productModel, {
                    sync: function(){
                        block.render();
                    }
                });

                block.productModel.fetch();
            }
        })
    }
)
