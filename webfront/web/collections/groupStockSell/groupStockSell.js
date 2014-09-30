define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            groupId: null,
            url: function(){
                return Collection.baseApiUrl + '/subcategories/' + this.groupId + '/products'
            }
        });
    }
);