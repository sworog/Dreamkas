define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        url: Collection.baseApiUrl + '/cashFlows',
        model: require('./model')
    });
});