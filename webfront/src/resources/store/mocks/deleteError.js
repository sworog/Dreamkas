define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/stores/*',
        type: 'DELETE',

        //response
        status: 409,
        responseText: {
            code: 409,
            message: 'Для удаления магазина очистите все остатки товаров в нем.'
        }
    });
});