define(
    [
        '/kit/block.js',
        '/models/product.js',
        '/helpers/helpers.js',
        './templates.js'
    ],
    function(block, productModel, utils, templates) {
        return block.extend({
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
