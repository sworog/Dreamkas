define(function(require, exports, module) {
    //requirements
    var Resource = require('kit/resource/resource');

    require('./mocks/get_3');

    return new Resource({
        url: CONFIG.baseApiUrl + '/firstStart'
    });
});