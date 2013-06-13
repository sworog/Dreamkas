define(function(require) {
        //requirements
        var Block = require('kit/block'),
            ProductModel = require('models/product');

        return Block.extend({
            productId: null,
            className: 'product',
            templates: {
                index: require('tpl!./templates/product.html')
            },

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
