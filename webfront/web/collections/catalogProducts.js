define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/product'),
            url: function(){
                if (this.storeId){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/subcategories/' + this.subcategory + '/products'
                } else {
                    return LH.baseApiUrl + '/subcategories/' + this.subcategory + '/products'
                }
            },
            initialize: function(models, options){
                this.subcategory = options.subcategory;
                this.storeId = options.storeId;
            }
        });
    }
);