define(
    [
        '/ui/models/Product.js',
        'tpl!./productForm.html'
    ],
    function(Product, productForm) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;

                block.render();
            },
            templates: {
                main: productForm
            },
            model: Product
        });
    }
);