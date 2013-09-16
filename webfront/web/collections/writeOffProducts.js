define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/writeOffProduct'),
            initialize: function(opt) {
                this.writeOffId = opt.writeOffId;
            },
            url: function() {
                return LH.baseApiUrl + '/writeoffs/' + this.writeOffId + '/products'
            }
        });
    }
);