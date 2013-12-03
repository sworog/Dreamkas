define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        __name__: module.id,
        url: function(){
            return ('http://lh.apiary.io' + LH.baseApiUrl) + '/reports/grossSalesByStores';
        }
    });
});