define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        cid: module.id,
        model: require('models/orderProduct'),
        url: LH.baseApiUrl + '/orders'
    });
});