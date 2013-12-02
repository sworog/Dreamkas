define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        storeId: null,
        url: function(){
            return 'http://lh.apiary.io' + '/stores/' + this.storeId + '/reports/grossSales';
        }
    });
});