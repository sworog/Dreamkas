define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('resources/invoice/model'),
        storeId: null,
        url: function(){
            return Collection.baseApiUrl + '/stockMovements';
        }
    });
});