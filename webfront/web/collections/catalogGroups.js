define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/catalogGroup'),
            url: function(){
                if (this.storeId){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/groups';
                } else {
                    return LH.baseApiUrl + '/groups';
                }
            },
            initialize: function(models, options){
                this.storeId = options.storeId;
                console.log(this.storeId);
            }
        });
    }
);