define(function(require) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/user'),
        url: function(){
            return LH.baseApiUrl + '/stores/' + this.storeId + '/managers';
        },
        initialize: function(models, options){
            this.storeId = options.storeId;
        }
    });
});