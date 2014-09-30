define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/product/product'),
            groupId: null,
            url: function(){
                return Collection.baseApiUrl + '/subcategories/' + this.groupId + '/products'
            }
        });
    }
);