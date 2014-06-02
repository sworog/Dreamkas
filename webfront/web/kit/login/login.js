define(function(require) {
    //requirements
    var cookie = require('cookies');

    return function(token){
        cookie.set('token', token, {path: '/'});

        if (window.PAGE.route === 'login(/)'){
            document.location.href = '/';
        } else {
            document.location.reload();
        }
    }
});