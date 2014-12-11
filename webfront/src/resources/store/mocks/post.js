define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock'),
        _ = require('lodash');

    return ajaxMock({
        //request
        url: '/stores',
        type: 'POST',

        //response
        status: 201,
        responseText: {
            "id": _.uniqueId('store'),
            "name": "Первый"
        }
    });
});