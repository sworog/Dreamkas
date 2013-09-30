define(function(require) {
    //requirements
    var cookie = require('kit/utils/cookie');

    return function(){
        cookie.set('token', '', {path: '/'});
        document.location.href = '/';
    }
});