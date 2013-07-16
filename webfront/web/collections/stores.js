define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            StoreModel = require('models/store');

        return BaseCollection.extend({
            model: StoreModel,
            url: LH.baseApiUrl + '/stores'
        });
    }
);