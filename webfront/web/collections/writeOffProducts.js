define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            writeOffProductModel = require('models/writeOffProduct');

        return BaseCollection.extend({
            model: writeOffProductModel,

            initialize: function(opt) {
                this.writeOffId = opt.writeOffId;
            },
            url: function() {
                return baseApiUrl + '/writeoffs/' + this.writeOffId + '/products'
            }
        });
    }
);