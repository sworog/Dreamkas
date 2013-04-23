define(
    [
        '/views/blocks/main.js',
        '/models/product.js',
        '/utils/main.js',
        './templates.js'
    ],
    function(Block, productModel, utils, templates) {
        return Block.extend({
            utils: utils,
            tpl: templates,

            initialize: function() {
                var block = this;

                block.productModel = new productModel({
                    id: block.productId
                });

                block.productModel.fetch();

                block
                    .listenTo(block.productModel, 'sync', this.render);
            }
        })
    }
)
