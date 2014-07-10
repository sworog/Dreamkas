define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/collection/collection');

    return Collection.extend({
        model: require('models/order'),
        storeId: null,
        url: function(){
            return Collection.baseApiUrl + '/stores/' + this.storeId + '/orders?incomplete=1'
        }
    });
});