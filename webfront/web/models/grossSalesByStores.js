define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        __name__: module.id,
        url: function(){
            return ('http://lh.apiary.io' + LH.baseApiUrl) + '/reports/grossSalesByStores';
        }
    });
});