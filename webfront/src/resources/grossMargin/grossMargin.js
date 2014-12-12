define(function(require, exports, module) {
    //requirements
    var Resource = require('kit/resource/resource');

    require('./mocks/get');

    return new Resource({
        url: CONFIG.baseApiUrl + '/reports/grossMargin'
    });
});