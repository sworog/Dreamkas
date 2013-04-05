var ProductsCollection = Backbone.Collection.extend({
    model: Product,
    url: baseApiUrl + "/api/1/products.json"
});