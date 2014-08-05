define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('kit/core/model'),
        storeId: null,
        url: function(){
            return LH.baseApiUrl + '/stores/' + this.storeId + '/reports/grossMargin';
        }
    });
});