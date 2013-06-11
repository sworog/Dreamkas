define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            ProductModel = require('models/product');

        return BaseCollection.extend({
            model: ProductModel,
            url: baseApiUrl + "/products"
        });
    }
);