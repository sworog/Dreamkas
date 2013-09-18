define(function(require) {
    //requirements
    var cookie = require('kit/utils/cookie');

    return function(token){
        cookie.set('token', '', {path: '/'});
        document.location.href = '/';
    }
});