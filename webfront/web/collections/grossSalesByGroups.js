define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        __name__: module.id,
        model: require('kit/core/model'),
        storeId: null,
        url: function(){
            return LH.mockApiUrl + '/stores/' + this.storeId + '/reports/grossSalesByGroups';
        }
    });
});