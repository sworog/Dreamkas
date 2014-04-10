define(function(require) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/invoice'),
        url: function(){
            if (this.storeId) {
                return LH.baseApiUrl + '/stores/' + this.storeId + '/invoices'
            }
        }
    });
});