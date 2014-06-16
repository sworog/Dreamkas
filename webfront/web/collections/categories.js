define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/category'),
            groupId: null,
            storeId: null,
            url: function() {
                if (this.storeId) {
                    return Collection.baseApiUrl + '/stores/' + this.storeId + '/groups/' + this.groupId + '/categories'
                } else {
                    return Collection.baseApiUrl + '/groups/' + this.groupId + '/categories'
                }
            }
        });
    }
);