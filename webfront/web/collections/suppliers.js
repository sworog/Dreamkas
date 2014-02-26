define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        cid: module.id,
        model: require('models/supplier'),
        url: LH.baseApiUrl + '/suppliers'
    });
});