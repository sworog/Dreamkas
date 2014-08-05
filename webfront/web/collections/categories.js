define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
            _ = require('lodash');

        return Collection.extend({
            model: require('models/category'),
            groupId: null,
            storeId: null,
            initialize: function(data, options){
                _.extend(this, options);
            },
            url: function() {
                if (this.store) {
                    return Collection.baseApiUrl + '/stores/' + this.storeId + '/groups/' + this.groupId + '/categories'
                } else {
                    return Collection.baseApiUrl + '/groups/' + this.groupId + '/categories'
                }
            }
        });
    }
);