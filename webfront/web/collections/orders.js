define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection'),
        currentUserModel = require('models/currentUser');

    return Collection.extend({
        model: require('models/order'),
        url: LH.baseApiUrl + '/stores/' + (currentUserModel.stores.length ? currentUserModel.stores.at(0).id : '') + '/orders'
    });
});