define(function(require) {
    //requirements
    var Collection = require('kit/core/collection'),
        currentUserModel = require('models/currentUser');

    return Collection.extend({
        model: require('models/productWriteOff'),
        initialize: function(opt) {
            this.productId = opt.productId;
        },
        url: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products/' + this.productId + '/writeoffsProducts'
        }
    });
});