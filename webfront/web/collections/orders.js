define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/order'),
        url: Collection.baseApiUrl + '/stores/orders?incomplete=1'
    });
});