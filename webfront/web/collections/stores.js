define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/store'),
            url: LH.baseApiUrl + '/stores'
        });
    }
);