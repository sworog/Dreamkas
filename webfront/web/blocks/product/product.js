define(
    [
        '/models/product.js',
        './product.templates.js'
    ],
    function(ProductModel, templates) {
        return Backbone.Block.extend({
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
