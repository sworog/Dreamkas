define(function(require) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/writeOff'),
        url: function(){
            if (this.storeId) {
                return LH.baseApiUrl + '/stores/' + this.storeId + '/writeoffs'
            }
        },
        initialize: function(models, options){
            this.storeId = options.storeId;
        }
    });
});