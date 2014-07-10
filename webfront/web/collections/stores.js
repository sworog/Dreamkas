define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/store'),
            url: Collection.baseApiUrl + '/stores'
        });
    }
);