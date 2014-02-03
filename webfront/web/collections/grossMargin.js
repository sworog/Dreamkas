define(function(require, exports, module) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('kit/core/model'),
        url: function(){
            return LH.baseApiUrl + '/reports/grossMargin';
        }
    });
});