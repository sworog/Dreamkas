define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/reports/cashFlows',
        type: 'GET',

        //response
        status: 200,
        responseText: {
            in: 1000,
            out: 10000,
            balance: -9000
        }
    });
});