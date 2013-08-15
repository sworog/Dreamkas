define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/product'),
            url: function(){
                if (this.storeId){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/subcategories/' + this.subCategory + '/products'
                } else {
                    return LH.baseApiUrl + '/subcategories/' + this.subCategory + '/products'
                }
            },
            initialize: function(models, options){
                this.subCategory = options.subCategory;
                this.storeId = options.storeId;
            }
        });
    }
);