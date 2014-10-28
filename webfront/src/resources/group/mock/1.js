define(function(require, exports, module) {
    //requirements
    var mockjax = require('kit/mockjax/mockjax'),
        config = require('config');

    mockjax({
        url: config.baseApiUrl + '/suppliers',
        status: 200,

        responseText: [{
            "id": "544e282c2cde6e21668b45bb",
            "name": "Алкашка",
            "rounding": {"name": "nearest1", "title": "\u0434\u043e \u043a\u043e\u043f\u0435\u0435\u043a"}
        }]

    });
});