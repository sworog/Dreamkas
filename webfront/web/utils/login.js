define(function(require) {
    //requirements
    var cookie = require('utils/cookie');

    return function(token){
        cookie.set('token', token, {path: '/'});
        document.location.reload();
    }
});