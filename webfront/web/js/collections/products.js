var ProductsCollection = Backbone.Collection.extend({
    model: Product,
    url: baseApiUrl + "/products.json"
});