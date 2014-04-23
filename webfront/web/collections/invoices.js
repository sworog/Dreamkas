define(function(require) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/invoice'),
        storeId: null,
        url: function(){
            return LH.baseApiUrl + '/stores/' + this.storeId + '/invoices';
        }
    });
});