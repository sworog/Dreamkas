define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('models/productReturn'),
        url: function() {
            return Collection.baseApiUrl + '/stores/' + this.storeId + '/products/' + this.productId + '/returnProducts'
        }
    });
});