define(function(require) {
    //requirements
    var cookie = require('cookies');

    return function(token){
        cookie.set('token', token, {path: '/'});
        document.location.reload();
    }
});