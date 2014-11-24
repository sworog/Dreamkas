define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/invoices/*',
        type: 'DELETE',

        //response
        status: 409,
        responseText: {
            code: 409,
            message: 'Невозможно удалить операцию'
        }
    });
});