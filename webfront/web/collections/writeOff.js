define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            writeOffModel = require('models/writeOff');

        return BaseCollection.extend({
            model: writeOffModel,
            url: baseApiUrl + "/writeoffs"
        });
    }
);