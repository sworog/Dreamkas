define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('resources/product/model'),
            groupId: null,
            url: function(){
                return Collection.baseApiUrl + '/subcategories/' + this.groupId + '/products'
            }
        });
    }
);