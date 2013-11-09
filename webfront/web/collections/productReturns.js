define(function(require) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/productReturn'),
        initialize: function(opt) {
            this.productId = opt.productId;
            this.storeId = opt.storeId;
        },
        url: function() {
            return LH.baseApiUrl + '/stores/' + this.storeId + '/products/' + this.productId + '/returnProducts'
        }
    });
});