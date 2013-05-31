define(
    [
        './baseCollection.js',
        '/models/writeOff.js'
    ],
    function(BaseCollection, writeOffModel) {
        return BaseCollection.extend({
            model: writeOffModel,
            url: baseApiUrl + "/writeoffs"
        });
    }
);