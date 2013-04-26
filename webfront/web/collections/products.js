define(
    [
        './baseCollection.js',
        '/models/product.js'
    ],
    function(baseCollection, ProductModel) {
        return baseCollection.extend({
            model: ProductModel,
            url: baseApiUrl + "/products.json"
        });
    }
);