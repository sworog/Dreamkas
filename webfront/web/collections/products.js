define(
    [
        '/models/product.js'
    ],
    function(ProductModel) {
        return Backbone.Collection.extend({
            model: ProductModel,
            url: baseApiUrl + "/products.json"
        });
    }
);