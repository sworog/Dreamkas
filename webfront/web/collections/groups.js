define(function(require) {
        //requirements
        var Collection = require('kit/collection'),
            _ = require('lodash');

        return Collection.extend({
            model: require('models/group'),
            storeId: null,
            url: function(){
                if (this.store){
                    return Collection.baseApiUrl + '/stores/' + this.storeId + '/groups';
                } else {
                    return Collection.baseApiUrl + '/groups';
                }
            }
        });
    }
);