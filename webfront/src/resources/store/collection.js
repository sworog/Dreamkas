define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('resources/store/model'),
            url: Collection.baseApiUrl + '/stores'
        });
    }
);