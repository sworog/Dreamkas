define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/storeProduct'),
            initialize: function(models, opt) {
                this.storeId = opt.storeId;
                this.subcategory = opt.subcategory;
            },
            url: function() {
                if (this.subcategory){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/subcategories/' + this.subcategory + '/products'
                } else {
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/products'
                }
            }
        });
    }
);