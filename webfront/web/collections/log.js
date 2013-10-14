define(function(require) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/log'),
        url: LH.baseApiUrl + '/logs'
    });
});