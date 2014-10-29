define(function(require, exports, module) {
    //requirements
    var mockjax = require('kit/mockjax/mockjax'),
        config = require('config');

    mockjax({
        url: config.baseApiUrl + '/suppliers',
        status: 200,

        responseText: [{
            "id": "544e28252cde6e70058b4581",
            "name": "\u0412\u044b\u043c\u043f\u0435\u043b",
            "bankAccounts": []
        }]
    });
});