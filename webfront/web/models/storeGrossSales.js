define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        storeId: null,
        url: function(){
            return LH.baseApiUrl + '/stores/' + this.storeId + '/reports/grossSales';
        }
    });
});