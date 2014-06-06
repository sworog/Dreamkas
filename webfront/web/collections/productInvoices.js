define(function(require) {
    //requirements
    var Collection = require('kit/core/collection'),
        currentUserModel = require('models/currentUser.inst');

    return Collection.extend({
        model: require('models/productInvoice'),
        initialize: function(opt) {
            this.productId = opt.productId;
            this.storeId = opt.storeId;
        },
        url: function() {
            return LH.baseApiUrl + '/stores/' + this.storeId + '/products/' + this.productId + '/invoiceProducts'
        }
    });
});