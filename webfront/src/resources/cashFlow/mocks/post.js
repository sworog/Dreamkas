define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/cashFlows',
        type: 'POST',

        //response
        responseText: {
            id: '5475f90b2cde6ed91e8b45e5',
            direction: 'out',
            date: '2014-11-24T11:10:56+0300',
            amount: 10000,
            comment: 'Нашел под кассой'
        }
    });
});