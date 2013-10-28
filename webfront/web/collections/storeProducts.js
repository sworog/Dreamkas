define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/storeProduct'),
            initialize: function(models, opt) {
                this.storeId = opt.storeId;
                this.subCategory = opt.subCategory;
            },
            url: function() {
                if (this.subCategory){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/subcategories/' + this.subCategory + '/products'
                } else {
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/products'
                }
            }
        });
    }
);