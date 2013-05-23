define(
    [
        '/kit/block.js',
        '/models/product.js',
        './templates/_templates.js'
    ],
    function(Block, ProductModel, templates) {
        return Block.extend({
            productId: null,
            className: 'product',
            templates: templates,

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
