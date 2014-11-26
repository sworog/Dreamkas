define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection/collection');

    require('./mocks/get_10');

    return Collection.extend({
        url: Collection.baseApiUrl + '/cashFlows',
        model: require('./model')
    });
});