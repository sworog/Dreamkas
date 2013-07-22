define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/product'),
            url: LH.baseApiUrl + "/products"
        });
    }
);