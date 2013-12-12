define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/catalogGroup'),
            storeId: null,
            url: function(){
                if (this.storeId){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/groups';
                } else {
                    return LH.baseApiUrl + '/groups';
                }
            },
            initialize: function(models, options){
                this.storeId = options.storeId;
            }
        });
    }
);