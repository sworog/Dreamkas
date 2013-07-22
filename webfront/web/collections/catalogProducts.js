define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/product'),
            url: function(){
                return LH.baseApiUrl + '/subcategories/' + this.subCategory + '/products'
            },
            initialize: function(models, options){
                this.subCategory = options.subCategory;
            }
        });
    }
);