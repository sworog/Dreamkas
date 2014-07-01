define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/supplier/supplier'),
        url: Collection.baseApiUrl + '/suppliers'
    });
});