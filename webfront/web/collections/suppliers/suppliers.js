define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('resources/supplier/model'),
        url: Collection.baseApiUrl + '/suppliers'
    });
});