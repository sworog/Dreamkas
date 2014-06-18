define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/product'),
            url: function() {
                if (this.storeId) {
                    return LH.baseApiUrl + "/stores/" + this.storeId + "/products";
                } else {
                    return LH.baseApiUrl + "/products";
                }
            },
            initialize: function(models, options) {
                if (options.storeId) {
                    this.storeId = options.storeId;
                } else {
                    this.storeId = null;
                }
            }
        });
    }
);