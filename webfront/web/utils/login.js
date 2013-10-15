define(function(require) {
    //requirements
    var cookie = require('kit/libs/cookie');

    return function(token){
        cookie.set('token', token, {path: '/'});
        document.location.reload();
    }
});