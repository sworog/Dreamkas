define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        url: function(){
            return LH.mockApiUrl + '/reports/grossSales';
        }
    });
});