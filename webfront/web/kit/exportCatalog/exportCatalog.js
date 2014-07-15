define(function(require, exports, module) {
    //requirements
    var cookies = require('cookies'),
        config = require('config');

    return function() {
        return $.ajax({
            url: config.baseApiUrl + '/integration/export/products',
            dataType: 'json',
            type: 'GET',
            headers: {
                Authorization: 'Bearer ' + cookies.get('token')
            }
        })
    };
});