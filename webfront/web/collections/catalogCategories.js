define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/catalogCategory'),
            initialize: function(models, options){
                this.group = options.group || options.parentModel.id;
                this.storeId = options.storeId || options.parentModel.get('storeId');
            },
            url: function() {
                if (this.storeId){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/groups/'+ this.group  + '/categories'
                } else {
                    return LH.baseApiUrl + '/groups/'+ this.group  + '/categories'
                }
            }
        });
    }
);