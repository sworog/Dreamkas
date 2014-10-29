define(function(require, exports, module) {
    //requirements
    var mockjax = require('kit/mockjax/mockjax'),
        config = require('config');

    mockjax({
        url: config.baseApiUrl + '/stores',
        type: 'GET',

        status: 200,
        responseText: [
            {
                "id": "544e281e2cde6ea7798b4588",
                "name": "Первый"
            },
            {
                "id": "544e281e2cde6ea7798b4589",
                "name": "Второй"
            },
            {
                "id": "544e281e2cde6ea7798b4590",
                "name": "Третий"
            }
        ]

    });
});