define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/writeOffProduct'),
            initialize: function(opt) {
                this.writeOffId = opt.writeOffId;
                this.storeId = opt.storeId;
            },
            url: function() {
                return LH.baseApiUrl + '/stores/' + this.storeId + '/writeoffs/' + this.writeOffId + '/products'
            }
        });
    }
);