define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('kit/model'),
        url: Collection.baseApiUrl + '/reports/grossSalesByStores'
    });
});