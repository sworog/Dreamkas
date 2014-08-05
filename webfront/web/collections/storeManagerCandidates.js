define(function(require) {
    //requirements
    var Collection = require('kit/core/collection');

    return Collection.extend({
        model: require('models/user'),
        comparator: 'name',
        url: function(){
            return LH.baseApiUrl + '/stores/' + this.storeId + '/storeManagers?candidates=1';
        },
        initialize: function(models, options){
            this.storeId = options.storeId;
        }
    });
});