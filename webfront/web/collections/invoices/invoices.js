define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('models/invoice/invoice'),
        storeId: null,
        url: function(){
            return Collection.baseApiUrl + '/stockMovements';
        }
    });
});