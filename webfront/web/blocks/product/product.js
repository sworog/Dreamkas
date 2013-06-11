define(function(require) {
        //requirements
        var ProductModel = require('models/product');

        return Backbone.Block.extend({
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
