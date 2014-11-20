define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    ajaxMock({
        url: '/suppliers/*',
        type: 'DELETE',
        status: 204
    });
});