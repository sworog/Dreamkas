define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection'),
        currentUserModel = require('models/currentUser.inst');

    return Collection.extend({
        model: require('models/order'),
        url: LH.baseApiUrl + '/stores/orders?incomplete=1'
    });
});