define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/subCategory'),
            categoryId: null,
            groupId: null,
            storeId: null,
            url: function() {
                if (this.storeId){
                    return Collection.baseApiUrl + '/stores/' + this.storeId + '/categories/' + this.categoryId + '/subcategories'
                } else {
                    return Collection.baseApiUrl + '/categories/' + this.categoryId + '/subcategories'
                }
            }
        });
    }
);