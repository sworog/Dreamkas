define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            ProductModel = require('models/product');

        return BaseCollection.extend({
            model: ProductModel,
            url: function(){
                return LH.baseApiUrl + '/subcategories/' + this.subCategory + '/products'
            },
            initialize: function(models, options){
                this.subCategory = options.subCategory;
            }
        });
    }
);