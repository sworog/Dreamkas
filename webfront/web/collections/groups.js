define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/group'),
            initialize: function(data, opt){
                this.storeId = opt.storeId;
            },
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