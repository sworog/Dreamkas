define(function(require) {
    //requirements
    var cookie = require('kit/libs/cookie');

    return function(){
        cookie.set('token', '', {path: '/'});
        document.location.href = '/';
    }
});