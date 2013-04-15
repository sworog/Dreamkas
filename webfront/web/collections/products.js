define(
    [
        '/models/Product.js',
        'json!baseApi/products.json'
    ],
    function(ProductModel, products) {
        var Products = Backbone.Collection.extend({
            model: ProductModel
        });

        return new Products(products);
    }
);