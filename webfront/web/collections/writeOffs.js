define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/writeOff'),
            url: LH.baseApiUrl + "/writeoffs"
        });
    }
);