define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    ajaxMock({
        url: '/cashFlows/*',
        type: 'DELETE',
        status: 204
    });
});