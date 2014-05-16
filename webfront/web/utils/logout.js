define(function(require) {
    //requirements
    var cookie = require('cookies');

    return function(){
        cookie.set('token', '', {path: '/'});
        document.location.href = '/';
    }
});