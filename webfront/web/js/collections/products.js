var ProductsCollection = Backbone.Collection.extend({
    model: Product,
    url: 'http://lighthouse/api/1/products.json'
});