define(function(require, exports, module) {
    //requirements
    var Resource = require('kit/resource/resource'),
        config = require('config');

    return new Resource({
        url: config.baseApiUrl + '/products',
        params: {
            limit: 1
        }
    });
});