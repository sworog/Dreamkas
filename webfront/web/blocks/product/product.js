define(
    [
        '/kit/block.js',
        '/models/product.js',
        '/helpers/helpers.js',
        './tpl/tpl.js'
    ],
    function(Block, ProductModel, utils, tpl) {
        return Block.extend({
            defaults: {
                productId: null
            },
            utils: utils,
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
