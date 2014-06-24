define(function(require) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/productInvoice'),
        url: function() {
            return Collection.baseApiUrl + '/stores/' + this.storeId + '/products/' + this.productId + '/invoiceProducts'
        }
    });
});