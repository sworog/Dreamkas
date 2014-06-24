define(function(require) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/productWriteOff'),
        url: function() {
            return Collection.baseApiUrl + '/stores/' + this.storeId + '/products/' + this.productId + '/writeOffProducts'
        }
    });
});