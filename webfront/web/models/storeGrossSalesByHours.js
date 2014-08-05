define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        __name__: module.id,
        storeId: null,
        url: function(){
            return LH.baseApiUrl + '/stores/' + this.storeId + '/reports/grossSalesByHours';
        }
    });
});