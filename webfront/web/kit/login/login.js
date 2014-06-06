define(function(require) {
    //requirements
    var cookie = require('cookies');

    return function(token){
        cookie.set('token', token, {path: '/'});

        if (document.location.pathname.indexOf('login')>=0){
            document.location.href = '/';
        } else {
            document.location.reload();
        }
    }
});