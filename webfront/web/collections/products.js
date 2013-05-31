define(
    [
        './baseCollection.js',
        '/models/product.js'
    ],
    function(BaseCollection, ProductModel) {
        return BaseCollection.extend({
            model: ProductModel,
            url: baseApiUrl + "/products"
        });
    }
);