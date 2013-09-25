define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/storeProduct'),
            initialize: function(models, opt) {
                this.storeId = opt.storeId;
            },
            url: function() {
                return LH.baseApiUrl + '/stores/' + this.storeId + '/products'
            }
        });
    }
);