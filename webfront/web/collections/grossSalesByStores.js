define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('kit/model/model'),
        url: Collection.baseApiUrl + '/reports/grossSalesByStores'
    });
});