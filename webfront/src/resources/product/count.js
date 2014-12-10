define(function(require, exports, module) {
    //requirements
    var Resource = require('kit/resource/resource');

    return new Resource({
        url: CONFIG.baseApiUrl + '/products',
        data: [],
        params: {
            limit: 1
        }
    });
});